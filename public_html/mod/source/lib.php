<?php

// FILENAME: lib.php
include_once(dirname(__FILE__).'/../../local/ousearch/searchlib.php');

require_once("class.source.php");
$source_instance = new source();

function mod_source_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload) {
    require_once(dirname(__FILE__) . '/models/file.php');
    list($group_id, $folder_id, $filename) = $args;
    $fs = get_file_storage();
     
    // Prepare file record object
    $fileinfo = (object)array(
        'component' => 'mod_source',     // usually = table name
        'filearea' => 'source',     // usually = table name
        'itemid' => $group_id,               // usually = ID of row in table
        'contextid' => $context->id, // ID of context
        'filepath' => "/$group_id/$folder_id/",           // any path beginning and ending in /
        'filename' => $filename); // any filename

    // Get file
    $file = $fs->get_file(
        $fileinfo->contextid, 
        $fileinfo->component, 
        $fileinfo->filearea, 
        $fileinfo->itemid, 
        $fileinfo->filepath, 
        $fileinfo->filename);
     
    // Read contents
    if ($file) {
        $contents = $file->get_content();
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $file->get_mimetype());
        header('Content-Disposition: attachment; filename='.basename($fileinfo->filename));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file->get_filesize());
        die($contents);
      } else {
        die('File not found in database');
        // file doesn't exist - do something
    }
    die();
    
} 

?>
