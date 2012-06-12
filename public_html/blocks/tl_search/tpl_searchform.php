<form id="tl_search_form" method="get" action="<?php echo $CFG->wwwroot; ?>/blocks/tl_search/search.php">
    <input type="text" size="20" name="query" value="<?php echo isset($_REQUEST['query']) ? htmlentities($_REQUEST['query']) : ''; ?>"  />
    <input type="submit" value="<?php print_string('search', 'block_tl_search'); ?>"  />
</form>