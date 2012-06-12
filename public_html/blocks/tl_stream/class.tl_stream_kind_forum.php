<?php

require_once("class.tl_stream_kind_base.php");

    
class tl_stream_kind_forum extends tl_stream_kind_base {
    
    protected $_short_code = 'forum';
    protected $_max_cache_age = 30; // 30 seconds
    
    /**
     * tl_stream_kind_forum::gather()
     * 
     * @return void
     */
    public function gather() {
        require_once("class.helper_moodle_system.php");
        global $DB;
        
        # get all courses this user is enrolled to
        $course_ids = helper_moodle_system::get_courses_from_users($this->_user_id); // param can be int or array
        
        if (empty($course_ids)) { return; }
        
        # get timestamp from last message
        $last_timestamp = $this->get_cache_timestamp();  

        # get direct stream messages from myself and from all students that I'm sharing a course with 
        $sql = sprintf("
            SELECT p.*,d.course,d.forum as forum_instance,CONCAT_WS(' ', u.firstname, u.lastname) AS user 
            FROM {forum_posts} p
            INNER JOIN {user} u ON (p.userid) = u.id
            INNER JOIN {forum_discussions} d ON (p.discussion=d.id)
            WHERE d.course IN (%s)
            AND p.modified >= %d
            ORDER BY p.modified DESC",
            join(',', $course_ids),
            $last_timestamp
        );
        $records = $DB->get_records_sql($sql);
        
        foreach ($records as $record) {
            // check viewing capabilities on the forum
            // derived from /mod/forum/discuss.php
            $tmp_context_module = get_coursemodule_from_instance('forum', $record->forum_instance, $record->course, false, MUST_EXIST);
            $context = get_context_instance(CONTEXT_MODULE, $tmp_context_module->id);
            if (!has_capability('mod/forum:viewdiscussion', $context)) {
                continue; // continue with the next foreach element, because we can't view this discussion
            }
            
            $flags = ($record->userid == $this->_user_id) ? 'P' : '';
            $meta = array(
                'discussion' => $record->discussion // save discussion_id for read more link
            );
            $this->_save_cache_record(
                array(
                    'user_id' => $this->_user_id,
                    'stream_kind_id' => $this->_get_stream_kind_id(),
                    'src_uid' => $record->id,
                    'title' => $record->subject,
                    'contents' => substr(strip_tags($record->message), 0, 160),
                    'meta' => serialize($meta),
                    'date_and_time' => $record->modified
                )
                , $flags
            );
        }
        
        // /mod/forum:viewdiscussion
        parent::gather(); // updates the cache time
    }
    
    public function get_read_more_link(&$item) {
        global $CFG;
        $meta = unserialize($item->meta);
        $result = $CFG->wwwroot . sprintf("/mod/forum/discuss.php?d=%d#p%d",
            $meta['discussion'],
            $item->src_uid
        );
        return $result;
    }
    
}



?>