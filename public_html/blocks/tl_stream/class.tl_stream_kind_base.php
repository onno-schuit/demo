<?php

abstract class tl_stream_kind_base {

    protected $_short_code;
    protected $_stream_kind_id;
    protected $_user_id;
    protected $_limit = 10;
    protected $_streams_controller;
    protected $_max_cache_age = 300; // in seconds
    
    public function __construct($user_id) {
        $this->_user_id = (int)$user_id;
        $this->_retrieve_stream_kind_id();
    }
    
    /**
     * Looks up the src_uid of cache item with id $id and removes all occurences of this stream item from the cache (all users)
     * 
     * @param int $id
     * @return void
     */
    public function delete($id) {
        global $DB;
        
        $src_uid = $DB->get_field_select(self::get_cache_table(), 'src_uid', 'id=?', array('id' => $id));
        $DB->delete_records_select(self::get_cache_table(), 'src_uid=? AND stream_kind_id=?', 
            array('src_uid' => $src_uid, 'stream_kind_id' => $this->_get_stream_kind_id()));
    }
    
    public function display_title($title) {
        return $title;
    }
    
    /**
     * gathers new items from 'external' streams and stores them in the cache table
     * 
     * @return void
     */
    public function gather() {
         $this->update_cache_timestamp();
    }

    /**
     * returns the age of the cache in seconds
     * 
     * @return void
     */
    public function get_cache_age() {
        return time() - $this->get_cache_timestamp();
    }
    
    public function get_cache_table($without_prefix=true) {
        return tl_streams_controller::get_cache_table($without_prefix);
    }
    
    /**
     * returns the timestamp of the last added record of this stream kind in the cache table
     * 
     * @return
     */
    public function get_cache_timestamp() {
        global $DB;
        $sql = sprintf("SELECT last_update FROM %s 
            WHERE stream_kind_id=%d 
            AND user_id=%d 
            ORDER BY last_update DESC LIMIT 1",
            $this->get_gather_table(false), 
            $this->_get_stream_kind_id(),
            $this->_user_id
        );
        $last_update = (int)$DB->get_field_sql($sql);
        return $last_update; 
    }
    
    function get_gather_table($without_prefix=true) {
        global $DB;
        $table = 'tl_stream_gather';
        if (!$without_prefix) {
            $table = $DB->get_prefix() . $table;
        }
        return $table;
    }
    
    /**
     * returns items from the cache table for this stream kind
     * 
     * @param mixed $options
     * @return
     */
    public function get_items($options = array()) {
        global $DB;
        
        # see how many items we need to return at most
        $limit = isset($options['limit']) ? $options['limit'] : $this->get_limit();
        
        $result = $DB->get_records_select($this->get_cache_table(),
             "stream_kind_id=" . $this->_get_stream_kind_id() . " AND user_id=" . $this->_user_id,
             null,
             "date_and_time DESC"
         );
         return $result;
    }
    
    public function get_limit() {
        return (int)$this->_limit;
    }
    
    public function get_read_more_link(&$item) {
        return "";   
    }

    public function get_user_id() {
        return $this->_user_id;
    }
    
    public function has_delete() {
        return false;
    }
    
    
    public function has_read_more() {
        return true;
    }
    
    /**
     * returns true if cache hasn't been updated for more than $this->_max_cache_age seconds
     * 
     * @return bool
     */
    function needs_cache_update() {
       return $this->get_cache_age() > $this->_max_cache_age; 
    }
    
    /**
     * Registers the last update time for the stream of the current user
     * 
     * @return void
     */
    public function update_cache_timestamp() {
        global $DB;
        # fetch last record
        $record = $DB->get_record_select($this->get_gather_table(), 
            'user_id=? AND stream_kind_id=?', 
            array('user_id' => $this->_user_id, 'stream_kind_id' => $this->_get_stream_kind_id())
        );
        # modify last_update field with current date/time
        $record->last_update = time();
        if (isset($record->id)) {
            # update if cache record exists
            $DB->update_record($this->get_gather_table(), $record);
        } else {
            # or insert record otherwise
            $record->user_id = $this->_user_id;
            $record->stream_kind_id = $this->_get_stream_kind_id();
            $DB->insert_record($this->get_gather_table(), $record);
        }
    }
    
    /**
     * alias for _retrieve_stream_kind_id();
     * 
     * @return int
     */
    protected function _get_stream_kind_id() {
        return $this->_retrieve_stream_kind_id();
    }
    
    /**
     * returns the id for this stream's short_code
     * 
     * @return int
     */
    protected function _retrieve_stream_kind_id() {
        global $DB;
        if (!isset($this->_stream_kind_id)) {
            // cache for later use
            $this->_stream_kind_id = $DB->get_field_select('tl_stream_kind', 'id', "short_code='" . $this->_short_code . "'");
        }
        return $this->_stream_kind_id;
    }
    
    /**
     * Writes record to the cache table; flags: P=Personal (just one for now)
     * 
     * @param array $record
     * @param string $flags 
     * @return void
     */
    protected function _save_cache_record($record, $flags='') {
        global $DB;
        $cache_record = (object)$record;
        if (stristr($flags, 'P')) {
            $cache_record->is_personal = 1;
        }
        // prevent duplicates in user's stream
        $exists = $DB->get_record_select($this->get_cache_table(), 
            "stream_kind_id=? AND src_uid=? AND user_id=?", 
            array('stream_kind_id' => $cache_record->stream_kind_id, 'src_uid' => $cache_record->src_uid, 'user_id' => $cache_record->user_id)
        );
        if (!$exists) {
            $DB->insert_record($this->get_cache_table(), $cache_record);
        }
        unset($cache_record);
    }
    
    public function timeago($datefrom,$dateto=-1) {
    	// Defaults and assume if 0 is passed in that
    	// its an error rather than the epoch

    	if($datefrom<=0) { return "A long time ago"; }
    	if($dateto==-1) { $dateto = time(); }

    	// Calculate the difference in seconds betweeen
    	// the two timestamps

    	$difference = $dateto - $datefrom;

    	// If difference is less than 60 seconds,
    	// seconds is a good interval of choice

    	if($difference < 60)
    	{
    		$interval = "s";
    	}

    	// If difference is between 60 seconds and
    	// 60 minutes, minutes is a good interval
    	elseif($difference >= 60 && $difference<60*60)
    	{
    		$interval = "n";
    	}

    	// If difference is between 1 hour and 24 hours
    	// hours is a good interval
    	elseif($difference >= 60*60 && $difference<60*60*24)
    	{
    		$interval = "h";
    	}

    	// If difference is between 1 day and 7 days
    	// days is a good interval
    	elseif($difference >= 60*60*24 && $difference<60*60*24*7)
    	{
    		$interval = "d";
    	}

    	// If difference is between 1 week and 30 days
    	// weeks is a good interval
    	elseif($difference >= 60*60*24*7 && $difference <
    	60*60*24*30)
    	{
    		$interval = "ww";
    	}

    	// If difference is between 30 days and 365 days
    	// months is a good interval, again, the same thing
    	// applies, if the 29th February happens to exist
    	// between your 2 dates, the function will return
    	// the 'incorrect' value for a day
    	elseif($difference >= 60*60*24*30 && $difference <
    	60*60*24*365)
    	{
    		$interval = "m";
    	}

    	// If difference is greater than or equal to 365
    	// days, return year. This will be incorrect if
    	// for example, you call the function on the 28th April
    	// 2008 passing in 29th April 2007. It will return
    	// 1 year ago when in actual fact (yawn!) not quite
    	// a year has gone by
    	elseif($difference >= 60*60*24*365)
    	{
    		$interval = "y";
    	}

    	// Based on the interval, determine the
    	// number of units between the two dates
    	// From this point on, you would be hard
    	// pushed telling the difference between
    	// this function and DateDiff. If the $datediff
    	// returned is 1, be sure to return the singular
    	// of the unit, e.g. 'day' rather 'days'

    	switch($interval)
    	{
    		case "m":
    			$months_difference = floor($difference / 60 / 60 / 24 /
    			29);
    			while (mktime(date("H", $datefrom), date("i", $datefrom),
    			date("s", $datefrom), date("n", $datefrom)+($months_difference),
    			date("j", $dateto), date("Y", $datefrom)) < $dateto)
    			{
    				$months_difference++;
    			}
    			$datediff = $months_difference;

    			// We need this in here because it is possible
    			// to have an 'm' interval and a months
    			// difference of 12 because we are using 29 days
    			// in a month

    			if($datediff==12)
    			{
    				$datediff--;
    			}

    			$res = ($datediff==1) ? "$datediff month ago" : "$datediff months ago";
    			break;

    		case "y":
    			$datediff = floor($difference / 60 / 60 / 24 / 365);
    			$res = ($datediff==1) ? "$datediff year ago" : "$datediff years ago";
    			break;

    		case "d":
    			$datediff = floor($difference / 60 / 60 / 24);
    			$res = ($datediff==1) ? "$datediff day ago" : "$datediff days ago";
    			break;

    		case "ww":
    			$datediff = floor($difference / 60 / 60 / 24 / 7);
    			$res = ($datediff==1) ? "$datediff week ago" : "$datediff weeks ago";
    			break;

    		case "h":
    			$datediff = floor($difference / 60 / 60);
    			$res = ($datediff==1) ? "$datediff hour ago" : "$datediff hours ago";
    			break;

    		case "n":
    			$datediff = floor($difference / 60);
    			$res = ($datediff==1) ? "$datediff minute ago" :"$datediff minutes ago";
    			break;

    		case "s":
    			$datediff = $difference;
    			$res = ($datediff==1) ? "$datediff second ago" :"$datediff seconds ago";
    			break;
    	}
    	return $res;
    }


}


?>
