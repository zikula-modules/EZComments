<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

define('_EZCOMMENTS',                       'Komentáøe');
define('_EZCOMMENTS_NOAUTH',                'Nemáte pøístup ke komentáøùm.');
define('_EZCOMMENTS_ONLYREG',               'Pouze pøihlášení uživatelé mohou komentovat.');
define('_EZCOMMENTS_GOTOREG',               'Registrovat/Pøihlásit');
define('_EZCOMMENTS_ADD',                   'Pøidat');
define('_EZCOMMENTS_DEL',                   'Smazat komentáø');
define('_EZCOMMENTS_COMMENT_ADD',           'Pøidat nový komentáø');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Odpovìï');
define('_EZCOMMENTS_COMMENT_FROM',          'od');
define('_EZCOMMENTS_COMMENT_ON',            'pro');
define('_EZCOMMENTS_CREATED',               'Komentáø byl pøidán');
define('_EZCOMMENTS_DELETED',               'Komentáø byl smazán');
define('_EZCOMMENTS_FAILED',                'Interní chyba');
define('_EZCOMMENTS_NODIRECTACCESS',        'K tomuto modulu nelze pøistupovat pøímo');
define('_EZCOMMENTS_RULES',                 'Definujte pravidla pro vaše komentáøe');

define('_EZCOMMENTS_ADMIN',                 'Administrace EZComments');
define('_EZCOMMENTS_ADMIN_MAIN',            'Hlavní administrace EZComments');
define('_EZCOMMENTS_SENDINFOMAIL',          'Odeslat mail pøi každém novém komentáøi');
define('_EZCOMMENTS_OK',                    'Uložit');
define('_EZCOMMENTS_LASTCOMMENTS',          'Poslední komentáøe');
define('_EZCOMMENTS_USERNAME',              'Uživatel');
define('_EZCOMMENTS_MODULE',                'Modul');
define('_EZCOMMENTS_TEMPLATE',              'Default template');
define('_EZCOMMENTS_DELETESELECTED',        'Delete selected comments');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'Nejsou žádné osiøelé komentáøe');
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Zpìt');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'Zde mùžete smazat všechny komentáøe, které v databázi osiøely po odstranìných modulech.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Vyberte modul:');
define('_EZCOMMENTS_CLEANUP_GO',            'Smazat všechny komentáøi k tomuto modulu');
define('_EZCOMMENTS_CLEANUP',               'Smazat osiøelé komentáøe');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Import komentáøù z jiných modulù');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'Plugin pro migraci není k dispozici');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Zpìt');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Migrovat z:');
define('_EZCOMMENTS_MIGRATE_GO',            'Zahájit migraci');
define('_EZCOMMENTS_MIGRATE',               'Migrovat komentáøe');

define('_EZCOMMENTS_FAILED1',               'Chyba pøi vytváøení tabulky');
define('_EZCOMMENTS_FAILED2',               'Chyba pøi vytváøení hook');
define('_EZCOMMENTS_FAILED3',               'Chyba pøi mazání tabulky');
define('_EZCOMMENTS_FAILED4',               'Chyba pøi mazání hook');
define('_EZCOMMENTS_FAILED5',               'Aktualizace tabulky selhala');

define('_EZCOMMENTS_MAILSUBJECT',           'Byl pøidán nový komentáø'); 
define('_EZCOMMENTS_MAILBODY',              'Byl pøidán nový komentáø'); 

define('_EZCOMMENTS_POSTED',                'posted'); 
define('_EZCOMMENTS_REG_SINCE',             'registered'); 
define('_EZCOMMENTS_NOTREG',                'not registered'); 
define('_EZCOMMENTS_STATUS',                'Status'); 
define('_EZCOMMENTS_OFFLINE',                  'Offline'); 
define('_EZCOMMENTS_ONLINE',                  'Online'); 
define('_EZCOMMENTS_PROFILE',                  'Profile'); 
define('_EZCOMMENTS_SEND_PM',                  'send PM'); 
define('_EZCOMMENTS_FROM',                  'Location'); 
define('_EZCOMMENTS_SUBJECT',               'Subject');

define('_EZCOMMENTS_EDIT',                  'Edit Comment');
define('_EZCOMMENTS_ITEMSPERPAGE',          'Items per page');

define('_EZCOMMENTS_AUTHOR',                'Author');
define('_EZCOMMENTS_COMMENT',               'Comment');

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
