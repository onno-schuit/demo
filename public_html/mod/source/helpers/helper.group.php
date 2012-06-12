<?php

class helper_group {
  
  static function get_group_id() {
    global $cm;
    static $group_id;
    
    if (!isset($group_id)) {
        $group_id = groups_get_activity_group($cm);
    }
    
    return (int)$group_id;
  }  
}

?>