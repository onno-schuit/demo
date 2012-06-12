<?php
function mycollaborator_get_active_group($courseid) {
	global $USER, $SESSION;

	$cm->groupingid = 0;
	$cm->id = 1;
	$cm->course = $courseid;
	$changegroup = optional_param('group', -1, PARAM_INT);


	if (!$groupmode = groups_get_activity_groupmode($cm)) {
		// NOGROUPS used
		return false;
	}

	if (is_siteadmin($USER)) {
		$groupmode = 'aag';
	}


	if ($groupmode == VISIBLEGROUPS or $groupmode === 'aag') {
		$allowedgroups = groups_get_all_groups($cm->course, 0, $cm->groupingid);
	} else {
		$allowedgroups = groups_get_all_groups($cm->course, $USER->id);
	}


	_group_verify_activegroup($cm->course, $groupmode, $cm->groupingid, $allowedgroups);

	// set new active group if requested


	if ($changegroup != -1) {

		if ($changegroup == 0) {
			// allgroups visible only in VISIBLEGROUPS or when accessallgroups
			if ($groupmode == VISIBLEGROUPS or $groupmode === 'aag') {
				$SESSION->activegroup[$cm->course][$groupmode][$cm->groupingid] = 0;
			}

		} else {
			if ($allowedgroups and array_key_exists($changegroup, $allowedgroups)) {
				$SESSION->activegroup[$cm->course][$groupmode][$cm->groupingid] = $changegroup;

			}
		}
	}

	return $SESSION->activegroup[$cm->course][$groupmode][$cm->groupingid];
}
?>
