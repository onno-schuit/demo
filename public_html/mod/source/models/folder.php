<?php 

require_once(dirname(__FILE__) . '/../helpers/helper.group.php');

class folder extends model {

    static $table_name = 'source_path';

    public static function load_all($where_clause = false, $include = false, $params = null, $limitfrom = null, $limitnum = null) {
        if ($where_clause) {
            $where_clause .= " AND ";
        }
        $where_clause .= sprintf("group_id=%d", helper_group::get_group_id());
        $where_clause .= " ORDER BY path";
        return parent::load_all($where_clause, $include, $params, $limitfrom, $limitnum);
    } 

    public static function delete_record($folder_id) {
        global $DB;
        
        // remove all files in this folder
        require_once(dirname(__FILE__) . '/file.php');
        $files_in_folder = $DB->get_records_sql(
            sprintf("SELECT files_id FROM {source_file} sf
            INNER JOIN {files} f ON (sf.files_id=f.id)
            WHERE sf.path_id=%d", $folder_id)
        );
        foreach ($files_in_folder as $file) {
            file::delete_record($file->files_id);
        }
        
        // delete all subfolders
        $folders_in_folder = self::load_all("parent_id=?", false, array($folder_id));
        foreach ($folders_in_folder as $folder) {
            self::delete_record($folder->id);
        }
        
        // delete this folder
        $DB->delete_records_select(self::$table_name, "id=?", array($folder_id));
    }
  
}

?>