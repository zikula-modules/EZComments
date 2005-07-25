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

define('_EZCOMMENTS',                       'Kommentarer');
define('_EZCOMMENTS_NOAUTH',                'Ingen adgang til kommentarer.');
define('_EZCOMMENTS_ONLYREG',               'Kun innloggede brukere har adgang til å kommentere.');
define('_EZCOMMENTS_GOTOREG',               'registrér deg/logg inn');
define('_EZCOMMENTS_ADD',                   'Legg til');
define('_EZCOMMENTS_DEL',                   'Slett denne kommentaren');
define('_EZCOMMENTS_COMMENT_ADD',           'Legg til en kommentar');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Svar'); 
define('_EZCOMMENTS_COMMENT_FROM',          'av');
define('_EZCOMMENTS_COMMENT_ON',            'den');
define('_EZCOMMENTS_CREATED',               'Kommentar lagt til');
define('_EZCOMMENTS_DELETED',               'Kommentar slettet');
define('_EZCOMMENTS_FAILED',                'Intern feil');
define('_EZCOMMENTS_NODIRECTACCESS',        'Ingen direkte adgang til denne modulen');
define('_EZCOMMENTS_RULES',                 'Definér reglene for dine kommentarer her');

define('_EZCOMMENTS_ADMIN',                 'EZComments administrasjon');
define('_EZCOMMENTS_ADMIN_MAIN',            'EZComments hoved-administrasjon');
define('_EZCOMMENTS_SENDINFOMAIL',          'send e-post ved ny kommentar');
define('_EZCOMMENTS_OK',                    'Godta');
define('_EZCOMMENTS_LASTCOMMENTS',          'De seneste kommentarene');
define('_EZCOMMENTS_USERNAME',              'Bruker');
define('_EZCOMMENTS_MODULE',                'Modul');
define('_EZCOMMENTS_TEMPLATE',              'Default template');
define('_EZCOMMENTS_DELETESELECTED',        'Delete selected comments');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'Ingen kommentarer tilknyttet fjernede moduler'); 
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Tilbake');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'Denne funksjonen lar deg slette kommentarer tilknyttet fjernede moduler.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Velg modul:');
define('_EZCOMMENTS_CLEANUP_GO',            'Slett alle kommentarer tilknyttet denne modulen');
define('_EZCOMMENTS_CLEANUP',               'Slett kommentarer tilknyttet fjernede moduler');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Importér kommentarer fra andre moduler');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'Ingen flyttbare plugins tilgjengelige');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Tilbake');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Flytt:');
define('_EZCOMMENTS_MIGRATE_GO',            'Begynn flytting');
define('_EZCOMMENTS_MIGRATE',               'Flytt kommentarer');

define('_EZCOMMENTS_FAILED1',               'Feil ved opprettelse av tabell');
define('_EZCOMMENTS_FAILED2',               'Feil ved opprettelse av tilknytning (hook)');
define('_EZCOMMENTS_FAILED3',               'Feil ved sletting av tabell');
define('_EZCOMMENTS_FAILED4',               'Feil ved sletting av tilknytning (hook)');
define('_EZCOMMENTS_FAILED5',               'Oppdatering av tabell mislyktes');

define('_EZCOMMENTS_MAILSUBJECT',           'En ny kommentar ble lagt inn');
define('_EZCOMMENTS_MAILBODY',              'En ny kommentar ble lagt inn');

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

define('_EZCOMMENTS_EDIT',                  'Edit Comment');
define('_EZCOMMENTS_ITEMSPERPAGE',          'Items per page');

define('_EZCOMMENTS_AUTHOR',                'Author');
define('_EZCOMMENTS_COMMENT',               'Kommentar');

// navigation bar
define('_EZCOMMENTS_DISPLAY',               'Display');
define('_EZCOMMENTS_FLAT',                  'Flat');
define('_EZCOMMENTS_NEWESTFIRST',           'Newest First');
define('_EZCOMMENTS_OLDESTFIRST',           'Oldest First');
define('_EZCOMMENTS_ORDER',                 'Order');
define('_EZCOMMENTS_THREADED',              'Threaded');

define('_EZCOMMENTS_ALLOWANONUSERSETINFO',  'Allow unregistered users to set user information');
define('_EZCOMMENTS_ANONNAME',              'Name');
define('_EZCOMMENTS_ANONMAIL',              'E-mail address'); 

define('_EZCOMMENTS_SEARCH',                'Search comments');
define('_EZCOMMENTS_NOCOMMENTSFOUND',       'No comments matched your search');

define('_EZCOMMENTS_TOP',                   'Top');
define('_EZCOMMENTS_BOTTOM',                'Bottom');

// comment moderation
define('_EZCOMMENTS_MODERERATE',            'Enable comment moderation');
define('_EZCOMMENTS_MODLINKCOUNT',          'Number of links in comment before moderation');
define('_EZCOMMENTS_MODLIST',               'Words to trigger moderation');
define('_EZCOMMENTS_BLACKLIST',             'Words to blacklist from comments');
define('_EZCOMMENTS_BLACKLISTNOTE',         'Note: Comments containing words list here will completely ignored by comments module');
define('_EZCOMMENTS_SEPERATELINE',          'Separate multiple words with new lines');
define('_EZCOMMENTS_SENDINFOMAILMOD',       'Send mail comments requiring moderation');
define('_EZCOMMENTS_MODMAILSUBJECT',        'New comment for your site');
define('_EZCOMMENTS_MODMAILBODY',           'A new comment was submitted to your site that requires moderation');
define('_EZCOMMENTS_ALWAYSMODERERATE',      'All comments require moderation');
define('_EZCOMMENTS_HELDFORMODERATION',     'Your comment was held for moderation and will be reviewed shortly');
define('_EZCOMMENTS_COMMENTBLACKLISTED',    'Your comment contains unacceptable content and has been rejected');
define('_EZCOMMENTS_PROXYBLACKLIST',        'Blacklist comments from insecure proxies');

// comment statuses
define('_EZCOMMENTS_APPROVED',              'Approved');
define('_EZCOMMENTS_PENDING',               'Pending');
define('_EZCOMMENTS_REJECTED',              'Rejected');

// modifyconfig fieldsets
define('_EZCOMMENTS_MISCSETTINGS',          'Miscellaneous');
define('_EZCOMMENTS_MODERATIONSETTINGS',    'Moderation');
define('_EZCOMMENTS_NOTIFICATIONSETTINGS',  'Notification');

// mails
define('_EZCOMMENTS_SHOW',                  'Show');
define('_EZCOMMENTS_MODERATE2',             'Moderate');
define('_EZCOMMENTS_DELETE',                'Delete');

// comment purging options
define('_EZCOMMENTS_PURGE',                 'Purge comments');
define('_EZCOMMENTS_PURGEPENDING',          'Purge all pending comments');
define('_EZCOMMENTS_PURGEREJECTED',         'Purge all rejected comments');

// Block
define('_EZCOMMENTS_NUMENTRIES',            'Number of comments to display');
define('_EZCOMMENTS_SHOWUSERNAME',          'Show username?');
define('_EZCOMMENTS_LINKUSERNAME',          'Link username to profile?');
define('_EZCOMMENTS_SHOWDATE',              'Show Date?');
define('_EZCOMMENTS_SELECT_MODULE',         'Show comments for the following module');
define('_EZCOMMENTS_ALLMODULES',            'All');

?>
