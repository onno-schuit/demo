<?php

require_once("class.tl_stream_kind_base.php");

    
class tl_stream_kind_calendar extends tl_stream_kind_base {
    
    protected $_short_code = 'calendar';
    protected $_max_cache_age = 30; // 30 seconds
    
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
            SELECT e.*,CONCAT_WS(' ', u.firstname, u.lastname) AS user, u.id as userid 
            FROM {event} e
            INNER JOIN {user} u ON (e.userid) = u.id
            WHERE e.courseid IN (%s)
            AND e.timemodified >= %d
            ORDER BY e.timemodified DESC",
            join(',', $course_ids),
            $last_timestamp
        );
        $records = $DB->get_records_sql($sql);
        
        foreach ($records as $record) {
            $meta = array(
                'course' => $record->courseid,
                'date' => $record->timestart
            );
            $flags = ($record->userid == $this->_user_id) ? 'P' : '';
            $this->_save_cache_record(
                array(
                    'user_id' => $this->_user_id,
                    'stream_kind_id' => $this->_get_stream_kind_id(),
                    'src_uid' => $record->id,
                    'title' => $record->name,
                    'contents' => substr(strip_tags($record->description), 0, 160),
                    'meta' => serialize($meta),
                    'date_and_time' => $record->timemodified
                ),
                $flags
            );
        }
        parent::gather(); // updates the cache time
    }
    
    public function get_read_more_link(&$item) {
        global $CFG;
        $meta = unserialize($item->meta);
        $result = sprintf($CFG->wwwroot . "/calendar/view.php?course=%d&view=day&cal_d=%d&cal_m=%d&cal_y=%d#event_%d",
            $meta['course'],
            date('d', $meta['date']),
            date('m', $meta['date']),
            date('Y', $meta['date']),
            $item->src_uid
        );
        return $result;
    }
    
}



?>