<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
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
    <script type="text/javascript" src="ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>

<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="thnk-page">
<?php if ($hasheading || $hasnavbar) { ?>
    <div id="page-header">
        <div id="page-header-wrapper" class="wrapper clearfix">
            <?php if ($hasheading) { ?>
                <h1 class="headermain inside"><?php echo $PAGE->heading ?></h1>
                <div class="headermenu"><?php
                    echo $OUTPUT->login_info();
                        if (!empty($PAGE->layout_options['langmenu'])) {
                            echo $OUTPUT->lang_menu();
                        }
                    echo $PAGE->headingmenu ?>
                </div>
            <?php } ?>
        </div>
    </div>

<?php if ($hascustommenu) { ?>
<div id="custommenuwrap"><div id="custommenu"><?php echo $custommenu; ?></div></div>
<?php } ?>



<?php } ?>


<!-- END OF HEADER -->
            
<div id="linkedinform">
    <div class="region-content">
         <?php echo $OUTPUT->main_content() ?>
   </div>
</div>

</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>