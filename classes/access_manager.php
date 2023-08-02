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
 * Manage the access to the quiz.
 *
 * @package    quizaccess_proctor
 * @author     Talview Inc.
 * @copyright  Talview, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_proctor;

use context_module;
use quiz;

defined('MOODLE_INTERNAL') || die();

/**
 * Manage the access to the quiz.
 *
 * @copyright  2020 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class access_manager {

    /** @var quiz $quiz A quiz object containing all information pertaining to current quiz. */
    private $quiz;

    /** @var quiz_settings $quizsettings A quiz settings persistent object containing plugin settings */
    private $quizsettings;

    /** @var context_module $context Context of this quiz activity. */
    private $context;

        /**
     * The access_manager constructor.
     *
     * @param quiz $quiz The details of the quiz.
     */
    public function __construct(quiz $quiz) {
        $this->quiz = $quiz;
        $this->context = context_module::instance($quiz->get_cmid());
        $this->quizsettings = quiz_settings::get_by_quiz_id($quiz->get_quizid());
    }

    /**
     * Check if Safe Exam Browser is required to access quiz.
     * If quizsettings do not exist, then there is no requirement for using proctor.
     *
     * @return bool If required.
     */
    public function tsbenabled() : bool {
        if (!$this->quizsettings) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * This is the basic check for the Safe Exam Browser previously used in the quizaccess_safebrowser plugin that
     * managed basic Moodle interactions with proctor.
     *
     * @return bool
     */
    public function validate_basic_header(): bool {
        if (!$this->should_validate_basic_header()) {
            // Config key should not be checked, so do not prevent access.
            return true;
        }

        if ($this->get_proctor_use_type() == settings_provider::USE_proctor_CLIENT_CONFIG) {
            return $this->is_using_proctor();
        }
        return true;
    }

    /**
     * Check if using Safe Exam Browser.
     *
     * @return bool
     */
    public function is_using_proctor(): bool {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return strpos($_SERVER['HTTP_USER_AGENT'], 'proctor') !== false;
        }

        return false;
    }

    /**
     * Check if user has any capability to bypass the Safe Exam Browser requirement.
     *
     * @return bool True if user can bypass check.
     */
    public function can_bypass_proctor(): bool {
        return has_capability('quizaccess/proctor:bypassproctor', $this->context);
    }

    /**
     * Return the full URL that was used to request the current page, which is
     * what we need for verifying the X-SafeExamBrowser-RequestHash header.
     */
    private function get_this_page_url(): string {
        global $CFG, $FULLME;
        // If $FULLME not set fall back to wwwroot.
        if ($FULLME == null) {
            return $CFG->wwwroot;
        }
        return $FULLME;
    }

    /**
     * Return expected proctor config key.
     *
     * @return string|null
     */
    public function get_valid_config_key(): ?string {
        return $this->validconfigkey;
    }

    /**
     * Getter for the quiz object.
     *
     * @return quiz
     */
    public function get_quiz() : quiz {
        return $this->quiz;
    }

    /**
     * Get type of proctor usage for the quiz.
     *
     * @return char
     */
    public function get_proctor_use_type(): char {
        if (empty($this->quizsettings)) {
            return 'noproctor';
        } else {
            return $this->quizsettings->get('proctortype');
        }
    }

        /**
     * Set session access for quiz.
     *
     * @param bool $accessallowed
     */
    public function set_session_access(bool $accessallowed): void {
        global $SESSION;
        if (!isset($SESSION->quizaccess_proctor_access)) {
            $SESSION->quizaccess_proctor_access = [];
        }
        $SESSION->quizaccess_proctor_access[$this->quiz->get_cmid()] = $accessallowed;
    }

    /**
     * Check session access for quiz if already set.
     *
     * @return bool
     */
    public function validate_session_access(): bool {
        global $SESSION;
        return !empty($SESSION->quizaccess_proctor_access[$this->quiz->get_cmid()]);
    }

    /**
     * Unset the global session access variable for this quiz.
     */
    public function clear_session_access(): void {
        global $SESSION;
        unset($SESSION->quizaccess_proctor_access[$this->quiz->get_cmid()]);
    }

    /**
     * Redirect to proctor config link. This will force Safe Exam Browser to be reconfigured.
     */
    public function redirect_to_proctor_config_link(): void {
        global $PAGE;

        $proctorlink = \quizaccess_proctor\link_generator::get_link($this->quiz->get_cmid(), true, is_https());
        $PAGE->requires->js_amd_inline("document.location.replace('" . $proctorlink . "')");
    }

    /**
     * Check if we need to redirect to proctor config link.
     *
     * @return bool
     */
    public function should_redirect_to_proctor_config_link(): bool {
        // We check if there is an existing config key header. If there is none, we assume that
        // the proctor application is not using header verification so auto redirect should not proceed.
        $haskeyinheader = !is_null($this->get_received_config_key());

        return $this->is_using_proctor()
                && get_config('quizaccess_proctor', 'autoreconfigureproctor')
                && $haskeyinheader;
    }
}
