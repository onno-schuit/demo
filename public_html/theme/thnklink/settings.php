<?php

/**
 * Settings for the thnklink theme
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
	
    // gakey
	$name = 'theme_thnklink/gakey';
	$title = get_string('gakey','theme_thnklink');
	$description = get_string('gakeydesc', 'theme_thnklink');
	$setting = new admin_setting_configtext($name, $title, $description, '');
	$settings->add($setting);	
	
	// codex Link
	$name = 'theme_thnklink/codexlink';
	$title = get_string('codexlink','theme_thnklink');
	$description = get_string('codexlinkdesc', 'theme_thnklink');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);
	
	// collaborator Link
	$name = 'theme_thnklink/collaboratorlink';
	$title = get_string('collaboratorlink','theme_thnklink');
	$description = get_string('collaboratorlinkdesc', 'theme_thnklink');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// Connector Link
	$name = 'theme_thnklink/connectorlink';
	$title = get_string('connectorlink','theme_thnklink');
	$description = get_string('connectorlinkdesc', 'theme_thnklink');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);
	
	// Wiki Link
	$name = 'theme_thnklink/wikilink';
	$title = get_string('wikilink','theme_thnklink');
	$description = get_string('wikilinkdesc', 'theme_thnklink');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);
	
	// Toolshed link 1
	$name = 'theme_thnklink/toolshedlink1';
	$title = get_string('toolshedlink1','theme_thnklink');
	$description = get_string('toolshedlink1desc', 'theme_thnklink');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);
	
	// Toolshed link 2
	$name = 'theme_thnklink/toolshedlink2';
	$title = get_string('toolshedlink2','theme_thnklink');
	$description = get_string('toolshedlink2desc', 'theme_thnklink');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);
	
	// Expert network
	$name = 'theme_thnklink/expertnetwork';
	$title = get_string('expertnetwork','theme_thnklink');
	$description = get_string('expertnetworkdesc', 'theme_thnklink');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);
	

}