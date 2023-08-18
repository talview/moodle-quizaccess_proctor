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
 * Upgrade script for plugin.
 *
 * @package    quizaccess_proctor
 * @author     Talview Inc.
 * @copyright  Talview, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot  . '/mod/quiz/accessrule/proctor/lib.php');

/**
 * Function to upgrade quizaccess_proctor plugin.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool Result.
 */
function xmldb_quizaccess_proctor_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    // Automatically generated Moodle v3.9.0 release upgrade line.
    // Put any upgrade step following this.

    if ($oldversion < 2023080101) {

        // Changing the default of field tsbenabled on table quizaccess_proctor to 0.
        $table = new xmldb_table('quizaccess_proctor');
        $field = new xmldb_field('tsbenabled', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0', 'proctortype');

        // Launch change of default for field tsbenabled.
        $dbman->change_field_default($table, $field);

        // Proctor savepoint reached.
        upgrade_plugin_savepoint(true, 2023080101, 'quizaccess', 'proctor');
    }

    if ($oldversion < 2023081001) {
        // Define field reference_link to be added to quizaccess_proctor.
        $table = new xmldb_table('quizaccess_proctor');
        $field = new xmldb_field('instructions', XMLDB_TYPE_TEXT, null, null, null, null, null, 'reference_link');

        // Conditionally launch add field reference_link.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Proctor savepoint reached.
        upgrade_plugin_savepoint(true, 2023081001, 'quizaccess', 'proctor');
    }

    if ($oldversion < 2023081801) {
        // Define field reference_link to be added to quizaccess_proctor.
        $table = new xmldb_table('quizaccess_proctor');
        $field = new xmldb_field('reference_link', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');

        // Conditionally launch add field reference_link.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        if ($dbman->field_exists($table, $field)) {
            $records = $DB->get_records('quizaccess_proctor');
            foreach ($records as $record) {
                $record->reference_link = '';
                $DB->update_record('quizaccess_proctor', $record);
            }
        }

        // Proctor savepoint reached.
        upgrade_plugin_savepoint(true, 2023081801, 'quizaccess', 'proctor');
    }
    // Automatically generated Moodle v4.0.0 release upgrade line.
    // Put any upgrade step following this.

    return true;
}
