<?php

class item extends model {
    static $table_name = 'todotwo_items';

    public static function load_for_group($todotwo_id, $user_id, $course_id, $group_id = false) {

        $group_id  = ($group_id) ? $group_id : 0;
        return parent::load_all("(group_id = :group_id OR user_id = :user_id) AND todotwo_id = :todotwo_id AND course_id = :course_id ORDER BY time_created DESC",
                                 false,
                                 array('todotwo_id' => $todotwo_id,
                                       'course_id' => $course_id,
                                       'group_id' => $group_id,
                                       'user_id' => $user_id)
                               );
    } // function load_all

} // class message 

?>
