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
 * Global configuration settings for the quizaccess_proctor plugin.
 *
 * @package    quizaccess_proctor
 * @author     Talview Inc.
 * @copyright  Talview, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $ADMIN;

if ($hassiteconfig) {

    $settings->add(new admin_setting_heading(
        'quizaccess_proctor/proctor',
        '',
        $OUTPUT->notification(get_string('setting:supportedversions', 'quizaccess_proctor'), 'warning')));

    $settings->add(new admin_setting_configcheckbox('quizaccess_proctor/enableproctor',
        get_string('setting:enableproctor', 'quizaccess_proctor'),
        get_string('setting:enableproctor_desc', 'quizaccess_proctor'),
        '1'));

    $name = 'quizaccess_proctor/proview_callback_url';
    $title = get_string('setting:proview_callback_url_desc', 'quizaccess_proctor');
    $description = get_string('setting:proview_callback_url_desc', 'quizaccess_proctor');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);

    $name = 'quizaccess_proctor/proview_admin_username';
    $title = get_string('setting:proview_admin_username', 'quizaccess_proctor');
    $description = get_string('setting:proview_admin_username_desc', 'quizaccess_proctor');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);

    $name = 'quizaccess_proctor/proview_admin_password';
    $title = get_string('setting:proview_admin_password', 'quizaccess_proctor');
    $description = get_string('setting:proview_admin_password_desc', 'quizaccess_proctor');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
}

