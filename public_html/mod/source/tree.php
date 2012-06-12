<?php

require_once(dirname(__FILE__) . '/../../config.php');
$tree = source_build_recursive(0);
include("t_tree.html");
die();

function source_build_recursive($parent_id, $is_first=false) {
    global $DB;
    $html = '';
    $folders = $DB->get_records_select('source_path', 'parent_id=? AND group_id=?', 
        array($parent_id, $_SESSION['group_id']), 
        "path");
    if ($folders && !$is_first) {
        $html = "<ul>";
    }
    foreach ($folders as $folder) {
        $html .= sprintf('<li><span class="folder" id="%d">%s</span>', $folder->id, $folder->path);
        $html .= source_build_recursive($folder->id); // recursive
        $html .= "</li>";
    }
    if ($folders && !$is_first) {
        $html .= "</ul>";
    }
    return $html;
}
 

?>