<?php

require_once("class.tl_stream_kind_base.php");

    
class tl_stream_kind_mdl_sent_message extends tl_stream_kind_base {
    
    # Sent messages for this user from the Moodle message system
    
    protected $_short_code = 'mdl_sent_message';
    protected $_max_cache_age = 30; // 30 seconds
    
    /**
     * Replace the default link to the user pofile with a link to the users's codex
     * 
     * @param mixed $title
     * @return
     */
    public function display_title($title) {
        return preg_replace('/user\/profile.php\?id=([0-9]+)/', 'blog/index.php?userid=$1', $title);
    }
    
    public function gather() {
        global $DB;
        
        # get timestamp from last message
        $last_timestamp = $this->get_cache_timestamp();  

        # get messages sent to this user (new_messages UNION read_messages)
        $sql = sprintf("
            (SELECT CONCAT('u',t1.id) AS id,useridto,useridfrom,subject,smallmessage,t1.timecreated,
             CONCAT_WS(' ', u1.firstname, u1.lastname) AS userto 
             FROM %s t1 
             INNER JOIN {user} u1 ON (t1.useridto=u1.id)
             WHERE useridfrom=%d AND t1.timecreated >= %d)
            UNION
            (SELECT CONCAT('r',t2.id) AS id,useridto,useridfrom,subject,smallmessage,t2.timecreated, 
             CONCAT_WS(' ', u2.firstname, u2.lastname) AS userto 
             FROM %s t2 
             INNER JOIN {user} u2 ON (t2.useridto=u2.id)
             WHERE useridfrom=%d AND t2.timecreated >= %d)
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
            $username = $record->userto;
            $username = sprintf('[user=%d]%s[/user]', $record->useridto, $username);
            $this->_save_cache_record(
                array(
                    'user_id' => $this->_user_id,
                    'stream_kind_id' => $this->_get_stream_kind_id(),
                    'src_uid' => $record->id,
                    'title' => sprintf(get_string('title_message_to', 'block_tl_stream'), $username),
                    'contents' => $record->smallmessage,
                    'meta' => serialize(array('useridto' => $record->useridto)),
                    'date_and_time' => $record->timecreated
                ),
                'P' // sent messages are always in the personal stream
            );
        }
        parent::gather(); // updates the cache time
    }

    public function get_read_more_link(&$item) {
        global $CFG;
        $meta = unserialize($item->meta);
        return $CFG->wwwroot . sprintf("/message/index.php?user2=%d", isset($meta['useridto']) ? $meta['useridto'] : 0);
    }
    
}



?>