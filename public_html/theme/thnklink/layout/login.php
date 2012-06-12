<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hasfooter = FALSE;

$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
if (!is_siteadmin($USER)) {
	$hassidepre = false;
} else {
	$hassidepre = true;
}
//$hassidepre = false;
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
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
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
<script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/jquery-ui.min.js"></script> 
    
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
    if(isloggedin() && !isguestuser()) {
        echo $OUTPUT->login_info();
    }
?>
</div>
</div>
<div id="course-header">
<?php 
include($CFG->dirroot . "/theme/thnklink/layout/dropdown.php");
?>
</div>

<div style="clear:both;"></div>

<div id="thnk-login-page">
     <div id="thnk-region-main">
          <div class="login-region-content">
                 <div id="thnk-login-blocks">
                 <div id="modal">
                 <div class="modal_header"><div class='signin'>Sign In</div></div>
                 <div class="modal_message">
                 <p>Don't have an Thnklink account? <a href="signup.php">Register now!</a></p></div>
                    <?php echo $OUTPUT->blocks_for_region('side-post') ?>
                    <?php echo $OUTPUT->main_content() ?>
                </div>
                <div style="clear:both;"></div>
          </div>
     </div>
</div>

<div style="clear:both;"></div>
<!-- START OF FOOTER -->
    <?php if ($hasfooter) { ?>
    <div id="thnk-footer">
        <?php 
        include("sitemap.php");
        ?>
    </div>
    <?php } ?>

</div>
<div style='clear:both;'> 
    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/bsdropdown.js"></script>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot;?>/theme/thnklink/javascript/loginhide.js"></script>
    <?php include($CFG->dirroot . "/theme/thnklink/js/google_analytics.php"); ?>
</div>
</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>