<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Lang strings.
 * @package local
 * @subpackage ousearch
 * @copyright 2011 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['ousearch'] = 'Zoeken (OUSearch)';
$string['searchfor']='Zoeken: {$a}';
$string['untitled']='(Geen titel)';
$string['searchresultsfor'] = 'Zoekresultaten voor <strong>{$a}</strong>';
$string['noresults'] = 'Geen resultaten gevonden. Gebruik andere woorden of verwijder woorden uit de zoekopdracht.';
$string['nomoreresults'] = 'Geen resultaten meer gevonden.';
$string['previousresults']='Terug naar resultaten {$a}';
$string['findmoreresults']='Meer resultaten';
$string['searchtime']='Zoeken duurde: {$a}s';
$string['resultsfail']='Geen resultaten gevonden voor <strong>{$a}</strong>. Probeer andere zoektermen.';
$string['remote'] = 'Remote search IP toestaan';
$string['configremote']='List of IP addresses that are permitted to use the remote search facility.
This should be a list of zero or more numeric IP addresses, comma-separated. Be careful! Requests
from these IP addresses can search (and see summary text) as if they were any user. The default,
blank, prevents this access.';
$string['displayversion'] = 'OU search versie: <strong>{$a}</strong>';
$string['nowordsinquery'] = 'Geef zoektermen op.';
$string['reindex'] = 'Documenten voor module {$a->module} herindexeren voor cursus {$a->courseid}';

$string['fastinserterror'] = 'Invoegen zoekgegevens mislukt (high performance insert)';
$string['remotewrong'] = 'Remote search access is not configured (or not correctly configured).';
$string['remotenoaccess'] = 'This IP address does not have access to remote search';
$string['pluginname'] = $string['ousearch'];
$string['restofwebsite'] = 'Zoeken binnen deze website';
$string['resultscount'] = '{$a} resultaten gevonden';
$string['restrictmodule_none'] = 'Alle content';
$string['restrictmodule_option_prefix'] = 'Alleen';
$string['restrictmodule_submitbutton'] = 'Filter';

