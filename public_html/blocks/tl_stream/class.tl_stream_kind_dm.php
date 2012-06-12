<?php

require_once("class.tl_stream_kind_base.php");

class tl_stream_kind_dm extends tl_stream_kind_base {
    
    protected $_short_code = 'dm';
    protected $_max_cache_age = 30; // in seconds
    
    /**
     * Replace the default link to the user pofile with a link to the users's codex
     * 
     * @param mixed $title
     * @return
     */
    public function display_title($title) {
        return preg_replace('/user\/profile.php\?id=([0-9]+)/', 'blog/index.php?userid=$1', $title);
    }

    public function delete($id) {
        global $DB, $USER;

        $src_uid = $DB->get_field_select(self::get_cache_table(), 'src_uid', 'id=?', array('id' => $id));

        // delete record from the direct messages table
        $DB->delete_records_select('tl_stream_dm', 'id=? AND user_id=?',
            array('id' => $src_uid, 'user_id' => $USER->id));
            
        // delete record from the cache table
        $DB->delete_records_select('tl_stream_cache', 'src_uid=? AND stream_kind_id=?',
            array($src_uid, $this->_stream_kind_id));
                    
        return parent::delete($id);
    }
    
    public function gather() {
        require_once("class.helper_moodle_system.php");
        global $DB;
        
        # get all courses this user is enrolled to
        $course_ids = helper_moodle_system::get_courses_from_users($this->_user_id); // param can be int or array
        
        # get all users enrolled in one or more courses
        $user_ids = helper_moodle_system::get_users_from_courses($course_ids); // param can be int or array

        $user_ids[] = $this->_user_id; // in case this user does not belong to any courses
        
        # get timestamp from last message
        $last_timestamp = $this->get_cache_timestamp();  

        # get direct stream messages from myself and from all students that I'm sharing a course with 
        $sql = sprintf("
            SELECT dm.*,CONCAT_WS(' ', u.firstname, u.lastname) AS user 
            FROM %s dm
            INNER JOIN {user} u ON (dm.user_id) = u.id
            WHERE user_id IN (%s)
            AND dm.timecreated >= %d
            ORDER BY dm.timecreated DESC",
            $DB->get_prefix() . 'tl_stream_dm',
            join(',', $user_ids),
            $last_timestamp
        );
        $records = $DB->get_records_sql($sql);
        
        foreach ($records as $record) {
            $flags = ($record->user_id == $this->_user_id) ? 'P' : '';
            $username = sprintf("[user=%d]%s[/user]", $record->user_id, $record->user);
            $this->_save_cache_record(
                array(
                    'user_id' => $this->_user_id,
                    'stream_kind_id' => $this->_get_stream_kind_id(),
                    'src_uid' => $record->id,
                    'title' => sprintf(get_string('title_stream_message', 'block_tl_stream'), $username),
                    'contents' => $record->message,
                    'meta' => '',
                    'date_and_time' => $record->timecreated
                ),
                $flags // always personal stream
            );
        }
        parent::gather(); // updates the cache time
    }
    
    public function has_delete(&$item) {
        global $USER;
        return ($item->is_personal);
    }
    
    public function has_read_more() {
        return false;
    }
    
    public function needs_cache_update() {
        return true;
    }
    
  
}

?>