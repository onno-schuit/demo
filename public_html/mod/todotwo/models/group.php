<?php

class group extends model {
    static $table_name = 'groups';

    var $group_members = false;


    function get_group_members() {
        if ($this->group_members) return $this->group_members;
        if (!$this->id) return false;
        $this->group_members = user::load_all(
            "id IN (SELECT userid FROM {groups_members} WHERE groupid = :group_id)", 
            false,
            array('group_id' => $this->id));
        if (empty($this->group_members)) error("Could not find group members for group_id = {$this->id}");
        return $this->group_members;
    } // function get_group_members} // class group


    function is_member($user_id) {
        if (!$members = $this->get_group_members()) return false;
        foreach($members as $member) {
            if ($member->id == $user_id) return $member;
        }
        return false;
    } // function is_member

} // class group

?>
