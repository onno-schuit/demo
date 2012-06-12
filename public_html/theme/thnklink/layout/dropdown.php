<script type="text/javascript">

</script>

<?php

//Get the link locations for the theme settings
$toolshed1 = $PAGE->theme->settings->toolshedlink1;
$toolshed2 = $PAGE->theme->settings->toolshedlink2;
$wiki = $PAGE->theme->settings->wikilink;
$connector = $PAGE->theme->settings->connectorlink;
$collaborator = $PAGE->theme->settings->collaboratorlink;
$codex = $PAGE->theme->settings->codexlink;
$expertnetwork = $PAGE->theme->settings->expertnetwork;


//Guests will be redirected to the login page
if (!isloggedin() || isguestuser()) {
    $collaborator = '/login/index.php?urltogo=' . $collaborator;
    $wiki = '/login/index.php?urltogo=' . $wiki;
    $toolshed1 = '/login/index.php?urltogo=' . $toolshed1;
    $toolshed2 = '/login/index.php?urltogo=' . $toolshed2;
    $connector = '/login/index.php?urltogo=' . $connector;
    $collaborator = '/login/index.php?urltogo=' . $collaborator;
}

$home = '/';
$sel_codex = $sel_collaborator = $sel_connector = $sel_toolshed = $sel_wiki = '';

switch($PAGE->bodyid) {
    case "page-mod-connector-index":
        $sel_connector = 'current';
        break;
    case "page-blog":
        $sel_codex = 'current';
        break;
    case "page-course-view-topcoll":
        $sel_collaborator = 'current';
        break;
    case "page-mod-ouwiki-view":
        if ($COURSE->id == 6) {
            $sel_wiki = 'current';
        } else {
            $sel_toolshed = 'current';
        }
        break;
}


//Get the dropdown menu items for the groups you are enrolled in
$courses = enrol_get_my_courses();

function print_group_menu($courses) {
    global $CFG, $gotgroups;
    $gotgroups = FALSE;
    
    $dropdowngroups = '';
    foreach ($courses as $course) {
        $mygroups = groups_get_user_groups($course->id);
        foreach ($mygroups as $mygrouping) {
            foreach ($mygrouping as $mygroup) {
                $groupinfo = groups_get_group($mygroup);
                $gotgroups = TRUE;
                $dropdowngroups .= "<li><a href=" . $CFG->wwwroot . "/course/view.php?id=" .$course->id ."&group=".$groupinfo->id.">" . $groupinfo->name. "</a></li>";
            }
        }

    }
    if ($gotgroups) {
        return $dropdowngroups;
    } else {
        return $gotgroups;
    }
}


?>

<div class="subnav">


<ul class="nav nav-pills">
	<li class='<?php echo $sel_codex; ?>'><a
		href="<?php echo $CFG->wwwroot;?>/blog/index.php?userid=<?php echo $USER->id;?>">CODEX </a></li>
	<li class='<?php echo $sel_collaborator; ?> dropdown'><a href="#" class="dropdown-toggle" data-toggle="dropdown">CREATOR<b class="caret"></b></a>
	    <ul class="dropdown-menu">
	    <?php 
	    if (!isloggedin() || isguestuser()) { 
	    ?>
	    <li><a href="<?php echo $CFG->wwwroot . '/login';?>">LOGIN</a></li>
	    <?php 
	    } else {
	        if ($dropdowngroups = print_group_menu($courses)) {
	            echo $dropdowngroups;
	        }else {
	            echo "<li><a href=" . $CFG->wwwroot . $collaborator . ">DEFAULT COLLABORATOR</a></li>";
	        }
	    }?>
	    </ul>
	</li>

	<li class='<?php echo $sel_connector; ?> dropdown'><a href="#"  class="dropdown-toggle" data-toggle="dropdown">CONNECT<b class="caret"></b></a>
	    <ul class="dropdown-menu">
		    <li><a href="<?php echo $CFG->wwwroot . $connector;?>">CREATIVE SPARKS</a></li>
		    <li><a href="<?php echo $CFG->wwwroot . $expertnetwork;?>">EXPERT NETWORK</a></li>
		    <li><a href="http://www.surveymonkey.com" target="new">SURVEYS</a></li>
	    </ul>
	</li>
	<li class='<?php echo $sel_wiki; ?>' ><a
		href="<?php echo $CFG->wwwroot . $wiki;?>">WIKI</a></li>
	<li class='<?php echo $sel_toolshed; ?>  dropdown' id="last" ><a href="#"   class="dropdown-toggle" data-toggle="dropdown">TOOLS<b class="caret"></b></a>
	    <ul class="dropdown-menu">
		    <li><a href="http://assessment.thnk.org" target="new">Assessment</a></li>
		    <li><a href="<?php echo $CFG->wwwroot . $toolshed1;?>">Creation Tools</a></li>

	    </ul>
	</li>
</ul>
<div id="thnklinksearch">
    <form id="tl_search_form" method="get" action="<?php echo $CFG->wwwroot;?>/blocks/tl_search/search.php">
        <input  type='search'  x-webkit-grammar='builtin:search' placeholder='Search this site' x-webkit-speech='' results='5' autosave='some_unique_value' name="query" value="">
        <input type="submit" value="search">
    </form>
</div>
<div style="clear: both;"></div>


</div>





