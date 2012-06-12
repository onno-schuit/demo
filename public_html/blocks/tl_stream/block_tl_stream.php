<?php

defined('MOODLE_INTERNAL') || die();

require_once("class.tl_streams_controller.php");

/**
 * 
 * @copyright  2012 Solin
 * @author     William van Veldhoven <william[at]solin.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_tl_stream extends block_base {
	/**
	 * block initializations
	 */
	public function init() {
		$this->title   = get_string('blockname', 'block_tl_stream');
	}
    
	public function get_content() {
		global $CFG, $USER, $DB, $OUTPUT, $PAGE, $COURSE, $SESSION;
        
        tl_streams_controller::handle_stream_messages();

		if ($this->content !== NULL) {
			return $this->content;
		}
        $this->content = new StdClass;

        // assign template vars
        $stream_items = tl_streams_controller::get_items(
            array('stream' => isset($_REQUEST['tlstream']) ? $_REQUEST['tlstream'] : '' ) 
        );
        // get template with output buffering
        ob_start();
        include('tpl_stream.php');
        $text = ob_get_clean();

        // assign content of the template to the tl_stream block
        $this->content->text = $text;

        return $this->content;
    }
    

	/**
	 * allow the block to have a configuration page
	 *
	 * @return boolean
	 */
	public function has_config() {
		return true;
	}
    
	/**
	 * allow more than one instance of the block on a page
	 *
	 * @return boolean
	 */
	public function instance_allow_multiple() {
		//allow more than one instance on a page
		return false;
	}

	/**
	 * allow instances to have their own configuration
	 *
	 * @return boolean
	 */
	function instance_allow_config() {
		//allow instances to have their own configuration
		return true;
	}

	/**
	 * instance specialisations (must have instance allow config true)
	 *
	 */
	public function specialization() {
	}

	/**
	 * displays instance configuration form
	 *
	 * @return boolean
	 */
	function instance_config_print() {
        return "HERE";
		return false;

	}

	/**
	 * locations where block can be displayed
	 *
	 * @return array
	 */
	public function applicable_formats() {
		return array('all'=>true);
	}

	/**
	 * post install configurations
	 *
	 */
	public function after_install() {
	}

	/**
	 * post delete configurations
	 *
	 */
	public function before_delete() {
	}

    // Now comes the fun part!
    // You have done all you NEEDED to do; but there are things that you may do optionally, too!
    // Read the lines below to discover these additional features for Moodle blocks.

    //  1.  If you don't want your block to show a header (for example, you want something like the site
    //      summary in the front page), uncomment the following line:

    //function hide_header() {return true;}

    //  2.  If you want your block to be used ONLY in specific course formats, you can do that, too!
    //      To do this, you will modify the next function to return a value that includes those course
    //      formats in which you want the block to be available. The format is very simple, as you will see.
    //      Don't forget to uncomment the function declaration! :)

    //function applicable_formats() {
        // Default case: the block can be used in all course types EXCEPT the SITE.
        // THERE IS NO TYPO HERE, if you don't know what | does, believe us! :)
        //return COURSE_FORMAT_WEEKS | COURSE_FORMAT_TOPICS | COURSE_FORMAT_SOCIAL | COURSE_FORMAT_SITE;

        // Sample case: We want our block to be available ONLY in weeks format:
        //return COURSE_FORMAT_WEEKS;
    //}

    //  3.  If for some reason your block needs a specific amount of width to be readable, you can
    //      REQUEST that the course format grant you that width. Keep in mind that the course format will
    //      decide to what extent to honor your request, if at all, so there is NO guarantee that you
    //      will get the width you asked for. You might get less, or you might get even more!
    //      However, it's pretty safe to assume that "logical" values will be honored.
    //      To achieve this effect, uncomment the next function:
    function preferred_width() {
        // Default case: the block wants to be 180 pixels wide
        return 300;
    }

    //  4.  It is possible that your block's behavior will be configurable, to some extent. This configuration
    //      will be available to the Moodle administrators only, from the Administration screen. An existing
    //      block that does this is online_users. You can refer to that for a real world example. But for now,
    //      if you desire configuration functionality, follow these simple steps:

    //      a. Uncomment the following function:
    //function has_config() {return true;}

    //      b. You need to display your block's configuration screen, somehow. The preferred way to do this is
    //      create an .html file (most probably config.html) in your block's directory, and then uncomment the
    //      following function:
    //function print_config() {
        //global $CFG, $THEME, $USER;
        //print_simple_box_start('center', '', $THEME->cellheading);
        //include($CFG->dirroot.'/blocks/'.$this->name().'/config.html');
        //print_simple_box_end();
        //return true;
    //}
    //      Of course, there is no restriction to HOW you will display your configuration interface.
    //      The above is just a simple example.
    //      Note the use of $this->name(), which automatically takes the value with which you replaced NEWBLOCK.

    //      NOTES on the configuration screen:
    // 
    //      This information needs to be updated changed for Moodle 1.8 to because of
    //      the new forms library
    //
    //      This will NEED to include a <form method="post" action="block.php">.
    //      Inside this form, you NEED to include an
    //      <input name="block" type="hidden" value="<?php echo intval($_REQUEST['block']); ? >" />
    //      and of course a <input type="submit" value="<?php print_string("savechanges") ? >">
    //      Other than that, you are free to do anything you wish.

    //      c. Finally, write a function that handles the submitted configuration data.
    //      This function will take ONE argument, which will be an object containing all form fields that
    //      were submitted. Use something like the example below to iterate among them and save their values
    //      someplace where you will be able to read them when get_content() is called.
    //function handle_config($config) {
    //    foreach ($config as $name => $value) {
    //        set_config($name, $value);
    //    }
    //    return true;
    //}

    // That's all! Copy the directory of your block (you didn't forget to rename it from NEWBLOCK, did you?)
    // into your /moodle/blocks/ directory and visit your site's administration screen to see the results! :)
}

?>
