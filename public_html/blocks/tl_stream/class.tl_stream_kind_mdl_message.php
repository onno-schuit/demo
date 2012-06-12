<?php

require_once("class.tl_stream_kind_base.php");

class tl_stream_kind_mdl_message extends tl_stream_kind_base {
    
    protected $_short_code = 'mdl_message';
    protected $_max_cache_age = 60; // in seconds
    
    public function gather() {
        global $DB;
        
        # get timestamp from last message
        $last_timestamp = $this->get_cache_timestamp();  

        # get messages sent to this user (new_messages UNION read_messages)
        $sql = sprintf("
            (SELECT CONCAT('u',id) AS id,useridto,useridfrom,subject,smallmessage,timecreated FROM %s t1 
             WHERE useridto=%d AND timecreated >= %d)
            UNION
            (SELECT CONCAT('r',id) AS id,useridto,useridfrom,subject,smallmessage,timecreated FROM %s t2 
             WHERE useridto=%d AND timecreated >= %d)
            ORDER BY timecreated DESC",
            'mdl_message',
            $this->get_user_id(),
            $last_timestamp,
            'mdl_message_read',
            $this->get_user_id(),
            $last_timestamp
        );
        $records = $DB->get_records_sql($sql);
        
        foreach ($records as $record) {
            // used for making the username clickable
            $user = $DB->get_record_select('user', 'id=?', array('id' => $record->useridfrom));
            $username = $user->firstname . ' ' . $user->lastname;
            $username_with_id = sprintf('[user=%d]%s[/user]', $user->id, $username);
            $this->_save_cache_record(
                array(
                    'user_id' => $this->_user_id,
                    'stream_kind_id' => $this->_get_stream_kind_id(),
                    'src_uid' => $record->id,
                    'title' => str_replace($username, $username_with_id, $record->subject),
                    'contents' => $record->smallmessage,
                    'meta' => serialize(array('useridfrom' => $record->useridfrom)),
                    'date_and_time' => $record->timecreated
                )
                ,'P' // sent messages to this user are always in the personal stream
            );
        }
        parent::gather(); // updates the cache time
    }
    
    public function get_read_more_link(&$item) {
        global $CFG;
        $meta = unserialize($item->meta);
        return $CFG->wwwroot . sprintf("/message/index.php?user2=%d", isset($meta['useridfrom']) ? $meta['useridfrom'] : 0);
    }
    
    
}

?>