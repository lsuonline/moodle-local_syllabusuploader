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

defined('MOODLE_INTERNAL') || die();

class upload_model {

    /** Simple CRUD operations to handle the files.
     *
     * @param type form object
     * @return boolean
     */
    public function save($object) {
        global $DB;

        // Transform the object.
        $trob = $this->transform($object);

        // Try to insert the record.
        try {
            // Insert the record and return true/false.
            $response = $DB->insert_record('local_syllabusuploader_file', $trob, $returnid = true);
            return $response;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function update($object) {
        global $DB;

        // Try to update the record.
        try {
            // Set the id.
            $object->id = $object->idfile;
            // Update the record and return true/false.
            $response = $DB->update_record('local_syllabusuploader_file', $object, false);
            return $response;
        } catch (Exception $ex) {

        }
    }

    public function get($instance) {
        global $DB;

        // Try to get the records.
        try {
            // Return the data.
            return $DB->get_records('local_syllabusuploader_file', array('instance' => $instance), null, 'instance, local_syllabusuploader_files')[$instance];
        } catch (Exception $ex) {

        }
    }

    public function delete($pfileid, $mfileid) {
        global $DB;
        // Try to delete the records.
        try {
            // Delete the record.
            $DB->delete_records('local_syllabusuploader_file', array("id" => $pfileid));
            // Get the file manager.
            $fs = get_file_storage();
            // Get the file.
            $file = $fs->get_file_by_id($mfileid);
            // Delete it.
            $file->delete();

        } catch (Exception $ex) {
            error_log("Uh Oh....NOTICE - something didn't delete properly");
        }
    }

    public function transform($object) {
        global $DB;

        // Build the SQL.
        $sql = "SELECT * FROM mdl_files
            WHERE itemid = ". $object->syllabusuploader_file."
            AND filename <> '.'
            AND filearea <> 'draft'";

        // Get the files.
        $files = $DB->get_records_sql($sql);

        // Count them.
        $count = count($files);

        // If we have 1 file.
        if ($count == 1) {

            // Set file to the value.
            $files = array_values($files);

            // Trurn the array.
            return array(
                "fileid" => $files[0]->id,
                "filename" => $files[0]->filename,
                "itemid" => $files[0]->itemid,
                "timecreated" => $files[0]->timecreated,
                "timemodified" => $files[0]->timemodified,
            );
        } else {
            error_log("\n\n\e[0;31m****************************************************");
            error_log("\e[0;31mupload_model -> ERROR: returned multiple objects");
            error_log("\e[0;31m****************************************************\n\n");
        }
    }
}
