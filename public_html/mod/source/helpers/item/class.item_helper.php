<?php

class item_helper extends helper {

    var $group_members = false;

    function get_owner($item, $edit = false) {
        global $USER;
        if (! $this->group_id) return "";
        if (! $this->group_members) $this->group_members = user::load_all(
            "id IN (SELECT userid FROM {groups_members} WHERE groupid = :group_id)", 
            false,
            array('group_id' => $this->group_id));
        if (empty($this->group_members)) error("Could not find a group for group_id = {$item->group_id}");
        if (! $user = user::find_by_id($item->user_id, $this->group_members)) error("Could not find user for user_id = {$item->user_id}");

        $selectbox = ($user->id == $USER->id) ? "ME" : $user->full_name();
        if ($edit) {
            $selectbox = "<select style='width:100px;' name='item[user_id]' id='todotwo_item_user_id'>";
            foreach($this->group_members as $user) {
                $full_name = ($user->id == $USER->id) ? "ME" : $user->full_name();
                $selected = ($user->id == $item->user_id) ? " selected='selected' " : "";
                $selectbox .= "<option $selected value='{$user->id}'>{$full_name}</option>";
            }
            $selectbox .= "</select>";
        }
        return "<div class='todotwo_item_owner' style='float:left;width:120px;'>$selectbox</div>";
    } // function get_owner 


    function get_priority_selectbox($item) {
        $values = array(-1, 0, 1, 2);
        $box = "<select name='item[priority]'>";
        foreach($values as $value) {
            $selected = ($item->priority == $value) ? " selected='selected' " : "";
            $box .= "<option $selected value='$value'>$value</option>";
        }
        $box .= "</select>";
        return $box;
    } // function get_priority_selectbox
} // class item_helper 

?>
