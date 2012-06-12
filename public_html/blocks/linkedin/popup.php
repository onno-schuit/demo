<?php
require('../../config.php');

$access = get_config("auth/linkedin", 'linkedin_access');
$secret = get_config("auth/linkedin", 'linkedin_secret');

require_once( $CFG->dirroot . "/auth/linkedin/linkedin.php");
$linkedin = new LinkedIn($access, $secret, $CFG->wwwroot.'/login/index.php');
$linkedin_response = '';

if (!isloggedin() or isguestuser()) {
	$linkedin->getRequestToken();
	$_SESSION['requestToken'] = serialize($linkedin->request_token);
	$url = $linkedin->generateAuthorizeUrl();
}

echo '
<div id="aulink" style="display:none;">'.$url.'</div>
<div id="tester"></div>
<script type="text/javascript">
link=document.getElementById(\'aulink\').innerHTML;
self.location.href=link;
</script>
';