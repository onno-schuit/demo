<?php

class item extends model {
    static $table_name = 'todotwo_items';

    public static function load_for_group($todotwo_id, $course_id, $group_id) {

        return parent::load_all("group_id = :group_id AND todotwo_id = :todotwo_id AND course_id = :course_id ORDER BY priority DESC, time_created DESC",
                                 false,
                                 array('todotwo_id' => $todotwo_id,
                                       'course_id' => $course_id,
                                       'group_id' => $group_id)
                               );
    } // function load_all
    
    public function save() {
        $result = parent::save();
        $this->update_search_document();
        return $result;
    }
    
    public function update_search_document() {
        global $cm, $USER, $COURSE, $CFG;

        require_once($CFG->dirroot . '/blocks/mycollaborator/lib.php');
        $currentgroup = mycollaborator_get_active_group($COURSE->id);
        $group_id = isset($this->group_id) ? $this->group_id : $currentgroup; 
        $post = (object)array(
            'id' => $this->id,
            'title' => $this->title,
            'description' => isset($this->note) ? $this->note : '',
            'userid' => $USER->id,
            'groupid' => $group_id
        );
        oustodotwo::update($post, $cm);
    }


    function get_owner() {
                
    } // function get_owner

} // class message 

?>
