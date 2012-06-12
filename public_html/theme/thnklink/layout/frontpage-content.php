<?php
defined('MOODLE_INTERNAL') || die();
?>

<style type="text/css" media="screen">
/* <![CDATA[ */
@import
	url(<?php echo $CFG->wwwroot ?>/course/format/topcoll/topics_collapsed.css)
	;
/* ]]> */
</style>
<!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot ?>/course/format/topcoll/ie-7-hacks.css" media="screen" />
<![endif]-->

<div id="thnk-front-apps">
    <div class="thnk-front-app">
	    <div class="thnk-front-apptitle" id="sectionhead-1">
		    <a id="thnk-link" href="#" onclick="togglefrontexacttopic('thnk-front-block-codex',1); return false;">Codex</a>
	    </div>
	    <div id="thnk-front-block-codex" class="thnk-front-block" style="display: none;">
        <?php echo $PAGE->theme->settings->codextext;?>
        <div id="thnk-front-link"><a href="<?php echo $CFG->wwwroot . $PAGE->theme->settings->codexlink;?>">Open Codex</a></div>
	   </div>
   </div>
   <div class="thnk-front-greybottom">
   </div>
	
	<div class="thnk-front-app">
	    <div class="thnk-front-apptitle" id="sectionhead-1">
		    <a id="thnk-link" href="#" onclick="togglefrontexacttopic('thnk-front-block-collaborator',1); return false;">Collaborator</a>	
	    </div>
	    <div id="thnk-front-block-collaborator" class="thnk-front-block" style="display: none;">
        <?php echo $PAGE->theme->settings->collaboratortext;?>
        <div id="thnk-front-link"><a href="<?php echo $CFG->wwwroot . $PAGE->theme->settings->collaboratorlink;?>">Open Collaborator</a></div>
	    </div>
	</div>
	<div class="thnk-front-greybottom">
	</div>
	
	<div class="thnk-front-app">
	    <div class="thnk-front-apptitle" id="sectionhead-1">
		<a id="thnk-link" href="#"
			onclick="togglefrontexacttopic('thnk-front-block-connector',1); return false;">Connector</a>	
	    </div>
	    <div id="thnk-front-block-connector" class="thnk-front-block" style="display: none;">
        <?php echo $PAGE->theme->settings->connectortext;?>
        <div id="thnk-front-link"><a href="<?php echo $CFG->wwwroot . $PAGE->theme->settings->connectorlink;?>">Open Connector</a></div>
	    </div>
	</div>
	<div class="thnk-front-greybottom">
	</div>
    
    <div class="thnk-front-app">
	    <div class="thnk-front-apptitle" id="sectionhead-1">
		    <a id="thnk-link" href="#"
			    onclick="togglefrontexacttopic('thnk-front-block-wiki',1); return false;">Wiki</a>	
	    </div>
	    <div id="thnk-front-block-wiki" class="thnk-front-block" style="display: none;">
        <?php echo $PAGE->theme->settings->wikitext;?>
        <div id="thnk-front-link"><a href="<?php echo $CFG->wwwroot . $PAGE->theme->settings->wikilink;?>">Open Wiki</a></div>
	    </div>
	</div>
	<div class="thnk-front-greybottom">
	</div>
    
    <div class="thnk-front-app">
	    <div class="thnk-front-apptitle" id="sectionhead-1">
		    <a id="thnk-link" href="#"
			onclick="togglefrontexacttopic('thnk-front-block-toolshed',1); return false;">Toolshed</a>	
	    </div>
	    <div id="thnk-front-block-toolshed" class="thnk-front-block" style="display: none;">
        <?php echo $PAGE->theme->settings->toolshedtext;?>
        <div id="thnk-front-link"><a href="<?php echo $CFG->wwwroot . $PAGE->theme->settings->toolshedlink1;?>">Open Toolshed</a></div>
        <div id="thnk-front-link"><a href="<?php echo $CFG->wwwroot . $PAGE->theme->settings->toolshedlink2;?>">Open Toolshed</a></div>
        
	    </div>
	</div>
	<div class="thnk-front-greybottom">
	</div>
</div>
