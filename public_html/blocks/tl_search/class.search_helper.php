<?php

class search_helper {
    

    static protected $_cached_users = array();
    static protected $_cached_courses = array();

        /**
     * Returns array of course ids where user(s) is enrolled to 
     * 
     * @param array $user_ids
     * @return array 
     */
    static public function get_courses_from_users($user_ids) {
        global $DB;
        if (!is_array($user_ids)) {
            $user_ids = array($user_ids);
        }

        if (empty($user_ids)) { return array(); }

        // some caching stuff, because the SELECT query might be slow
        sort($user_ids);
        $cache_index = join(',', $user_ids); // $_cached_courses[id1,id2,id3,..] 
        if (isset(self::$_cached_courses[$cache_index])) {
            return self::$_cached_courses[$cache_index]; // already done this, so send cached results
        }

        $user_ids = array_map('intval', $user_ids); // convert all elements to ints
        $sql = sprintf("SELECT DISTINCT(e.courseid) AS id FROM {context} c
            INNER JOIN {role_assignments} ra ON (c.id=ra.contextid)
            INNER JOIN {role} r ON (ra.roleid=r.id)
            INNER JOIN {enrol} e ON (r.id=e.roleid)
            INNER JOIN {user_enrolments} ue ON (e.id=ue.enrolid)
            WHERE ue.userid IN (%s) AND c.contextlevel=%d",
            join(', ', $user_ids), CONTEXT_COURSE);
            
        $records = $DB->get_records_sql($sql);
        $courses = array();
        foreach ($records as $record) {
            $courses[] = $record->id;
        }

        self::$_cached_courses[$cache_index] = $courses; // save in cache for future reference
        return $courses;
    }

}