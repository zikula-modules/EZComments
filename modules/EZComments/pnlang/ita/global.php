<?php 
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Translations by: Stefano garuti AKA Garubi garubi@users.sourceforge.net
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

define('_EZCOMMENTS',                   'Commenti');
define('_EZCOMMENTS_NOAUTH',            'Impossibile accedere ai commenti');	
define('_EZCOMMENTS_ONLYREG',   		'Solo gli utenti registrati possono inviare commenti');	
define('_EZCOMMENTS_GOTOREG',   		'Entra');
define('_EZCOMMENTS_ADD',               'Aggiungi');
define('_EZCOMMENTS_DEL',               'Cancella questo commento');
define('_EZCOMMENTS_COMMENT_ADD',       'Aggiungi un nuovo commento');
define('_EZCOMMENTS_COMMENT_ANSWER', 	'Answer');  // Translate me!
define('_EZCOMMENTS_COMMENT_FROM',  	'by');      // Translate me!  Meaning: the autor of the comment
define('_EZCOMMENTS_COMMENT_ON',    	'on');      // Translate me!  Meaning: the date of the comment   "by foobar on 11-11-2003"
define('_EZCCOMMENTSCREATED',           'Commento aggiunto');
define('_EZCCOMMENTSDELETED',           'Commento cancellato');
define('_EZCOMMENTS_FAILED',            'Erroreinterno');
define('_EZCOMMENTS_NODIRECTACCESS',	'Non è possibile accedere direttamente a questo modulo');
define('_EZCOMMENTS_RULES',				'Definisci le regole per i tuoi commenti qui');

define('_EZCOMMENTS_ADMIN',             'Gestione di EZComments');
define('_EZCOMMENTS_ADMIN_MAIN',		'Main EZComments Administration');    // Translate me!
define('_EZCOMMENTS_SMARTYPATH',        'Percorso a Smarty');
define('_EZCOMMENTS_SENDINFOMAIL',		'invia una email per i nuovi commenti');
define('_EZCOMMENTS_OK',                'Accetta');
define('_EZCOMMENTS_LASTCOMMENTS',      'Ultimi commenti');
define('_EZCOMMENTS_USERNAME',          'Utente');
define('_EZCOMMENTS_MODULE',            'Modulo');
define('_EZCOMMENTS_COMMENT',           'Commento');

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

define('_EZCOMMENTS_FAILED1',           'Errore nel creare la tabella');
define('_EZCOMMENTS_FAILED2',           'Errore nel creare l\'hook');
define('_EZCOMMENTS_FAILED3',           'Errore nel cancellare la tabella');
define('_EZCOMMENTS_FAILED4',           'Errore nel cancellare l\'hook');
define('_EZCOMMENTS_FAILED5',           'Errore nell\'aggiornare il campo');

define('_EZCOMMENTS_MAILSUBJECT',		'E\' stato inviato un nuovo commento');
define('_EZCOMMENTS_MAILBODY',  		'E\' stato inviato un nuovo commento');

?>