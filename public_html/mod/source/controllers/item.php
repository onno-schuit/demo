<?php
include_once("{$CFG->dirroot}/mod/todotwo/controllers/todotwo.php");
include_once("{$CFG->dirroot}/mod/todotwo/models/group_member.php");

class item_controller extends todotwo_controller  {

    var $no_layout = true;

    function index() {
        global $course, $USER, $id;
        // TEST CODE
        /*
        $_SESSION['group_id'] = 1;
        $this->group_id = 1;
         */
        // END TEST CODE
        $this->no_layout = false;
        $items = item::load_for_group($id, $USER->id, $course->id, $this->group_id);
        $this->get_view(array('items' => $items));
    } // function index


    function show() {
    } // function show


    function edit() {
        if (! $item = $this->valid_request($_REQUEST['item_id']) ) return false;
        $this->get_view(array('item' => $item), '_edit_item', $template = false);
    } // function edit


    function delete() {
        if (! $item = $this->valid_request($_REQUEST['item_id']) ) return false;
        return item::delete_all("id = :id", array('id' => $item->id));
    } // function delete


    function has_ownership($item_id) {
        global $USER;
        $item = item::load_by_id($item_id);
        if ($USER->id == $item->user_id) return $item;
        // TEMP SOLUTION: check if USER is in same group as item owner
        if (!$this->group_id) return false;
        if (!group_member::load_by_userid_and_groupid($USER->id, $this->group_id)) return false;
        return $item;
    } // function has_ownership


    function valid_request($item_id) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return false;
        return $this->has_ownership($item_id);
    } // function valid_request


    function create() {
        global $course, $USER, $id;
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return false;
        $item = new item($_REQUEST['item']);
        $item->todotwo_id = $id;
        $item->course_id = $course->id;
        $item->user_id = $USER->id;
        $item->time_created = time();
        $item->save();
        $this->get_view(array('item' => $item), '_single_item', $template = false);
    } // function create


    function save() {
        $data = $_REQUEST['item'];
        if (! $item = $this->valid_request($data['id']) ) return false;
        $item->title = $data['title'];
        $item->completed = $data['completed'];
        $item->note = $data['note'];
        $item->priority = $data['priority'];
        if (!empty($this->group_id)) {
            $item->user_id = $data['user_id'];
            $item->group_id = $this->group_id;
        }
        $item->time_updated = time();
        $item->save();
        $this->rebuild_list();
    } // function save


    function toggle_completed() {
        $item_id = preg_replace("/[^0-9]/", '', $_REQUEST['field_name']);
        if (! $item = $this->valid_request($item_id) ) return false;
        $item->completed = (int) $_REQUEST['completed'];
        $item->save();
        $this->rebuild_list();
    } // function toggle_completed


    function rebuild_list() {
        global $course, $USER, $id;
        $items = item::load_for_group($id, $USER->id, $course->id, $this->group_id);
        $this->get_view(array('items' => $items), '_list', $template = false);
    } // function rebuild_list


} // class item_controller 

?>
