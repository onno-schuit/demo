<?php

class item_helper extends helper {

    function count_items($items) {
        global $USER;
        return count(item::find_all_by_user_id($USER->id, $items));
    } // function count_items


    function get_owner_field($item) {
        global $USER;
        if (!$owner = $this->group->is_member($item->user_id)) return $this->owner_field("NOT FOUND");
        $full_name = ($owner->id == $USER->id) ? "ME" : $owner->full_name();
        return $this->owner_field($full_name);
    } // function get_owner_field


    function owner_field($full_name) {
        return "<div class='todotwo_item_owner' style='float:left;width:90px;'>$full_name</div>";               
    } // function owner_field


    function get_owner_selectbox($item) {
        global $USER;
        $selectbox = "<div class='todotwo_editable_field'>
                           <label for='todotwo_item_user_id'>Owner:</label>
                           <select name='item[user_id]' id='todotwo_item_user_id'>";
        foreach($this->group->get_group_members() as $user) {
            $full_name = ($user->id == $USER->id) ? "ME" : $user->full_name();
            $selected = ($user->id == $item->user_id) ? " selected='selected' " : "";
            $selectbox .= "<option $selected value='{$user->id}'>{$full_name}</option>";
        }
        $selectbox .= "</select></div>";               
        return $selectbox;
    } // function get_owner_selectbox


    function get_priority_selectbox($item) {
        $values = array(-1, 0, 1, 2);
        $box = "<select name='item[priority]' id='item_priority'>";
        foreach($values as $value) {
            $selected = (isset($item->priority) && ($item->priority == $value)) ? " selected='selected' " : "";
            $box .= "<option $selected value='$value'>$value</option>";
        }
        $box .= "</select>";
        return $box;
    } // function get_priority_selectbox
} // class item_helper 

?>
