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

	<div class="thnk-front-story" >
	    <div id="thnk-front-story-leftwrap">
		    <div id="thnk-front-image" >
		        <img src="<?php echo $CFG->wwwroot ?>/theme/thnklink/pix/frontpix.jpg">
		    </div>
		    <div id="thnk-front-title">
		    <h3>Got any Lorem for my ipsum?</h3>
		    </div>
		    
		    <div class="thnk-column"><p>    
		    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet
            libero ut leo faucibus ultrices. Maecenas ullamcorper, leo vitae sodales
            sagittis, libero enim aliquam mi, eget rutrum lacus felis at leo. Aenean
            tempus dolor vel massa congue at sagittis risus porta. Etiam id mi at 
            nisi luctus 
            </p>
            </div>
            <div class="thnk-column">
            <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet
            libero ut leo faucibus ultrices. Maecenas ullamcorper, leo vitae sodales
            sagittis, libero enim aliquam mi, eget rutrum lacus felis at leo. Aenean
            tempus dolor vel massa congue at sagittis risus porta. Etiam id mi at
            nisi luctus 
            </p>
            </div>
       </div>
       <div class="thnk-column-right"> 
       <p>    
       Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet
       libero ut leo faucibus ultrices. Maecenas ullamcorper, leo vitae sodales
       sagittis, libero enim aliquam mi, eget rutrum lacus felis at leo. Aenean
       tempus dolor vel massa congue at sagittis risus porta. Etiam id mi at
       nisi luctus
       
       Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet
       libero ut leo faucibus ultrices. Maecenas ullamcorper, leo vitae sodales
       sagittis, libero enim aliquam mi, eget rutrum lacus felis at leo. Aenean
       tempus dolor vel massa congue at sagittis risus porta. Etiam id mi at
       nisi luctus  
       </p>
       
       <p>    
       Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet
       libero ut leo faucibus ultrices. Maecenas ullamcorper, leo vitae sodales
       sagittis, libero enim aliquam mi, eget rutrum lacus felis at leo. Aenean
       tempus dolor vel massa congue at sagittis risus porta. Etiam id mi at
       nisi luctus
       
       </p>
       </div>

       <div style="clear:both;"></div>
	   </div>
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
        <div id="thnk-front-link"><a href="<?php echo $CFG->wwwroot . $PAGE->theme->settings->toolshedlink;?>">Open Toolshed</a></div>
	    </div>
	</div>
	<div class="thnk-front-greybottom">
	</div>
	
		
</div>
