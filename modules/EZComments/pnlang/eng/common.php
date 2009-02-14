<?php
/**
 * $Id$
 * 
 * * EZComments *
 * 
 * Attach comments to any module calling hooks
 * 
 * 
 * * License *
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 *
 * @author Joerg Napp <jnapp@users.sourceforge.net>
 * @author Mark West <markwest at zikula dot org>
 * @author Jean-Michel Vedrine
 * @author Florian Schieﬂl <florian.schiessl at ifs-net.de>
 * @author Frank Schummertz
 * @version 1.6
 * @link http://code.zikula.org/ezcomments/ Support and documentation
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package Zikula_3rdParty_Modules
 * @subpackage EZComments
 */

// new for 2.0 (.8only)

// Search plugin
define('_EZCOMMENTS_SEARCHLENGTHHINT', 		'The comments can only be searched for words that are longer than %minlen% and less than maxlen% characters!');

// ContactList integration
define('_EZCOMMENTS_USER_IGNORES_YOU',		'The user set as content owner ignores you. You cannot post any comment here!');

// admin interface
define('_EZCOMMENTS_TOOWNERORADMIN',		'The notification email will be sent to the owner of the content. If there is no owner known, the notification mail will be sent to the site administrator.');
define('_EZCOMMENTS_OWNERUSERNAME',			'Content owner');
define('_EZCOMMENTS_UNKNOWNOWNER',			'unknown');

// myprofile plugin
define('_EZCOMMENTS_TABTITLE', 				'User\'s pinboard');
define('_EZCOMMENTS_PINBOARDFOR',			'Pinboard for member');
define('_EZCOMMENTS_HOOKREGFAILED',			'Registering EZComments hook for MyProfile module failed');
define('_EZCOMMENTS_PINBOARDDEACT',			'The user has deactivated the pinboard for his account');
define('_EZCOMMENTS_PINBOARDENTRYS',		'Pinboard entries');
define('_EZCOMMENTS_REGISTERTOVIEW',		'Pinboard entries are only visible for registered or logged in users.');
// my account panel
define('_EZCOMMENTS_MANAGEMYCOMMENTS', 		'Manage my comments');

define('_EZCOMMENTS_ILLEGALSTATUS', 		'wrong status');
define('_EZCOMMENTS_ANONNAMEMISSING', 		'Name for anonymous user is missing');
define('_EZCOMMENTS_ANONMAILMISSING', 		'email address of anonymous user is missing or invalid');
define('_EZCOMMENTS_ANONWEBSITEINVALID', 	'website of anonymous user is invalid');
define('_EZCOMMENTS_SENDMEBACK', 			'Send me back to the commented content after finishing');
define('_EZCOMMENTS_MISSINGVALUE', 			'missing value');
// end of new defines






define('_EZCOMMENTS',                       'Comments');

// admin interface
define('_EZCOMMENTS_ONLYREG',               'Only logged in users are allowed to comment.');
define('_EZCOMMENTS_GOTOREG',               'register/log in');
define('_EZCOMMENTS_ADD',                   'Submit Comment');
define('_EZCOMMENTS_DEL',                   'Delete this comment');
define('_EZCOMMENTS_COMMENT_ADD',           'Add a new Comment');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Answer');
define('_EZCOMMENTS_CREATED',               'Comment added');
define('_EZCOMMENTS_DELETED',               'Comment deleted');
define('_EZCOMMENTS_FAILED',                'Internal Error');
define('_EZCOMMENTS_RULES',                 'Define the rules for your comments here');
define('_EZCOMMENTS_ADMIN',                 'EZComments Administration');
define('_EZCOMMENTS_ADMIN_MAIN',            'View comments');
define('_EZCOMMENTS_SENDINFOMAIL',          'Send mail on new comment');
define('_EZCOMMENTS_OK',                    'Accept');
define('_EZCOMMENTS_LASTCOMMENTS',          'The last %c% comments');
define('_EZCOMMENTS_USERNAME',              'User');
define('_EZCOMMENTS_MODULE',                'Module');
define('_EZCOMMENTS_TEMPLATE',              'Default template');
define('_EZCOMMENTS_ALLCOMMENTS',           'All %s% comments');
define('_EZCOMMENTS_EDIT',                  'Edit Comment');
define('_EZCOMMENTS_ITEMSPERPAGE',          'Comments per page (admin view)');
define('_EZCOMMENTS_ALLOWANONUSERSETINFO',  'Allow unregistered users to set user information');

// user errors/status messages
define('_EZCOMMENTS_EMPTYCOMMENT',          'Error! Sorry! The comment contains no text');

//cleanup of orphaned comments
define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'No orphaned comments');
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Back');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'This functionality allows you to delete comments that are in the database for removed modules.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Select module:');
define('_EZCOMMENTS_CLEANUP_GO',            'Delete all comments for this module');
define('_EZCOMMENTS_CLEANUP',               'Delete orphanded comments');

//comment migration
define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Import comments from other modules');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'No migration plugins available');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Back');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Migrate:');
define('_EZCOMMENTS_MIGRATE_GO',            'Start migration');
define('_EZCOMMENTS_MIGRATE',               'Migrate Comments');

//errors/status meesages for init script
define('_EZCOMMENTS_FAILED1',               'Error creating table');
define('_EZCOMMENTS_FAILED2',               'Error creating hook');
define('_EZCOMMENTS_FAILED3',               'Error deleting table');
define('_EZCOMMENTS_FAILED4',               'Error deleting hook');
define('_EZCOMMENTS_FAILED5',               'Table update failed');

// e-mail messages
define('_EZCOMMENTS_MAILSUBJECT',           'A new comment was entered'); 
define('_EZCOMMENTS_MAILBODY',              'A new comment was entered'); 
define('_EZCOMMENTS_SHOW',                  'Show');
define('_EZCOMMENTS_MODERATE2',             'Moderate');
define('_EZCOMMENTS_DELETE',                'Delete');

//useful phrases for the user templates
define('_EZCOMMENTS_POSTED',                'posted'); 
define('_EZCOMMENTS_REG_SINCE',             'registered'); 
define('_EZCOMMENTS_NOTREG',                'not registered'); 
define('_EZCOMMENTS_STATUS',                'Status'); 
define('_EZCOMMENTS_OFFLINE',  			    'Offline'); 
define('_EZCOMMENTS_ONLINE',  			    'Online'); 
define('_EZCOMMENTS_PROFILE',  			    'Profile'); 
define('_EZCOMMENTS_SEND_PM',  			    'send PM'); 
define('_EZCOMMENTS_FROM',  			    'Location'); 
define('_EZCOMMENTS_SUBJECT',               'Subject');
define('_EZCOMMENTS_TOP',                   'Top');
define('_EZCOMMENTS_BOTTOM',                'Bottom');
define('_EZCOMMENTS_COMMENTSOFAR',          'Comments so far');
define('_EZCOMMENTS_WROTEON',               'wrote on');
define('_EZCOMMENTS_AT',                    'at');
define('_EZCOMMENTS_LEAVEACOMMENT',         'Leave a Comment');
define('_EZCOMMENTS_COMMENT_FROM',          'by');
define('_EZCOMMENTS_COMMENT_ON',            'on');

// comment form
define('_EZCOMMENTS_ANONNAME',              'Name');
define('_EZCOMMENTS_ANONMAIL',              'E-mail address (will not be published)'); 
define('_EZCOMMENTS_ANONWEBSITE',           'Website'); 
define('_EZCOMMENTS_AUTHOR',                'Author');
define('_EZCOMMENTS_COMMENT',               'Comment');

// navigation bar
define('_EZCOMMENTS_DISPLAY',               'Display');
define('_EZCOMMENTS_FLAT',                  'Flat');
define('_EZCOMMENTS_NEWESTFIRST',           'Newest First');
define('_EZCOMMENTS_OLDESTFIRST',           'Oldest First');
define('_EZCOMMENTS_ORDER',                 'Order');
define('_EZCOMMENTS_THREADED',              'Threaded');

// search plugin
define('_EZCOMMENTS_SEARCH',                'Search comments');
define('_EZCOMMENTS_NOCOMMENTSFOUND',       'No comments matched your search');

// comment moderation
define('_EZCOMMENTS_MODERERATE',              'Enable comment moderation');
define('_EZCOMMENTS_MODLINKCOUNT',            'Number of links in comment before moderation');
define('_EZCOMMENTS_MODLIST',                 'Words to trigger moderation');
define('_EZCOMMENTS_BLACKLINKCOUNT',          'Number of links in comment before blacklisting');
define('_EZCOMMENTS_BLACKLIST',               'Words to blacklist from comments');
define('_EZCOMMENTS_BLACKLISTNOTE',           'Note: Comments containing words listed here will be completely ignored by the comments module');
define('_EZCOMMENTS_SEPERATELINE',            'Separate multiple words with new lines');
define('_EZCOMMENTS_SENDINFOMAILMOD',         'Send mail on comment requiring moderation');
define('_EZCOMMENTS_MODMAILSUBJECT',          'New comment for your site');
define('_EZCOMMENTS_MODMAILBODY',             'A new comment was submitted to your site that requires moderation');
define('_EZCOMMENTS_ALWAYSMODERERATE',        'All comments require moderation');
define('_EZCOMMENTS_HELDFORMODERATION',       'Your comment was held for moderation and will be reviewed shortly');
define('_EZCOMMENTS_COMMENTBLACKLISTED',      'Your comment contains unacceptable content and has been rejected');
define('_EZCOMMENTS_PROXYBLACKLIST',          'Blacklist comments from insecure proxies');
define('_EZCOMMENTS_DONTMODERATEIFCOMMENTED', 'Don\'t require moderation for comments from users who\'ve already commented');
define('_EZCOMMENTS_MODERATIONON',            '<strong>Please note:</strong> Comment moderation is enabled and may delay your comment. There is no need to resubmit your comment.');
define('_EZCOMMENTS_APPLYMODRULES',           'Re-apply moderation rules');
define('_EZCOMMENTS_APPLYMODRULESINTRO',      'This page allows to to re-apply the defined moderation rules to all existing comments allowing all comments to be re-checked if you change your moderation rules.');
define('_EZCOMMENTS_APPLYMODRULESALL',        'Apply moderation rules to all comments');
define('_EZCOMMENTS_APPLYMODRULESSTATUS',     'Comments with the status');
define('_EZCOMMENTS_TOBEREJECTED',            'The following comments will become rejected');
define('_EZCOMMENTS_TOBEMODERATED',           'The following comments will require moderation');
define('_EZCOMMENTS_APPLYNEWRULES',           'Apply new rules comments');

// comment statuses
define('_EZCOMMENTS_APPROVED',              'Approved');
define('_EZCOMMENTS_PENDING',               'Pending');
define('_EZCOMMENTS_REJECTED',              'Rejected');
define('_EZCOMMENTS_SPAM',                  'Spam');

// modifyconfig fieldsets
define('_EZCOMMENTS_MISCSETTINGS',          'Miscellaneous');
define('_EZCOMMENTS_MODERATIONSETTINGS',    'Moderation');
define('_EZCOMMENTS_NOTIFICATIONSETTINGS',  'Notification');

// comment purging options
define('_EZCOMMENTS_PURGE',                 'Purge comments');
define('_EZCOMMENTS_PURGEPENDING',          'Purge all pending comments');
define('_EZCOMMENTS_PURGEREJECTED',         'Purge all rejected comments');

// Block
define('_EZCOMMENTS_NUMENTRIES',            'Number of comments to display');
define('_EZCOMMENTS_SHOWUSERNAME',          'Show username?');
define('_EZCOMMENTS_LINKUSERNAME',          'Link username to profile?');
define('_EZCOMMENTS_SHOWDATE',              'Show Date?');
define('_EZCOMMENTS_SHOWPENDING',           'Show pending comments?');
define('_EZCOMMENTS_SELECT_MODULE',         'Show comments for the following module');
define('_EZCOMMENTS_ALLMODULES',            'All');

// ip address logging
define('_EZCOMMENTS_IPADDR',                'IP address');
define('_EZCOMMENTS_LOGIPADDR',             'Log IP addresses');
define('_EZCOMMENTS_IPADDRNOTLOGGED',       'IP address not logged');

// multiple comment processing
define('_EZCOMMENTSWITHSELECTED',            'With selected comments: ');
define('_EZCOMMENTS_APPROVE',                'Approve');
define('_EZCOMMENTS_REJECT',                 'Reject');
define('_EZCOMMENTS_HOLD',                   'Hold');

// comment stats
define('_EZCOMMENTS_STATS',                  'Comment statistics');
define('_EZCOMMENTS_TOTAL',                  'Total comments');
define('_EZCOMMENTS_ITEM',                   'Item ID');
define('_EZCOMMENTS_CONFIRMDELETEMODULE',    'Confirm deletion of all comments attached to module \'%m%\'');
define('_EZCOMMENTS_CANCELDELETEMODULE',     'Cancel deletion of all comments attached to module \'%m%\'');
define('_EZCOMMENTS_CONFIRMDELETEITEM',      'Confirm deletion of all comments for object id \'%o%\' attached to module \'%m%\'');
define('_EZCOMMENTS_CANCELDELETEITEM',       'Cancel deletion of all comments for object id \'%o%\' attached to module \'%m%\'');

// comment typing
define('_EZCOMMENTS_TYPE',                    'Comment type');

// comment feeds
define('_EZCOMMENTS_FEEDS',	                  'Feeds');
define('_EZCOMMENTS_FEEDTYPE',                'Type of feed');
define('_EZCOMMENTS_FEEDCOUNT',               'Number of items to display in feed');
define('_EZCOMMENTS_ATOM',                    'Atom 0.3');
define('_EZCOMMENTS_RSS',                     'RSS 2.0');
define('_EZCOMMENTS_FEEDNOTE',                'Note: both the feed type and feed count can be overriden using feedtype and feedcount parameters appended to the feed URL');

// pager defines
define('_EZCOMMENTS_ENABLEPAGER',             'Enable pager (user view)');
define('_EZCOMMENTS_COMMENTSPERPAGE',         'Comments per page (user view)');

// status filter
define('_EZCOMMENTS_FILTERBYSTATUS',          'Filter by status :');
define('_EZCOMMENTS_SHOWALL',                 'Show all comments');

// askismet
define('_EZCOMMENTS_AKISMET',                 'Akismet spam dectection service');
define('_EZCOMMENTS_AKISMETNOTE',             '<a href="http://akismet.com/">Akismet</a> is a spam detection service that can, in many 
cases, eliminate comment and trackback spam. To use akismet you need to install and configure the <a href="http://code.zikula.org/ezcomments/">Akismet module</a>.');
define('_EZCOMMENTS_ENABLEAKISMET',           'Enable akismet');
define('_EZCOMMENTS_AKISMETSTATUS',           'Status level to apply to comments flagged as spam by akismet');

// username requried for guest comments
define('_EZCOMMENTS_ANON_NAME',               'Require name for unregistered user');
define('_EZCOMMENTS_ANON_NAME_FORM',          '(required for unregistered users)');
define('_EZCOMMENTS_ANON_NAME_REJECT',        'Error! Sorry! The name field is required. Comment rejected');
