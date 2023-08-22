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
 * Install script for plugin.
 *
 * @package    quizaccess_proctor
 * @author     Talview Inc.
 * @copyright  Talview, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot  . '/mod/quiz/accessrule/proctor/lib.php');

/**
 * Custom code to be run on installing the plugin.
 */
function xmldb_quizaccess_proctor_install() {
    global $DB;
    $params = array();
    $total = $DB->count_records('quiz', $params);
    if ($total > 0) {
        $rs = $DB->get_recordset('quiz', $params);

        $i = 0;
        $pbar = new progress_bar('updatequizrecords', 500, true);

        foreach ($rs as $quiz) {
            if (!$DB->record_exists('quizaccess_proctor', ['quizid' => $quiz->id])) {
                $cm = get_coursemodule_from_instance('quiz', $quiz->id, $quiz->course);

                $proctorsettings = new stdClass();

                $proctorsettings->quizid = $quiz->id;
                $proctorsettings->cmid = $cm->id;
                $proctorsettings->proctortype = 'noproctor';
                $proctorsettings->tsbenabled = 0;
                $proctorsettings->usermodified = get_admin()->id;
                $proctorsettings->timecreated = time();
                $proctorsettings->timemodified = time();

                $DB->insert_record('quizaccess_proctor', $proctorsettings);

            }

            $i++;
            $pbar->update($i, $total, "Reconfiguring existing quizzes to use a new proctor plugin - $i/$total.");
        }

        $rs->close();
    }

    return true;
}
