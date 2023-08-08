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
 * @copyright  2023 onwards Tim Hunt, Robert Russo, David Lowe
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_syllabusuploader\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

global $CFG;
require_once($CFG->dirroot . '/local/syllabusuploader/lib.php');

require_login();

class files_view implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output): array {
        global $CFG;
        
        $context = \context_system::instance();
        $settingspath = get_config('moodle', "local_syllabusuploader_copy_file");

        \syllabusuploader_helpers::upsert_system_folder();

        $nonmoodlefiles = \syllabusuploader_helpers::get_system_file_list();
        $tabledata = \syllabusuploader_helpers::get_syllabusuploader_file_list();
        
        $renderdata = array(
            "syllabusuploader_data" => $tabledata,
            "syllabusuploader_url" => $CFG->wwwroot,
            "currentpath" => $settingspath,
            "non_mood_files" => $nonmoodlefiles

        );
        return $renderdata;
    }
}
