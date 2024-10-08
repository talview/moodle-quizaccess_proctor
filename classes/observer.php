<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Event observer.
 *
 * @package    quizaccess_proctor
 * @copyright  2014 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/quiz/accessrule/proctor/vendor/autoload.php');


/**
 * Event observer.
 * Stores all actions about modules create/update/delete in plugin own's table.
 * This allows the block to avoid expensive queries to the log table.
 *
 * @package    quizaccess_proctor
 * @copyright  2014 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface CustomThrowable extends \Throwable
{

}

class CustomException extends \Exception implements CustomThrowable
{

}

class quizaccess_proctor_observer
{

    /** @var int indicates that course module was created */
    const CM_CREATED = 0;
    /** @var int indicates that course module was udpated */
    const CM_UPDATED = 1;
    /** @var int indicates that course module was deleted */
    const CM_DELETED = 2;

    /**
     * Store all actions about modules create/update/delete in own table.
     *
     * @param \core\event\base $event
     */
    public static function store(\core\event\base $event)
    {
        global $DB;
        $quizaccess_proctor_setting_enabled = get_config('quizaccess_proctor', 'enableproctor');
        $api_base_url = trim(get_config('quizaccess_proctor', 'proview_callback_url'));
        $auth_payload = new \stdClass();
        $auth_payload->username = trim(get_config('quizaccess_proctor', 'proview_admin_username'));
        $auth_payload->password = trim(get_config('quizaccess_proctor', 'proview_admin_password'));
        if (!$quizaccess_proctor_setting_enabled
            || $event->other['modulename'] != 'quiz') {
            return;
        }
        try {
            if (!$api_base_url
                || !$auth_payload->username
                || !$auth_payload->password) {
                throw new CustomException("Configurations Missing for triggering callbacks");
            }
        } catch (\Throwable $err) {
            self::capture_error($err);
            return;
        }
        $eventdata = new \stdClass();
        $quiz = $DB->get_record('quiz', array('id' => $event->other['instanceid']));
        $quiz_proctor_settings = $DB->get_record('quizaccess_proctor', array('quizid' => $event->other['instanceid']));
        $eventdata->quiz_title = $event->other['name'];
        $eventdata->quiz_id = (int)$quiz->id;
        $eventdata->course_id = (int)$event->courseid;
        $eventdata->course_module_id = (int)$event->objectid;
        $eventdata->proctoring_enabled = !($quiz_proctor_settings->proctortype == 'noproctor');
        $eventdata->proctoring_type = ($quiz_proctor_settings->proctortype == 'noproctor') ? NULL : $quiz_proctor_settings->proctortype;
        $eventdata->tsb_enabled = boolval($quiz_proctor_settings->tsbenabled);
        $eventdata->attempts = 0;
        $eventdata->timeopen = (int)$quiz->timeopen;
        $eventdata->timeclose = (int)$quiz->timeclose;
        $eventdata->timelimit = (int)$quiz->timelimit;
        $eventdata->overduehandling = $quiz->overduehandling;
        $eventdata->graceperiod = $quiz->graceperiod;
        $eventdata->timemodified = $quiz->timemodified;
        $eventdata->timecreated = $quiz->timecreated;
        $eventdata->userid = $event->userid;
        switch ($event->eventname) {
            case '\core\event\course_module_created':
                if ($quiz_proctor_settings->proctortype === 'noproctor')
                    return;
                $eventdata->action = self::CM_CREATED;
                break;
            case '\core\event\course_module_updated':
                $eventdata->action = self::CM_UPDATED;
                break;
            case '\core\event\course_module_deleted':
                $eventdata->action = self::CM_DELETED;
                break;
            default:
                return;
        }
        try {
            $auth_response = self::generate_auth_token($api_base_url, $auth_payload);
            if (!$auth_response) {
                throw new CustomException("Auth Token Not generated");
                return;
            }
            $token = $auth_response['access_token'];
            $response = self::send_quiz_details($api_base_url, $token, $eventdata);
        } catch (\Throwable $err) {
            self::capture_error($err);
        }

    }

    private static function generate_auth_token($api_base_url, $payload)
    {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        $curl = new curl();
        $headers = array('Content-Type: application/json');
        $curl->setHeader($headers);
        $request_url = $api_base_url . '/auth';
        $json_payload = json_encode($payload);
        try {
            $response = $curl->post($request_url, $json_payload);
            if ($curl->get_errno()) {
                $error_msg = $curl->error;
                throw new moodle_exception('errorapirequest', 'quizaccess_proctor', '', $error_msg);
            }
            $decoded_response = json_decode($response, true);
            if (!isset($decoded_response['access_token'])) {
                throw new CustomException("Auth Token Not generated");
            }
            return $decoded_response;
        } catch (\Throwable $err) {
            self::capture_error($err);
        }
    }

    private static function send_quiz_details($api_base_url, $token, $eventdata)
    {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        $curl = new curl();
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );
        $curl->setHeader($headers);
        $request_url = $api_base_url . '/quiz';
        $json_eventdata = json_encode($eventdata);
        try {
            $response = $curl->post($request_url, $json_eventdata);
            if ($curl->get_errno()) {
                $error_msg = $curl->error;
                throw new moodle_exception('errorapirequest', 'quizaccess_proctor', '', $error_msg);
            }
            $decoded_response = json_decode($response, true);
            return $decoded_response;
        } catch (\Throwable $err) {
            self::capture_error($err);
        }
    }

    public static function capture_error(\Throwable $err)
    {
        \Sentry\init(['dsn' => 'https://61facdc5414c4c73ab2b17fe902bf9ba@o286634.ingest.sentry.io/5304587']);
        \Sentry\captureException($err);
    }
}
