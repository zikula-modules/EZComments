<?php 
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Translations by: �ivind Skau
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
define('_EZCOMMENTS_ONLYREG',   		'Only logged in users are allowed to comment.');	// Translate me!
define('_EZCOMMENTS_GOTOREG',   		'register/log in');		// Translate me!
define('_EZCOMMENTS_ADD',      			'Legg til');
define('_EZCOMMENTS_DEL', 	 			'Slett denne kommentarent');
define('_EZCOMMENTS_COMMENT_ADD', 		'Legg til en kommentar');
define('_EZCOMMENTS_COMMENT_ANSWER', 	'Answer');  // Translate me!
define('_EZCOMMENTS_COMMENT_FROM',  	'by');      // Translate me!  Meaning: the autor of the comment
define('_EZCOMMENTS_COMMENT_ON',    	'on');      // Translate me!  Meaning: the date of the comment   "by foobar on 11-11-2003"
define('_EZCCOMMENTSCREATED', 			'Kommentar lagt til');
define('_EZCCOMMENTSDELETED', 			'Kommentar slettet');
define('_EZCOMMENTS_FAILED',   			'Intern feil');
define('_EZCOMMENTS_NODIRECTACCESS',	'No direct access to this module'); // Translate me!
define('_EZCOMMENTS_RULES',				'Define the rules for your comments here');  // Translate me

define('_EZCOMMENTS_ADMIN',				'EZComments Administration');           // Translate me!
define('_EZCOMMENTS_ADMIN_MAIN',		'Main EZComments Administration');      // Translate me!
define('_EZCOMMENTS_SMARTYPATH',		'Path to your installation of SMARTY'); // Translate me!
define('_EZCOMMENTS_SENDINFOMAIL',		'send mail on new comment');            // Translate me!
define('_EZCOMMENTS_OK', 				'Accept');                              // Translate me!
define('_EZCOMMENTS_LASTCOMMENTS', 		'The last comments');                   // Translate me!
define('_EZCOMMENTS_USERNAME', 			'User');                                // Translate me!
define('_EZCOMMENTS_MODULE', 			'Module');                              // Translate me!
define('_EZCOMMENTS_COMMENT', 			'Kommentar');

// Translate Me
define('_EZCOMMENTS_CLEANUP_NOTHINGTODO', 'No orphaned comments'); 
define('_EZCOMMENTS_CLEANUP_GOBACK',      'Back');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',     'This functionality allows you to delete comments that are in the database for removed modules.');
define('_EZCOMMENTS_CLEANUP_LABEL',       'Select module:');
define('_EZCOMMENTS_CLEANUP_GO',          'Delete all comments for this module');
define('_EZCOMMENTS_CLEANUP',             'Delete orphanded comments');

// Translate Me
define('_EZCOMMENTS_MIGRATE_EXPLAIN',     'Import comments from other modules');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO', 'No migration plugins available');
define('_EZCOMMENTS_MIGRATE_GOBACK',      'Back');
define('_EZCOMMENTS_MIGRATE_LABEL',       'Migrate:');
define('_EZCOMMENTS_MIGRATE_GO',          'Start migration');
define('_EZCOMMENTS_MIGRATE',             'Migrate Comments');

define('_EZCOMMENTS_FAILED1', 			'Feil ved opprettelse av tabell');
define('_EZCOMMENTS_FAILED2', 			'Feil ved opprettelse av tilknytning (hook)');
define('_EZCOMMENTS_FAILED3', 			'Feil ved sletting av tabell');
define('_EZCOMMENTS_FAILED4', 			'Feil ved sletting av tilknytning (hook)');
define('_EZCOMMENTS_FAILED5', 			'Oppdatering av tabell mislyktes');

define('_EZCOMMENTS_MAILSUBJECT',		'A new comment was entered');  // Translate me
define('_EZCOMMENTS_MAILBODY',  		'A new comment was entered');  // Translate me

?>
