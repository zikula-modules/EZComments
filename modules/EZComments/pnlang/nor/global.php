<?php 
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Translations by: Øivind Skau
// ----------------------------------------------------------------------
// LICENSE
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

define('_EZCOMMENTS',         			'Kommentarer');
define('_EZCOMMENTS_NOAUTH',   			'Ingen adgang til kommentarer.');
define('_EZCOMMENTS_ONLYREG',   		'Kun innloggede brukere har adgang til å kommentere.');
define('_EZCOMMENTS_GOTOREG',   		'registrér deg/logg inn');
define('_EZCOMMENTS_ADD',      			'Legg til');
define('_EZCOMMENTS_DEL', 	 			'Slett denne kommentaren');
define('_EZCOMMENTS_COMMENT_ADD', 		'Legg til en kommentar');
define('_EZCOMMENTS_COMMENT_ANSWER', 	'Svar'); 
define('_EZCOMMENTS_COMMENT_FROM',  	'av');
define('_EZCOMMENTS_COMMENT_ON',    	'den');
define('_EZCCOMMENTSCREATED', 			'Kommentar lagt til');
define('_EZCCOMMENTSDELETED', 			'Kommentar slettet');
define('_EZCOMMENTS_FAILED',   			'Intern feil');
define('_EZCOMMENTS_NODIRECTACCESS',	'Ingen direkte adgang til denne modulen');
define('_EZCOMMENTS_RULES',				'Definér reglene for dine kommentarer her');

define('_EZCOMMENTS_ADMIN',				'EZComments administrasjon');
define('_EZCOMMENTS_ADMIN_MAIN',		'EZComments hoved-administrasjon');
define('_EZCOMMENTS_SMARTYPATH',		'Filsti til din SMARTY-installasjon');
define('_EZCOMMENTS_SENDINFOMAIL',		'send e-post ved ny kommentar');
define('_EZCOMMENTS_OK', 				'Godta');
define('_EZCOMMENTS_LASTCOMMENTS', 		'De seneste kommentarene');
define('_EZCOMMENTS_USERNAME', 			'Bruker');
define('_EZCOMMENTS_MODULE', 			'Modul');
define('_EZCOMMENTS_COMMENT', 			'Kommentar');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO', 'Ingen kommentarer tilknyttet fjernede moduler'); 
define('_EZCOMMENTS_CLEANUP_GOBACK',      'Tilbake');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',     'Denne funksjonen lar deg slette kommentarer tilknyttet fjernede moduler.');
define('_EZCOMMENTS_CLEANUP_LABEL',       'Velg modul:');
define('_EZCOMMENTS_CLEANUP_GO',          'Slett alle kommentarer tilknyttet denne modulen');
define('_EZCOMMENTS_CLEANUP',             'Slett kommentarer tilknyttet fjernede moduler');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',     'Importér kommentarer fra andre moduler');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO', 'Ingen flyttbare plugins tilgjengelige');
define('_EZCOMMENTS_MIGRATE_GOBACK',      'Tilbake');
define('_EZCOMMENTS_MIGRATE_LABEL',       'Flytt:');
define('_EZCOMMENTS_MIGRATE_GO',          'Begynn flytting');
define('_EZCOMMENTS_MIGRATE',             'Flytt kommentarer');

define('_EZCOMMENTS_FAILED1', 			'Feil ved opprettelse av tabell');
define('_EZCOMMENTS_FAILED2', 			'Feil ved opprettelse av tilknytning (hook)');
define('_EZCOMMENTS_FAILED3', 			'Feil ved sletting av tabell');
define('_EZCOMMENTS_FAILED4', 			'Feil ved sletting av tilknytning (hook)');
define('_EZCOMMENTS_FAILED5', 			'Oppdatering av tabell mislyktes');

define('_EZCOMMENTS_MAILSUBJECT',		'En ny kommentar ble lagt inn');
define('_EZCOMMENTS_MAILBODY',  		'En ny kommentar ble lagt inn');

?>
