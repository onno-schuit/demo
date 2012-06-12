<?php

function xmldb_block_tl_stream_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2012012502) {

        // Define field meta to be added to tl_stream_cache
        $table = new xmldb_table('tl_stream_cache');
        $field = new xmldb_field('meta', XMLDB_TYPE_TEXT, 'small', null, null, null, null, 'contents');

        // Conditionally launch add field meta
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // tl_stream savepoint reached
        upgrade_block_savepoint(true, 2012012502, 'tl_stream');
    }

    if ($oldversion < 2012012503) {
        $record = $DB->get_record_select('tl_stream_kind', "short_code='dm'");
        if (!$record) {
            $direct_message_record = (object)array('short_code' => 'dm', 'description' => "Direct messages");
            $DB->insert_record('tl_stream_kind', $direct_message_record);
        } 
        upgrade_block_savepoint(true, 2012012503, 'tl_stream');
    }
    
    if ($oldversion < 2012012504) {

        // Define field is_personal to be added to tl_stream_cache
        $table = new xmldb_table('tl_stream_cache');
        $field = new xmldb_field('is_personal', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'meta');

        // Conditionally launch add field is_personal
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define index is_personal (not unique) to be added to tl_stream_cache
        $index = new xmldb_index('is_personal', XMLDB_INDEX_NOTUNIQUE, array('is_personal'));
        // Conditionally launch add index is_personal
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // tl_stream savepoint reached
        upgrade_block_savepoint(true, 2012012504, 'tl_stream');
    }
    if ($oldversion < 2012012506) {
        $DB->execute('TRUNCATE {tl_stream_gather}');
        $DB->execute('TRUNCATE {tl_stream_cache}');
        upgrade_block_savepoint(true, 2012012506, 'tl_stream');
    }
    
}


?>