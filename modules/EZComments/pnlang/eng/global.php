<?php 
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Author: Jörg Napp, http://postnuke.lottasophie.de
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

define('_EZCOMMENTS', 		  			'Comments');
define('_EZCOMMENTS_NOAUTH',   			'No access to comments.');
define('_EZCOMMENTS_ONLYREG',   		'Only logged in users are allowed to comment.');
define('_EZCOMMENTS_GOTOREG',   		'register/log in');
define('_EZCOMMENTS_ADD', 	  			'Add');
define('_EZCOMMENTS_DEL',      			'Delete this comment');
define('_EZCOMMENTS_COMMENT_ADD', 		'Add a new Comment');
define('_EZCOMMENTS_COMMENT_ANSWER', 	'Answer');
define('_EZCOMMENTS_COMMENT_FROM',  	'by');
define('_EZCOMMENTS_COMMENT_ON',    	'on');
define('_EZCCOMMENTSCREATED',			'Comment added');
define('_EZCCOMMENTSDELETED', 			'Comment deleted');
define('_EZCOMMENTS_FAILED',   			'Internal Error');
define('_EZCOMMENTS_NODIRECTACCESS',	'No direct access to this module');
define('_EZCOMMENTS_RULES',	            'Define the rules for your comments here');

define('_EZCOMMENTS_ADMIN',				'EZComments Administration');
define('_EZCOMMENTS_ADMIN_MAIN',		'Main EZComments Administration');
define('_EZCOMMENTS_SENDINFOMAIL',		'send mail on new comment');
define('_EZCOMMENTS_OK', 				'Accept');
define('_EZCOMMENTS_LASTCOMMENTS', 		'The last comments');
define('_EZCOMMENTS_USERNAME', 			'User');
define('_EZCOMMENTS_MODULE', 			'Module');
define('_EZCOMMENTS_COMMENT', 			'Comment');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO', 'No orphaned comments');
define('_EZCOMMENTS_CLEANUP_GOBACK',      'Back');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',     'This functionality allows you to delete comments that are in the database for removed modules.');
define('_EZCOMMENTS_CLEANUP_LABEL',       'Select module:');
define('_EZCOMMENTS_CLEANUP_GO',          'Delete all comments for this module');
define('_EZCOMMENTS_CLEANUP',             'Delete orphanded comments');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',     'Import comments from other modules');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO', 'No migration plugins available');
define('_EZCOMMENTS_MIGRATE_GOBACK',      'Back');
define('_EZCOMMENTS_MIGRATE_LABEL',       'Migrate:');
define('_EZCOMMENTS_MIGRATE_GO',          'Start migration');
define('_EZCOMMENTS_MIGRATE',             'Migrate Comments');

define('_EZCOMMENTS_FAILED1', 			'Error creating table');
define('_EZCOMMENTS_FAILED2', 			'Error creating hook');
define('_EZCOMMENTS_FAILED3', 			'Error deleting table');
define('_EZCOMMENTS_FAILED4', 			'Error deleting hook');
define('_EZCOMMENTS_FAILED5', 			'Table update failed');

define('_EZCOMMENTS_MAILSUBJECT',		'A new comment was entered'); 
define('_EZCOMMENTS_MAILBODY',  		'A new comment was entered'); 

// Steffen 01/2005
define('_EZCOMMENTS_POSTED',  			'posted'); 
define('_EZCOMMENTS_REG_SINCE',  		'registered'); 
define('_EZCOMMENTS_STATUS',  			'Status'); 
define('_EZCOMMENTS_OFFLINE',  			'Offline'); 
define('_EZCOMMENTS_ONLINE',  			'Online'); 
define('_EZCOMMENTS_PROFILE',  			'Profile'); 
define('_EZCOMMENTS_SEND_PM',  			'send PM'); 

?>
