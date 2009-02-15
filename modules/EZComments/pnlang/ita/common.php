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

define('_EZCOMMENTS',                       'Commenti');
define('_EZCOMMENTS_NOAUTH',                'Impossibile accedere ai commenti');    
define('_EZCOMMENTS_ONLYREG',               'Solo gli utenti registrati possono inviare commenti');    
define('_EZCOMMENTS_GOTOREG',               'Entra');
define('_EZCOMMENTS_ADD',                   'Aggiungi');
define('_EZCOMMENTS_DEL',                   'Cancella questo commento');
define('_EZCOMMENTS_COMMENT_ADD',           'Aggiungi un nuovo commento');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Answer');  // Translate me!
define('_EZCOMMENTS_COMMENT_FROM',          'by');      // Translate me!  Meaning: the autor of the comment
define('_EZCOMMENTS_COMMENT_ON',            'on');      // Translate me!  Meaning: the date of the comment   "by foobar on 11-11-2003"
define('_EZCOMMENTS_CREATED',               'Commento aggiunto');
define('_EZCOMMENTS_DELETED',               'Commento cancellato');
define('_EZCOMMENTS_FAILED',                'Erroreinterno');
define('_EZCOMMENTS_NODIRECTACCESS',        'Non Ë possibile accedere direttamente a questo modulo');
define('_EZCOMMENTS_RULES',                 'Definisci le regole per i tuoi commenti qui');

define('_EZCOMMENTS_ADMIN',                 'Gestione di EZComments');
define('_EZCOMMENTS_ADMIN_MAIN',            'Main EZComments Administration');    // Translate me!
define('_EZCOMMENTS_SENDINFOMAIL',          'invia una email per i nuovi commenti');
define('_EZCOMMENTS_OK',                    'Accetta');
define('_EZCOMMENTS_LASTCOMMENTS',          'Ultimi commenti');
define('_EZCOMMENTS_USERNAME',              'Utente');
define('_EZCOMMENTS_MODULE',                'Modulo');
define('_EZCOMMENTS_TEMPLATE',              'Default template');
define('_EZCOMMENTS_DELETESELECTED',        'Delete selected comments');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'No orphaned comments');
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Back');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'This functionality allows you to delete comments that are in the database for removed modules.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Select module:');
define('_EZCOMMENTS_CLEANUP_GO',            'Delete all comments for this module');
define('_EZCOMMENTS_CLEANUP',               'Delete orphanded comments');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Import comments from other modules');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'No migration plugins available');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Back');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Migrate:');
define('_EZCOMMENTS_MIGRATE_GO',            'Start migration');
define('_EZCOMMENTS_MIGRATE',               'Migrate Comments');

define('_EZCOMMENTS_FAILED1',               'Errore nel creare la tabella');
define('_EZCOMMENTS_FAILED2',               'Errore nel creare l\'hook');
define('_EZCOMMENTS_FAILED3',               'Errore nel cancellare la tabella');
define('_EZCOMMENTS_FAILED4',               'Errore nel cancellare l\'hook');
define('_EZCOMMENTS_FAILED5',               'Errore nell\'aggiornare il campo');

define('_EZCOMMENTS_MAILSUBJECT',           'E\' stato inviato un nuovo commento');
define('_EZCOMMENTS_MAILBODY',              'E\' stato inviato un nuovo commento');

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
define('_EZCOMMENTS_COMMENT',               'Commento');

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
