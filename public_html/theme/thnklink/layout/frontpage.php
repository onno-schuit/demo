<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hasfooter = FALSE;
$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hasthnkadmin = $PAGE->blocks->region_has_content('thnk-admin', $OUTPUT);
$hasthnkstream = $PAGE->blocks->region_has_content('thnk-stream', $OUTPUT);

if (!is_siteadmin($USER)) {
	$hassidepre = false;
} else {
	$hassidepre = true;
}


//$hassidepre = false;
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

if ($hassidepost || $hasthnkstream) {
	$streamclass = 'thnk-stream';
} else {
	$streamclass = 'thnk-nostream';
}

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));
$hascustommenu = false;
$hasheading = false;
$hasnavbar = false;

$bodyclasses = array();
if ($hassidepre && !$hassidepost) {
    $bodyclasses[] = 'side-pre-only';
} else if ($hassidepost && !$hassidepre) {
    $bodyclasses[] = 'side-post-only';
} else if (!$hassidepost && !$hassidepre) {
    $bodyclasses[] = 'content-only';
}
if (is_siteadmin($USER)) {
    $bodyclasses[] = 'showsettings';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>


    
        <script>
            window.onload = function () {
                var paper = Raphael("canvas", 640, 480),
                    btn = document.getElementById("run"),
                    cd = document.getElementById("code");

                (btn.onclick = function () {
                    paper.clear();
                    paper.rect(0, 0, 640, 480, 10).attr({fill: "#fff", stroke: "none"});
                    try {
                        (new Function("paper", "window", "document", cd.value)).call(paper, paper);
                    } catch (e) {
                        alert(e.message || e);
                    }
                })();
            };
        </script>
    
</head>

<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="pagewrapper">
<?php
if (is_siteadmin($USER)) {
include($CFG->dirroot . "/theme/thnklink/layout/thnk-menu.php"); 
}?>

<div id="thnk-page">
<div id="course-top">
<a href="<?php echo $CFG->wwwroot; ?>">
<img src="<?php echo $CFG->wwwroot; ?>/theme/thnklink/pix/thnk-logo.png">
</a>
<div id="course-login-info">
<?php
    echo $OUTPUT->login_info();
?>
</div>
</div>
<div id="course-header">
<?php 
include($CFG->dirroot . "/theme/thnklink/layout/dropdown.php");
?>
</div>

<div style="clear:both;"></div>
<!-- END OF HEADER -->

    <?php if ($hasthnkadmin) { ?>
    <div id="thnk-admin">
         <?php echo $OUTPUT->blocks_for_region('thnk-admin') ?>
    </div>
    <?php } ?>


<div id="thnk-course-content" class="<?php echo $streamclass; ?>">


          <div id="thnk-region-main">
               <div class="region-content">

                    
                    <div id="canvas"></div>
                    <div id="hiddenstuff">
                    <div><textarea id="code">paper.circle(320, 240, 60).animate({fill: "#005fa2", stroke: "#000", "stroke-width": 80, "stroke-opacity": 0.5}, 2000);</textarea></div>
                    </div>
                    <div><button id="run" type="button">Run</button></div>
       
                    <div id="thnk-home-modules" class="<?php if (is_siteadmin($USER->id)) { echo "showadmin";}?>">
                    <?php echo $OUTPUT->main_content() ?>
                    </div>
               </div>
         </div>
</div>       

<?php if ($hassidepost) { ?>
<div id="course-post-wrapper">
    <div class="thnk-navbutton"> <?php echo $PAGE->button; ?>
    </div>

<div id="thnk-region-post" class="block-region" style="DISPLAY: block">
    <div class="region-content">
         <?php echo $OUTPUT->blocks_for_region('side-post') ?>
    </div>

</div> 
<?php } 
?>

<?php if ($hasthnkstream) { ?>
<div id="thnk-region-thnk-stream" class="block-region" style="DISPLAY: block">
    <div class="region-content">
         <?php echo $OUTPUT->blocks_for_region('thnk-stream') ?>
    </div>
</div> 
<?php } ?>

<div style="clear:both;"></div>
<?php if ($hassidepost) { ?>
</div>
<?php } 
?>


<div style="clear:both;"></div>
<!-- START OF FOOTER -->
    <?php if ($hasfooter) { ?>
    <div id="thnk-footer">
        <?php 
        include("sitemap.php");
        ?>
    </div>
    <?php } ?>

</div><!--  end of page -->

    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/jquery.min.js"></script> 
    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/raphael.js"></script> 
    
    <?php if (is_siteadmin($USER)) { ?>    
    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/stream-folding.js"></script>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/jquery.mb.flipText.js"></script>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/jquery-ui.min.js"></script> 
    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/thnk-menu.js"></script>
    <?php } ?>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/bsdropdown.js"></script>


<div style='clear:both;'>

</div>

</div>

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
<?php include($CFG->dirroot . "/theme/thnklink/js/google_analytics.php"); ?>
