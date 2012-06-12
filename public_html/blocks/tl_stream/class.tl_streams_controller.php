<?php

require_once("class.helper_moodle_system.php");

class tl_streams_controller {

    static public $streams = array();
    static protected $_auto_streams_registered = false; 
    static protected $_limit = 20;
    static protected $_dm_processed = false;
    static protected $_display_stream; // personal or system
    static public $sql;
    
    static public function apply_items_filter(&$results) {
        global $CFG;
        foreach ($results as $result) {
            if (preg_match('/\[user=([0-9]+)\](.+)\[\/user\]/', $result->title, $matches)) {
                $user_id = $matches[1];
                $user_name = $matches[2];
                $result->title = str_replace($matches[0], 
                    sprintf('<a href="%s">%s</a>',
                        $CFG->wwwroot . '/user/profile.php?id=' . (int)$user_id,
                        $user_name
                    ),
                    $result->title); 
            }
        }
    }
    
    /**
     * registers availiable streams (from tl_stream_kinds table) and initiates the stream's class;
     * 
     * @return
     */
    static public function auto_register_streams() {
        if (self::$_auto_streams_registered) { return; }
        global $DB;
        # get stream kinds from DB
        $stream_kinds = $DB->get_records_select('tl_stream_kind', '1');
        # register all stream kinds by short_code
        foreach ($stream_kinds as $stream_kind) {
            self::register_stream($stream_kind->short_code);
        }
        self::$_auto_streams_registered = true;
    }
    
    static public function delete_stream_item($id) {
        global $USER, $DB;
        
        self::auto_register_streams();
        // only if user is owner, this action is permitted
        $record = $DB->get_record_select(self::get_cache_table(), 'id=? AND user_id=?', array('id' => $id, 'user_id' => $USER->id));
        if (!$record) {
            return false;
        }
        
        $short_code = $DB->get_field_select('tl_stream_kind', 'short_code', 'id=?', array('id' => $record->stream_kind_id));
        $stream_handler = self::get_stream_handler($short_code);
        $stream_handler->delete($record->id);
        
        return true;
        
    }
    
    /**
     * gathers new items from streams if necessary and stores them in the cache
     * 
     * @return void
     */
    static public function gather($options=array()) {
        foreach (self::$streams as $stream) {
            if ($stream->needs_cache_update()) {
                $stream->gather();
            }
        }
    }
    
    static public function get_cache_table($without_prefix=true) {
        global $DB;
        $table = 'tl_stream_cache';
        if (!$without_prefix) {
            $table = $DB->get_prefix() . $table;
        }
        return $table;
    }
    
    static public function get_display_stream() {
        $result = isset(self::$_display_stream) ? self::$_display_stream : get_user_preferences('tlstream');
        if (!$result) {
            $result = 'system';
        } 
        return $result;
    }
    
    static public function get_items($options=array()) {
        global $DB, $USER;
        self::auto_register_streams();
        self::gather();
        
        # see how many items we need to return at most
        $limit = isset($options['limit']) ? $options['limit'] : self::get_limit();
        self::set_display_stream(isset($options['stream']) && !empty($options['stream']) ? $options['stream'] : self::get_display_stream());
        
        # which streams do we want to see?
        $stream_ids = join(',', self::get_stream_ids());
        
        $user_id = isset($options['user_id']) ? $options['user_id'] : $USER->id;

		$wheres = array(1);
		if (self::get_display_stream() == 'personal') {
			$wheres[] = 'is_personal=1';
		}
		if (isset($options['last_returned_id'])) {
			$wheres[] = 'c.id > ' . $options['last_returned_id'];
		}
		$wheres = join(" AND ", $wheres);
        
        $sort = isset($options['sort']) ? $options['sort'] : 'DESC';
        $limit_from = isset($options['limit_from']) ? (int)$options['limit_from'] : 0;
        
        $sql = sprintf("SELECT c.*,k.short_code FROM `%s` c
            INNER JOIN `%s` k ON (c.stream_kind_id=k.id)
            WHERE stream_kind_id IN (%s) AND user_id=%d
            AND $wheres
            ORDER BY date_and_time DESC
            LIMIT %d,%d",
            self::get_cache_table(false),
            'mdl_tl_stream_kind',
            $stream_ids, 
            $user_id,
            $limit_from,
            $limit);
        $result = $DB->get_records_sql($sql);
        self::$sql = $sql;
        if ($sort == 'ASC') {
            uasort($result, array('self', 'item_compare_asc'));
        }
        if (isset($options['downloaded_ids']) && !empty($options['downloaded_ids'])) {
            self::remove_downloaded_items($result, $options['downloaded_ids']);
        }
        self::apply_items_filter($result);
        return $result;
                
    }
    
    public static function remove_downloaded_items(&$results, $downloaded_ids) {
        foreach ($results as $key=>$result) {
            if (in_array($result->id, $downloaded_ids)) {
                unset($results[$key]);
            }
        }
    }
    
    public static function item_compare_asc($a, $b) {
        return $a->date_and_time < $b->date_and_time ? -1 : ($a->date_and_time == $b->date_and_time ? 0 : 1);
    }
    
    static public function get_limit() {
        return (int)self::$_limit;
    }
    
    static public function get_stream_handler($short_code) {
        return self::$streams[$short_code];
    }
    
    static public function get_stream_ids() {
        /* @todo: find out which stream kinds */
        # all stream kinds for now.
        
        global $DB;
        $ids = array();
        $records = $DB->get_records_select('tl_stream_kind', '1');
        foreach ($records as $record) {
            if (get_config('block_tl_stream', 'user_' . $record->short_code)) {
                $ids[] = (int)$record->id;
            }
        }
        return $ids;
        
    }
    
    /**
     * Handles submission of direct stream messages from the stream block's interface
     * 
     * @return void
     */
    public static function handle_stream_messages() {
        if (self::$_dm_processed) { return; }

        # for non ajax requests
        if (!isset($_REQUEST['_nonce']) || !helper_moodle_system::verifyNonce($_REQUEST['_nonce'], 'update_stream')) {
            // Nonce does not compute. Exit here..
            return;
        }
        global $DB, $USER;

        if (isset($_POST['tl_stream_new_message']) && !empty($_POST['tl_stream_new_message'])) {
            $record = (object)array(
                'user_id' => $USER->id,
                'message' => $_POST['tl_stream_new_message'],
                'timecreated' => time()
            );
            $DB->insert_record('tl_stream_dm', $record);
        }        
        self::$_dm_processed = true;
    }

    static public function register_stream($short_code) {
        global $USER;
        $classname = 'tl_stream_kind_' . $short_code;
        $filename = "class.$classname.php";
        
        if (!@include_once($filename)) debugging("Class $filename not found while stream is defined in the database");
        if (class_exists($classname)) {
            $stream = new $classname($USER->id);
            self::$streams[$short_code] = $stream;
            return $stream; 
        }
        return false;
    }
    
    public static function set_display_stream($name) {
        self::$_display_stream = $name;
    }
    
    public static function store_user_preference($name) {
        set_user_preference('tlstream', $name);         
    }
}

?>