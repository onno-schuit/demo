<?php

class helper_moodle_system {
    
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
    
    /**
     * Returns array of users enrolled to the courses with id IN $course_ids
     * 
     * @param array $course_ids
     * @return array 
     */
    static public function get_users_from_courses($course_ids) {
        global $DB;
        if (!is_array($course_ids)) {
            $course_ids = array($course_ids);
        }

        if (empty($course_ids)) { return array(); }

        // some caching stuff, because the SELECT query might be slow
        sort($course_ids);
        $cache_index = join(',', $course_ids); // $_cached_users[id1,id2,id3,..] 
        if (isset(self::$_cached_users[$cache_index])) {
            return self::$_cached_users[$cache_index]; // already done this, so send cached results
        }

        $course_ids = array_map('intval', $course_ids); // convert all elements to ints
        $sql = sprintf("SELECT DISTINCT(ue.userid) AS id FROM {context} c
            INNER JOIN {role_assignments} ra ON (c.id=ra.contextid)
            INNER JOIN {role} r ON (ra.roleid=r.id)
            INNER JOIN {enrol} e ON (r.id=e.roleid)
            INNER JOIN {user_enrolments} ue ON (e.id=ue.enrolid)
            WHERE e.courseid IN (%s) AND c.contextlevel=%d",
             join(', ', $course_ids), CONTEXT_COURSE);
            
        $records = $DB->get_records_sql($sql);
        $users = array();
        foreach ($records as $record) {
            $users[] = $record->id;
        }
        self::$_cached_users[$cache_index] = $users; // save in cache for future reference
        return $users;
    }
    
    /**
     * Builds url with new query variables ($qsAdd = 'x=1&y=2')
     * 
     * @param string $qsAdd
     * @param string $existing_url
     * @return
     */
    static public function url($qsAdd = false, $existing_url='') {
        // from php.net
        $existing_url = $existing_url ? $existing_url : $_SERVER['REQUEST_URI'];
        list($path, $qs) = strstr($existing_url, '?') ? explode('?', $existing_url) : array('', '');

        $var_array = array();
        $varAdd_array = array();
        $url = $path;
       
        if($qsAdd)
        {
            $varAdd = explode('&', $qsAdd);
            foreach($varAdd as $varOne)
            {
                $name_value = explode('=', $varOne);
               
                $varAdd_array[$name_value[0]] = $name_value[1];
            }
        }
    
        if($qs)
        {
            $var = explode('&', $qs);
            foreach($var as $varOne)
            {
                $name_value = explode('=', $varOne);
               
                //remove duplicated vars
                if($qsAdd)
                {
                    if(!array_key_exists($name_value[0], $varAdd_array))
                    {
                        $var_array[$name_value[0]] = $name_value[1];
                    }
                }
                else
                {
                    $var_array[$name_value[0]] = $name_value[1];
                }
            }
        }
           
        //make url with querystring   
        $delimiter = "?";
       
        foreach($var_array as $key => $value)
        {
            $url .= $delimiter.$key."=".$value;
            $delimiter = "&";
        }
       
        foreach($varAdd_array as $key => $value)
        {
            $url .= $delimiter.$key."=".$value;
            $delimiter = "&";
        }
       
        return $url;
    } 

    protected static function _getNonceLifeTime() {
        return 3600; // one hour in seconds
    }
    
    protected static function _getNonceSecret() {
        return "Som3ThinG SeCretHere!";
    }
    
    /**
     * Returns a 'number used once', like WordPress does. This is for verifying if the client is known 
     * and may post data to our PHP-script. A nonce does not verify if the user has the proper capabilities 
     * to read, post, or whatever.  
     * Different from WordPress is that we also send the timestamp on which the nonce was created. The 
     * timestamp is part of the md5 hash, so can't be altered by the client without messing up the md5 
     * hash. Advantage of this method is that we don't have a persistent time window in which the nonce 
     * exists, with the risk of the nonce being a second away from expiring. Our nonce will always live 
     * for 3600 seconds (_getNonceLifeTime()) 
     * 
     * @param string $action
     * @return void
     */
    public static function getNonce($action='default') {
       $time = time();
       // last 6 characters of md5 + timestamp
       $nonce = substr(md5($action . self::_getNonceSecret() . $time), -6) . $time;
       return $nonce;
    }
    
    public static function verifyNonce($nonce, $action='default') {
        # nonce to short? exit
        if (strlen($nonce) < strlen(time()) + 6) { return false; }

        # split up the nonce
        $nonce_value = substr($nonce, 0, 6); // first 6 chars is part of the md5 hash
        $time = substr($nonce, 6); // the rest is timestamp

        # nonce expired? exit..
        if (time() - $time > self::_getNonceLifeTime()) { return false; }
        
        # re-calculate the nonce calue
        $calculated_nonce = substr(md5($action . self::_getNonceSecret() . $time), -6);
        # compare
        return ($nonce_value == $calculated_nonce);
    }

}

?>