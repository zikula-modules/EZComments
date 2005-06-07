<?php 
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Author: Jörg Napp
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
define('_EZCOMMENTS_SENDINFOMAIL',		'Send mail on new comment');
define('_EZCOMMENTS_OK', 				'Accept');
define('_EZCOMMENTS_LASTCOMMENTS', 		'The last comments');
define('_EZCOMMENTS_USERNAME', 			'User');
define('_EZCOMMENTS_MODULE', 			'Module');
define('_EZCOMMENTS_TEMPLATE',          'Default template');

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
define('_EZCOMMENTS_FROM',  			'Location'); 

define('_EZCOMMENTS_SUBJECT',           'Subject');
define('_EZCOMMENTS_EDIT',              'Edit Comment');
define('_EZCOMMENTS_DELETE',            'Delete Comment');
define('_EZCOMMENTS_ITEMSPERPAGE',      'Items per page');

define('_EZCOMMENTS_AUTHOR',            'Author');
define('_EZCOMMENTS_COMMENT',           'Comment');

// navigation bar
define('_EZCOMMENTS_DISPLAY',            'Display');
define('_EZCOMMENTS_FLAT',               'Flat');
define('_EZCOMMENTS_NEWESTFIRST',        'Newest First');
define('_EZCOMMENTS_OLDESTFIRST',        'Oldest First');
define('_EZCOMMENTS_ORDER',              'Order');
define('_EZCOMMENTS_THREADED',           'Threaded');

define('_EZCOMMENTS_ALLOWANONUSERSETINFO', 'Allow unregistered users to set user information');
define('_EZCOMMENTS_ANONNAME',             'Name');
define('_EZCOMMENTS_ANONMAIL',             'E-mail address'); 

define('_EZCOMMENTS_SEARCH',               'Search comments');
define('_EZCOMMENTS_NOCOMMENTSFOUND',      'No comments matched your search');

define('_EZCOMMENTS_TOP',                  'Top');
define('_EZCOMMENTS_BOTTOM',               'Bottom');

// comment moderation
define('_EZCOMMENTS_MODERERATE',           'Enable comment moderation');
define('_EZCOMMENTS_MODLINKCOUNT',         'Number of links in comment before moderation');
define('_EZCOMMENTS_MODLIST',              'Words to trigger moderation');
define('_EZCOMMENTS_BLACKLIST',            'Words to blacklist from comments');
define('_EZCOMMENTS_BLACKLISTNOTE',        'Note: Comments containing words list here will completely ignored by comments module');
define('_EZCOMMENTS_SEPERATELINE',         'Separate multiple words with new lines');
define('_EZCOMMENTS_SENDINFOMAILMOD',      'Send mail comments requiring moderation');
define('_EZCOMMENTS_MODMAILSUBJECT',       'New comment for your site');
define('_EZCOMMENTS_MODMAILBODY',          'A new comment was submitted to your site that requires moderation');
define('_EZCOMMENTS_ALWAYSMODERERATE',     'All comments require moderation');
define('_EZCOMMENTS_HELDFORMODERATION',    'Your comment was held for moderation and will be reviewed shortly');
define('_EZCOMMENTS_COMMENTBLACKLISTED',   'Your comment contains unacceptable content and has been rejected');
define('_EZCOMMENTS_PROXYBLACKLIST',       'Blacklist comments from insecure proxies');

// comment statuses
define('_EZCOMMENTS_APPROVED', 'Approved');
define('_EZCOMMENTS_PENDING', 'Pending');
define('_EZCOMMENTS_REJECTED', 'Rejected');

// modifyconfig fieldsets
define('_EZCOMMENTS_MISCSETTINGS', 'Miscellaneous');
define('_EZCOMMENTS_MODERATIONSETTINGS', 'Moderation');
define('_EZCOMMENTS_NOTIFICATIONSETTINGS', 'Notification');

// mails
define('_EZCOMMENTS_SHOW', 'Show');
define('_EZCOMMENTS_MODERATE2', 'Moderate');
define('_EZCOMMENTS_DELETE', 'Delete');

?>