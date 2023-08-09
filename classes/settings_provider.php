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
 * Class for providing quiz settings, to make setting up quiz form manageable.
 *
 * To make sure there are no inconsistencies between data sets, run tests in tests/phpunit/settings_provider_test.php.
 *
 * @package    quizaccess_proctor
 * @author     Talview Inc.
 * @copyright  Talview, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_proctor;

use context_module;
use context_user;
use lang_string;
use stdClass;
use stored_file;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper class for providing quiz settings, to make setting up quiz form manageable.
 *
 * @copyright  2020 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class settings_provider {

    /**
     * Insert form element.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     * @param \HTML_QuickForm_element $element Element to insert.
     * @param string $before Insert element before.
     */
    protected static function insert_element(\mod_quiz_mod_form $quizform,
                                             \MoodleQuickForm $mform, \HTML_QuickForm_element $element, $before = 'security') {
        $mform->insertElementBefore($element, $before);
    }

    /**
     * Remove element from the form.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     * @param string $elementname Element name.
     */
    protected static function remove_element(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform, string  $elementname) {
        if ($mform->elementExists($elementname)) {
            $mform->removeElement($elementname);
            $mform->setDefault($elementname, null);
        }
    }

    /**
     * Add help button to the element.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     * @param string $elementname Element name.
     */
    protected static function add_help_button(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform, string $elementname) {
        if ($mform->elementExists($elementname)) {
            $mform->addHelpButton($elementname, $elementname, 'quizaccess_proctor');
        }
    }

    /**
     * Set default value for the element.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     * @param string $elementname Element name.
     * @param mixed $value Default value.
     */
    protected static function set_default(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform, string  $elementname, $value) {
        $mform->setDefault($elementname, $value);
    }

    /**
     * Set element type.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     * @param string $elementname Element name.
     * @param string $type Type of the form element.
     */
    protected static function set_type(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform, string $elementname, string $type) {
        $mform->setType($elementname, $type);
    }

    /**
     * Freeze form element.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     * @param string $elementname Element name.
     */
    protected static function freeze_element(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform, string $elementname) {
        if ($mform->elementExists($elementname)) {
            $mform->freeze($elementname);
        }
    }

    /**
     * Add proctor header element to  the form.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    protected static function add_proctor_header_element(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform) {
        global  $OUTPUT;

        $element = $mform->createElement('header', 'proctor', get_string('proctorsettings', 'quizaccess_proctor'));
        self::insert_element($quizform, $mform, $element);

    }

    /**
     * Add proctor usage element with all available options.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    protected static function add_proctor_usage_options(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform) {
        $element = $mform->createElement(
            'select',
            'proctortype',
            get_string('selectproctor', 'quizaccess_proctor'),
            self::get_proctor_options($quizform->get_context())
        );

        self::insert_element($quizform, $mform, $element);
        self::set_type($quizform, $mform, 'proctortype', PARAM_RAW);
        self::set_default($quizform, $mform, 'proctortype', 'noproctor');
        self::add_help_button($quizform, $mform, 'proctortype');

        $element1 = $mform->createElement(
            'textarea',
            'instructions',
            get_string('instructions', 'quizaccess_proctor'),
            ['style' => 'width: 100%;']
        );
        self::insert_element($quizform, $mform, $element1);
        self::set_type($quizform, $mform, 'instruction', PARAM_TEXT);
        self::set_default($quizform, $mform, 'instruction', '');

        $element2 = $mform->createElement(
            'checkbox',
            'tsbenabled',
            get_string('tsbenable', 'quizaccess_proctor'))
        ;
        self::insert_element($quizform, $mform, $element2);
    }

     /**
     * Add setting fields.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    public static function add_proctor_settings_fields(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform) {
        //if (self::can_configure_seb($quizform->get_context())) {
            self::add_proctor_header_element($quizform, $mform);
            self::add_proctor_usage_options($quizform, $mform);
       // }
    }


    
    /**
     * Hide proctor elements if required.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    protected static function hide_proctor_elements(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform) {
        foreach (self::get_quiz_hideifs() as $elname => $rules) {
            if ($mform->elementExists($elname)) {
                foreach ($rules as $hideif) {
                    $mform->hideIf(
                        $hideif->get_element(),
                        $hideif->get_dependantname(),
                        $hideif->get_condition(),
                        $hideif->get_dependantvalue()
                    );
                }
            }
        }
    }

    
    /**
     * Returns a list of all options of proctor usage.
     *
     * @param \context $context Context used with capability checking selection options.
     * @return array
     */
    public static function get_proctor_options(\context $context) : array {
        
        $options[''] = get_string('selectproctor', 'quizaccess_proctor');
        $options['noproctor'] = get_string('noproctor', 'quizaccess_proctor');
        $options['ai_proctor'] = get_string('aiproctor', 'quizaccess_proctor');
        $options['record_and_review'] = get_string('recordandreview', 'quizaccess_proctor');
        $options['live_proctor'] = get_string('liveproctor', 'quizaccess_proctor');
        

        return $options;
    }

    
    /**
     * Check if settings is locked.
     *
     * @param int $quizid Quiz ID.
     * @return bool
     */
    public static function is_proctor_settings_locked($quizid) : bool {
        if (empty($quizid)) {
            return false;
        }

        return quiz_has_attempts($quizid);
    }

    /**
     * Filter a standard class by prefix.
     *
     * @param stdClass $settings Quiz settings object.
     * @return stdClass Filtered object.
     */
    private static function filter_by_prefix(\stdClass $settings): stdClass {
        $newsettings = new \stdClass();
        foreach ($settings as $name => $setting) {
            // Only add it, if not there.
            if (strpos($name, "proctor_") === 0) {
                $newsettings->$name = $setting; // Add new key.
            }
        }
        return $newsettings;
    }

    /**
     * Filter settings based on the setting map. Set value of not allowed settings to null.
     *
     * @param stdClass $settings Quiz settings.
     * @return \stdClass
     */
    private static function filter_by_settings_map(stdClass $settings) : stdClass {
        if (!isset($settings->proctor_proctor)) {
            return $settings;
        }

        $newsettings = new \stdClass();
        $newsettings->proctor_proctor = $settings->proctor_proctor;
        $allowedsettings = self::get_allowed_settings((int)$newsettings->proctor_proctor);
        unset($settings->proctor_proctor);

        foreach ($settings as $name => $value) {
            if (!in_array($name, $allowedsettings)) {
                $newsettings->$name = null;
            } else {
                $newsettings->$name = $value;
            }
        }

        return $newsettings;
    }

    /**
     * Filter quiz settings for this plugin only.
     *
     * @param stdClass $settings Quiz settings.
     * @return stdClass Filtered settings.
     */
    public static function filter_plugin_settings(stdClass $settings) : stdClass {
        $settings = self::filter_by_prefix($settings);
        $settings = self::filter_by_settings_map($settings);

        return self::strip_all_prefixes($settings);
    }

    /**
     * Strip the proctor_ prefix from each setting key.
     *
     * @param \stdClass $settings Object containing settings.
     * @return \stdClass The modified settings object.
     */
    private static function strip_all_prefixes(\stdClass $settings): stdClass {
        $newsettings = new \stdClass();
        foreach ($settings as $name => $setting) {
            $newname = preg_replace("/^proctor_/", "", $name);
            $newsettings->$newname = $setting; // Add new key.
        }
        return $newsettings;
    }

    /**
     * Add prefix to string.
     *
     * @param string $name String to add prefix to.
     * @return string String with prefix.
     */
    public static function add_prefix(string $name): string {
        if (strpos($name, 'proctor_') !== 0) {
            $name = 'proctor_' . $name;
        }
        return $name;
    }
}
