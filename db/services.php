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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR SURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    local_syllabusuploader
 * @copyright  2023 onwards LSU Online & Continuing Education
 * @copyright  2023 onwards Tim Hunt, Robert Russo, David Lowe
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// We defined the web service functions to install.
$functions = array(
    'local_syllabusuploader_sujax' => array(
        'classname'   => 'local_syllabusuploader_external',
        'methodname'  => 'sujax',
        'classpath'   => 'local/syllabusuploader/externallib.php',
        'description' => 'Entry point for Syllabus File Uploading Rest Services',
        'type'        => 'write',
        'ajax'        => true
    ),
);

// We define the services to install as pre-build services.
// A pre-build service is not editable by administrator.
$services = array(
    'SU Service' => array(
        'functions' => array (
            'local_syllabusuploader_sujax'
        ),
        'restrictedusers' => 0,
        'enabled' => 1
    )
);
