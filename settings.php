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
 * @package    local_syllabusuploader
 * @copyright  2023 onwards LSU Online & Continuing Education
 * @copyright  2023 onwards Robert Russo, David Lowe
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Set the string for use later.
$fn = new lang_string('foldername', 'local_syllabusuploader');

// Create the folder / submenu.
$ADMIN->add('localplugins', new admin_category('local_syllabusuploader_folder', $fn));

// Create the local settings.
$settings = new admin_settingpage($section="syllabusuploader", get_string('settings'));

// Make sure only admins see this one.
if ($ADMIN->fulltree) {
    // Copy File Settings.
    $settings->add(
        new admin_setting_configtext(
            'local_syllabusuploader_copy_file',
            get_string('syllabusuploader_copy_file', 'local_syllabusuploader'),
            get_string('syllabusuploader_copy_file_help', 'local_syllabusuploader'),
            '/var/moodledata/syllabus/' // Default.
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'local_syllabusuploader_public_path',
            get_string('syllabusuploader_public_path', 'local_syllabusuploader'),
            get_string('syllabusuploader_public_path_help', 'local_syllabusuploader'),
            '/syllabus/' // Default.
        )
    );
}

// Add the folder.
$ADMIN->add('local_syllabusuploader_folder', $settings);

// Prevent Moodle from adding settings local in standard location.
$settings = null;

// Set the url for the ProctorU file uploader.
$suuploader = new admin_externalpage(
    'syllabusuploader_uploader',
    new lang_string('manage_uploader', 'local_syllabusuploader'),
    "$CFG->wwwroot/local/syllabusuploader/uploader.php"
);

// Set the url for the ProctorU file viewer.
$suviewer = new admin_externalpage(
    'syllabusuploader_viewer',
    new lang_string('manage_viewer', 'local_syllabusuploader'),
    "$CFG->wwwroot/local/syllabusuploader/view.php"
);

// Add the ProctorU override tool url.
$context = \context_system::instance();

// Add the link for those who have access.
if (has_capability('local/syllabusuploader:admin', $context)) {
    $ADMIN->add('local_syllabusuploader_folder', $suuploader);
    $ADMIN->add('local_syllabusuploader_folder', $suviewer);
}
