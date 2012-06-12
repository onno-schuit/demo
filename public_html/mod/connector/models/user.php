<?php

class connector_user extends model {


    static $table_name = 'user';
    static $limit = 100;
    static $default_restriction = " u.username NOT LIKE 'guest' AND u.suspended = 0 AND u.deleted = 0 ";

    public static function filter_by_username($user, $username) {
        global $CFG;
        $sql = sprintf(" u.username LIKE '%s' ", $username);
        return static::load_ids($sql);               
    } // function filter_by_username


    public static function filter_by_lastname($user, $lastname) {
        global $CFG;
        $sql = sprintf(" u.lastname LIKE '%s' ", $lastname);
        return static::load_ids($sql);               
    } // function filter_by_lastname 


    public static function filter_by_role($user, $role) {
        global $CFG;
        $no_experts = "";
        // Dirty hack to exclude experts from 'ordinary' users set...
        if ($role != 'expert') {
            $no_experts = " AND u.id NOT IN (
                SELECT ra.userid 
                FROM {$CFG->prefix}role_assignments AS ra, {$CFG->prefix}role AS r 
                WHERE r.shortname LIKE 'expert'
                AND ra.roleid = r.id) ";
        }
        $sql = sprintf(" u.id IN (
            SELECT ra.userid 
            FROM {$CFG->prefix}role_assignments AS ra, {$CFG->prefix}role AS r 
            WHERE r.shortname LIKE '%s' 
            AND ra.roleid = r.id) 
            $no_experts", $role);
        return static::load_ids($sql);               
    } // function filter_by_role 


    public static function filter_by_same_group($user) {
        global $CFG, $cm, $course;
        if (! ($usergroups = groups_get_all_groups($course->id, $user->id, $cm->groupingid)) ) return array();
        if (!(count($usergroups))) return array();
        $sql = sprintf(" u.id IN (SELECT userid 
            FROM {$CFG->prefix}groups_members 
            WHERE groupid IN (%s) )", join(',', model::collect('id', $usergroups) ));
        return static::load_ids($sql);
    } // function filter_by_same_group


    public static function filter_by_tags($user, $tags) {
        global $CFG;

        $sql = sprintf(" u.id IN (
            SELECT DISTINCT itemid 
            FROM {$CFG->prefix}tag_instance AS ti,  {$CFG->prefix}tag AS t
            WHERE ti.itemtype LIKE 'user' AND ti.tagid = t.id
            AND t.rawname IN (%s) )", static::quotify($tags));
        return static::load_ids($sql);
    } // function filter_by_tags


    public static function load_all($user, $where = false) {
        global $DB, $CFG;
        $where = ($where) ? static::$default_restriction . " AND $where " : static::$default_restriction;
        $where = "WHERE $where";

        // putting DISTINCT into the first subquery ruins the order by m.order_column results...
        $sql = "SELECT DISTINCT d.* FROM (SELECT u.* FROM {$CFG->prefix}user AS u LEFT JOIN  
                (SELECT timecreated AS order_column, useridfrom FROM {$CFG->prefix}message UNION SELECT timeread AS order_column, useridfrom FROM {$CFG->prefix}message_read) AS m
                ON m.useridfrom = u.id 
                $where
                AND id <> {$user->id} 
                ORDER BY m.order_column DESC, u.currentlogin DESC ) AS d  LIMIT " . static::$limit;

        if (! $recordset = $DB->get_recordset_sql($sql) ) return array();
        $users = array();
        foreach ($recordset as $record) {
            $users[] = new static($record);
        }
        $recordset->close();
        return $users;
    } // function load_all


    public static function filter_by($user, $filters = false) {
        if (! $filters ) return static::load_all($user);
        if (! count($filtered_users = static::apply_all_filters($user, $filters)) ) return array();
        $where = sprintf(" username NOT LIKE 'guest' AND id IN (%s)", join(',', $filtered_users)); 
        return static::load_all($user, $where);
    } // function filter_by





    // searching using boolean AND
    public static function apply_all_filters($user, $filters) {
        $filtered_users = array();
        foreach($filters as $filter => $value) {
            if ( (! $value) || (trim($value) == '') ) continue;
            $method = "filter_by_$filter";
            if (! count($temp = static::$method($user, $value)) ) return array();
            if (! count($filtered_users) ) {
                $filtered_users = $temp;
                continue;
            }
            $filtered_users = array_intersect($filtered_users, $temp);
            if (! count($filtered_users) ) return array();
        }               
        return $filtered_users;
    } // function apply_all_filters


    public static function load_ids($sql_where) {
        global $DB, $CFG;
        //exit($sql_where);
        $user_ids = array();
        if (! $recordset = $DB->get_recordset_sql("SELECT u.id 
            FROM {$CFG->prefix}" . static::table_name() . "  AS u 
            WHERE $sql_where AND " . static::$default_restriction ) ) return array();
        //exit(print_object($recordset));
        foreach ($recordset as $record) {
            $user_ids[] = $record->id;
        }
        $recordset->close();
        return $user_ids;               
    } // function load_ids


    // Returns array of ids from users with a new message for $user
    public static function userids_with_new_messages_for($user) {
        global $CFG, $DB;
        $sql = "SELECT COUNT(useridfrom) AS new_messages, useridfrom 
                FROM {$CFG->prefix}message
                WHERE useridto = {$user->id}
                GROUP BY useridfrom";
        if (! $recordset = $DB->get_recordset_sql($sql) ) return array();
        $userids = array();
        foreach ($recordset as $record) {
            $userids[$record->useridfrom] = $record->new_messages;
        }
        $recordset->close();
        return $userids;
    } // function userids_with_new_messages_for 


    function details_url() {
        global $CFG;
        if ($this->is_expert()) return "{$CFG->wwwroot}/user/profile.php?id={$this->id}";
        return "{$CFG->wwwroot}/blog/index.php?userid={$this->id}";
    } // function get_details_url


    function is_expert() {
        return (!empty($this->expert));
    } // function is_expert

} // class user 

?>
