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
 * Version information for the quizaccess_proctor plugin.
 *
 * @package    quizaccess_proctor
 * @author     Talview Inc.
 * @copyright  Talview, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
$plugin->version = 2024042401;
$plugin->requires = 2020061500;
$plugin->release = '1.4.0 (Build: 2024042401)';
$plugin->component = 'quizaccess_proctor';
$plugin->maturity = MATURITY_STABLE;

$plugin->dependencies = array(
    'mod_quiz' => ANY_VERSION,
);
