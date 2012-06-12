<?php

function xmldb_block_tl_stream_install() {
    global $CFG, $DB;
    
    # insert known stream kinds; can be configured later to add more stream kinds
    $register_stream_kinds = explode(',', "mdl_message:Received Moodle messages,mdl_sent_message:Sent Moodle messages,calendar:Calendar events,forum:Forum messages,dm:Direct messages");
    foreach ($register_stream_kinds as $stream_kind) {
        list($key, $description) = explode(":", $stream_kind);
        $record = (object)array(
            'short_code' => $key,
            'description' => $description
        );
        $DB->insert_record('tl_stream_kind', $record);
        unset($record);
    }
}


?>