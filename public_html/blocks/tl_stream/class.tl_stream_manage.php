<?php

class tl_stream_manage {
    
    /**
     * Shows config screen 
     * 
     * @return void
     */
    static public function display() {
        global $OUTPUT;
        
        $result = '';
        $result .= $OUTPUT->heading('Possible streams for the ThinkLink Stream Block');
        $kinds = self::get_kinds();
        
        // include 'html' template
        ob_start();
        include('tpl_admin_stream_kinds.php');
        $contents = ob_get_clean();
                $result .= $OUTPUT->box($contents);
        
        echo $result;
    }
    
    /**
     * Returns all rows from tl_stream_kind ordered by short_code
     * 
     * @return
     */
    static public function get_kinds() {
        global $DB;
        return $DB->get_records_select('tl_stream_kind', '', null, 'short_code');
    }
    
    /**
     * Updates existing records ($post_data['records']) and adds a new one ($post_data['new_record'])
     * 
     * @param array $post_data
     * @return void
     */
    static public function update($post_data) {
        global $DB;
        if (!is_array($post_data['records'])) { return; }
        foreach ($post_data['records'] as $id => $row) {
            $row['id'] = $id;
            $DB->update_record('tl_stream_kind', (object)$row);
        }
        
        $new_record = $post_data['new_record'];
        if (!empty($new_record['short_code']) && !empty($new_record['description'])) {
            $DB->insert_record('tl_stream_kind', (object)$new_record);
        }
        
        if (isset($post_data['delete']) && !empty($post_data['delete']) && is_array($post_data['delete'])) {
            $ids = join(',', array_map('intval', $post_data['delete'])); // make sure we only put integers in our query
            $DB->delete_records_select('tl_stream_kind', "id IN ($ids)");
        }
        
    }
    
    
}

?>