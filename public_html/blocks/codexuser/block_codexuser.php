<?php
/**
 * @author Bas Brands
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodle codexuser block
 *
 */


defined('MOODLE_INTERNAL') || die();


class block_codexuser extends block_base {
    /**
     * block initializations
     */
    public function init() {
        $this->title   = get_string('pluginname', 'block_codexuser');
    }

    /**
     * block contents
     *
     * @return object
     */
    public function get_content() {
        global $CFG, $USER, $DB, $OUTPUT, $PAGE, $COURSE, $SESSION;

        if ($this->content !== NULL) {
            return $this->content;
        }


        $userid = optional_param('userid', -1, PARAM_INT);

        if ($userid == '-1') {
            $userid = get_user_preferences('watchbloguser','-1');
        }

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (!isloggedin() or isguestuser()) {
            return '';      // Never useful unless you are logged in as real users
        }

        if ($userid != '-1') {
            $codexuser = $DB->get_record('user',array('id'=>$userid));
        } 

        $this->content = new stdClass;
        $this->content->text = '';
        $error = '';

        if ($codexuser) {
            $this->content->text .= '<div id="linkedinusercontainer"><div id="linkedinuserwrapper">';

            $this->content->text .= '<div id="linkedinuserinfo">';
            $this->content->text .= '<div id="linkedinusername">' .$codexuser->firstname . ' ' .$codexuser->lastname . '</div>';
            $this->content->text .= '<p>';
            $description = $DB->get_field('user', 'description', array('id'=>$codexuser->id));
            if (isset($description)) {
                $this->content->text .= '<span class="linkedinusertitle">' . $description . '</span>';
            }
            if (isset($codexuser->city)) {
                $this->content->text .= '<span class="linkedinuserlocation">' .$codexuser->city . '</span>';
            }
            if (!empty($codexuser->url)) {
                $this->content->text .= '<span class="linkedinuserprofile"><a href="' .$codexuser->url . '" target="new">'.get_string('visit_profile','block_codexuser').'</a></span>';
            }
            if ($codexuser->id == $USER->id) {
                $this->content->text .= '<br><span class="edituser"><a href="'.$CFG->wwwroot . '/user/edit.php?id='.$USER->id.'"><button class="thnkbutton">edit your account</button></a></span>';
            }
            if (is_siteadmin($USER)) {
                $this->content->text .= '<br><span class="edituser"><a href="'.$CFG->wwwroot . '/user/view.php?id='.$codexuser->id.'&course=1"><button class="thnkbutton">view profile</button></a></span>';
            }
            $this->content->text .= '</p>';

            $this->content->text .= '</div>';

            $this->content->text .= '<div id="linkedinuserpic">';
            $this->content->text .= '<img src="'.$CFG->wwwroot.'/user/pix.php?file=/'.$codexuser->id.'/f1.jpg" width="80px" height="80px" title="'.$codexuser->firstname.' '.$codexuser->lastname.'" alt="'.$codexuser->firstname.' '.$codexuser->lastname.'" />';
            $this->content->text .= '</div>';

            $this->content->text .= '<div style="clear:both;"></div></div></div>';
        }

        $this->content->footer = '';
        return $this->content;
    }

    /**
     * allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return false;
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
        return false;
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

}
