<?php
/**
 * @author Bas Brands
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodle linkedIn block
 *
 */


defined('MOODLE_INTERNAL') || die();


class block_linkedin extends block_base {
    /**
     * block initializations
     */
    public function init() {
        $this->title   = get_string('pluginname', 'block_linkedin');
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

        require_once( $CFG->dirroot . "/auth/linkedin/linkedin.php");

        $this->content = new stdClass;

        $error = '';
        $authplugin = 'linkedin';

        if (!file_exists("{$CFG->dirroot}/auth/$authplugin/auth.php")) {
            $error = get_string('notinstalled','block_linkedin');
        }
        if (!is_readable("{$CFG->dirroot}/auth/$authplugin/auth.php")) {
            if (empty($error)) {
                $error = get_string('notreadable','block_linkedin');
            }
        }
        if (!is_enabled_auth($authplugin)) {
            if (empty($error)) {
                $error = get_string('notenabled','block_linkedin');
            }
        }
        if (!empty($error)) {
            if (is_siteadmin($USER)) {
                $this->content->text .= get_string('errors','block_linkedin');
                $this->content->text .= $error;
            }
            return $this->content;
        }
        
        
        $urltogo = optional_param('urltogo', '', PARAM_RAW);

        $access = get_config("auth/linkedin", 'linkedin_access');
        $secret = get_config("auth/linkedin", 'linkedin_secret');

        $linkedin = new LinkedIn($access, $secret, $CFG->wwwroot.'/login/index.php');
        $linkedin_response = '';


        // LOGIN WITH LINKEDIN BOX
        if (!isloggedin() or isguestuser()) {
            $linkedin->getRequestToken();
            $_SESSION['requestToken'] = serialize($linkedin->request_token);

            $this->content->text .= '<div id="linkedincontainer"><div id="linkedinwrapper">';

            $this->content->text .= '
			<script type="text/javascript">
			var newwindow;
			function pop(url)
			{
				newwindow=window.open(\''.$CFG->wwwroot.'/blocks/linkedin/popup.php\',\'LinkedIn\',\'height=450,width=600\');
				if (window.focus) {newwindow.focus()}
			}
			</script>
			';

            

            $this->content->text .= '<div class="thnk-login-info">';
            $popuplink = '<a onclick="pop()" href="#">'.get_string('linkedin','block_linkedin').'</a>';
            $moodlelink = '<a href="#">'.get_string('systemname','block_linkedin').'</a>';
            $this->content->text .=  '<span class="linkedinlogin">'. get_string('login_linkedin','block_linkedin',$popuplink).'</span>';
            $this->content->text .= '<br><a onclick="pop()" href="#"><img src="'.
                $CFG->wwwroot.'/blocks/linkedin/linkedin-login.png"></a><br>';
            $this->content->text .= $PAGE->headingmenu;
            $this->content->text .= 'Or login with your Thnklink account';
            $this->content->text .= '</div></div></div>';
             

        } else {
            if (isset($urltogo)) {
                redirect($CFG->wwwroot . $urltogo);
            } elseif (get_user_roles_in_course($USER->id,5)) {
                redirect($CFG->wwwroot . '/course/view.php?id=5');
            } else {
                redirect($CFG->wwwroot . '/blog/index.php?userid=' . $USER->id);
                $this->content->text .= '<div id="linkedincontainer"><div id="linkedinwrapper">';

                $this->content->text .= '<div id="linkedininfo">';
                $this->content->text .= '<div id="linkedinname">' .$USER->firstname . ' ' .$USER->lastname . '</div>';
                $this->content->text .= '<p>';
                $description = $DB->get_field('user', 'description', array('id'=>$USER->id));
                if (isset($description)) {
                    $this->content->text .= '<span class="linkedintitle">' . $description . '</span>';
                }
                if (isset($USER->city)) {
                    $this->content->text .= '<span class="linkedinlocation">' .$USER->city . '</span>';
                }
                $this->content->text .=  '<span class="linkedinlogout"><a href="' . $CFG->wwwroot . '/login/logout.php?sesskey='. sesskey() .'">'. get_string('logout') .'</a></span>';
                $this->content->text .= '</p>';
                if (is_siteadmin($USER)) {
                    //$this->content->text .= get_string('plugindescription','block_linkedin');
                }
                //$this->content->text .=  $OUTPUT->login_info();
                $this->content->text .= '</div>';

                $this->content->text .= '<div id="linkedinpic">';
                $this->content->text .= '<img src="'.$CFG->wwwroot.'/user/pix.php?file=/'.$USER->id.'/f1.jpg" width="80px" height="80px" title="'.$USER->firstname.' '.$USER->lastname.'" alt="'.$USER->firstname.' '.$USER->lastname.'" />';
                $this->content->text .= '</div>';

                $this->content->text .= '<div style="clear:both;"></div></div></div>';
            }

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
