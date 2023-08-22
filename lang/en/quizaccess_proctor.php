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
 * Strings for the quizaccess_proctor plugin.
 *
 * @package    quizaccess_proctor
 * @author     Talview Inc.
 * @copyright  Talview, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['default'] = 'Enabled by default';
$string['default_help'] = 'If set, this proctor method will be enabled by default for all new quizzes.';
$string['proctorsettings'] = 'Proview Proctoring Settings';
$string['privacy:metadata:quizid'] = 'Quiz ID';
$string['proctortype'] = 'Proctoring Type';
$string['proctortype_help'] = 'The type of proctoring applies to the quiz.';
$string['tsbenable'] = 'Enable Talview Secure Browser';
$string['selectproctor'] = 'Select Proctoring Type';
$string['noproctor'] = 'No Proctoring';
$string['aiproctor'] = 'AI Proctoring';
$string['recordandreview'] = 'Record and Review Proctoring';
$string['liveproctor'] = 'Live Proctoring';
$string['pluginname'] = 'Proctoring Settings';
$string['invalidproctor'] = 'Invalid Proctoring';
$string['setting:supportedversions'] = 'Supported from Moodle 3.9';
$string['setting:enableproctor'] = 'Enable Proctoring for the quizzes';
$string['setting:enableproctor_desc'] = 'If enabled, the proctoring will be applied to the quiz. Else no proctoring is used for the quizzes.';
$string['setting:proview_callback_url'] = 'Proview Callback URL';
$string['setting:proview_callback_url_desc'] = 'URL provided by Talview to trigger callbacks';
$string['setting:proview_admin_username'] = 'Proview Admin Username';
$string['setting:proview_admin_username_desc'] = 'Username provided by Talview to authenticate callbacks';
$string['setting:proview_admin_password'] = 'Proview Admin Password';
$string['setting:proview_admin_password_desc'] = 'Password provided by Talview to authenticate callbacks';
$string['reference_link'] = 'Reference Link';
$string['reference_link_help'] = 'Kindly provide the reference links in the form url:caption for example, www.google.com : GOOGLE. Separate every pair of url:caption with newline';
$string['invalid_reference_links'] = 'Please enter valid reference links in the format URL:Caption, one per line.';
$string['instructions'] = 'Instructions';