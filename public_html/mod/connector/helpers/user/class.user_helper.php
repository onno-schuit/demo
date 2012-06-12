<?php

class user_helper extends helper {

    function get_filtered($filters, $key) {
        return ($filters && isset($filters[$key] ) && $filters[$key] != '') ? $filters[$key] : '';
    } // function get_filtered
} // class user_helper 

?>
