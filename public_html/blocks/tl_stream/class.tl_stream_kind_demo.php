<?php

require_once("class.tl_stream_kind_base.php");

    
class tl_stream_kind_demo extends tl_stream_kind_base {
    
    # generates three random text messages in the stream every 24 hours
    
    protected $_short_code = 'demo';
    protected $_max_cache_age = 86400; // 24 hours in seconds
    
    public function gather() {
        global $DB;

        return;
        $text = explode(' ', "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas pretium dolor eu enim vehicula mattis. Cras arcu libero, semper in adipiscing a, ultricies ullamcorper arcu. Curabitur et mattis justo. Etiam dignissim sodales tellus, non gravida diam vulputate eu. Integer molestie condimentum nulla. Nulla sagittis");
        
        # dummy: add three random items
        for($i=0; $i<3; $i++) {
            $n_words = rand(10,20);
            $content = array();
            while($n_words-- > 0) {
                $content[] = $text[rand(0, count($text)-1)];
            }
            $content = join(' ', $content);
            $item = array(
                'user_id' => $this->get_user_id(),
                'stream_kind_id' => $this->_get_stream_kind_id(),
                'src_uid' => 'id:' . rand(100,200),
                'title' => 'Demo ' . rand(100,200),
                'contents' => $content,
                'meta' => '',
                'date_and_time' => (time() - rand(5,100)) // between (now - 5 seconds) and (now - 100 seconds) 
            );
            $this->_save_cache_record($item);
        }
    }
    
    public function get_read_more_link(&$item) {
        return '';
    }
    
}



?>