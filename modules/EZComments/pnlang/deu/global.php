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

define('_EZCOMMENTS',         			'Kommentare');
define('_EZCOMMENTS_NOAUTH',   			'Keine Berechtigung für die Kommentare.');
define('_EZCOMMENTS_ONLYREG',   		'Nur angemeldete Benutzer dürfen Kommentare verfassen.');
define('_EZCOMMENTS_GOTOREG',   		'Zur Registrierung/Anmeldung');
define('_EZCOMMENTS_ADD', 	  			'Hinzufügen');
define('_EZCOMMENTS_DEL', 	  			'Diesen Kommentar löschen');
define('_EZCOMMENTS_COMMENT_ADD', 		'Einen Kommentar hinzufügen');
define('_EZCOMMENTS_COMMENT_ANSWER', 	'Hierauf antworten');
define('_EZCOMMENTS_COMMENT_FROM',  	'von');
define('_EZCOMMENTS_COMMENT_ON',    	'am');
define('_EZCCOMMENTSCREATED', 			'Kommentar hinzugefügt');
define('_EZCCOMMENTSDELETED', 			'Kommentar gelöscht');
define('_EZCOMMENTS_FAILED',   			'Interner Fehler');
define('_EZCOMMENTS_NODIRECTACCESS',	'Kein direkter Zugriff auf dieses Modul');
define('_EZCOMMENTS_RULES',				'Hier sollten die Regeln für die Benutzung der Kommentaren hinterlegt werden.');

define('_EZCOMMENTS_ADMIN',				'EZComments Administration');
define('_EZCOMMENTS_ADMIN_MAIN',		'Hauptmenü');
define('_EZCOMMENTS_SENDINFOMAIL',		'Mail bei neuen Kommentaren verschicken');
define('_EZCOMMENTS_OK', 				'Bestätigen');
define('_EZCOMMENTS_LASTCOMMENTS', 		'Die letzten Kommentare');
define('_EZCOMMENTS_USERNAME', 			'Benutzer');
define('_EZCOMMENTS_MODULE', 			'Modul');
define('_EZCOMMENTS_COMMENT', 			'Kommentar');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO', 'Es gibt keine verwaisten Kommentare');
define('_EZCOMMENTS_CLEANUP_GOBACK',      'Zurück');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',     'Mit dieser Funktionalität können Kommentare zu Modulen, die entfernt wurden, gelöscht werden.');
define('_EZCOMMENTS_CLEANUP_LABEL',       'Modul auswählen:');
define('_EZCOMMENTS_CLEANUP_GO',          'Alle Kommentare zu diesem Modul löschen');
define('_EZCOMMENTS_CLEANUP',             'Verwaiste Kommentare löschen');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',     'Hiermit können Kommentare aus bestehenden Modulen importiert werden.');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO', 'Es gibt keine zu migrierenden Module');
define('_EZCOMMENTS_MIGRATE_GOBACK',      'Zurück');
define('_EZCOMMENTS_MIGRATE_LABEL',       'Migration von:');
define('_EZCOMMENTS_MIGRATE_GO',          'Migration starten');
define('_EZCOMMENTS_MIGRATE',             'Kommentare migrieren');

define('_EZCOMMENTS_FAILED1', 			'Erstellung der Tabelle fehlgeschlagen');
define('_EZCOMMENTS_FAILED2', 			'Registrierung des Hooks fehlgeschlagen');
define('_EZCOMMENTS_FAILED3', 			'Löschen der Tabelle fehlgeschlagen');
define('_EZCOMMENTS_FAILED4', 			'Löschen des Hooks fehlgeschlagen');
define('_EZCOMMENTS_FAILED5', 			'Update der Tabellen fehlgeschlagen');

define('_EZCOMMENTS_MAILSUBJECT',		'Neuer Kommentar'); 
define('_EZCOMMENTS_MAILBODY',  		'Ein neuer Kommentar wurde eingegeben'); 
// Steffen 01/2005
define('_EZCOMMENTS_POSTED',  			'verfasst'); 
define('_EZCOMMENTS_REG_SINCE',  		'registriert'); 
define('_EZCOMMENTS_STATUS',  			'Status'); 
define('_EZCOMMENTS_OFFLINE',  			'Offline'); 
define('_EZCOMMENTS_ONLINE',  			'Online'); 
define('_EZCOMMENTS_PROFILE',  			'Profil'); 
define('_EZCOMMENTS_SEND_PM',  			'PM sende'); 
define('_EZCOMMENTS_FROM',  			  'Wohnort'); 
define('_EZCOMMENTS_SUBJECT',  			'Titel');
define('_EZCOMMENTS_AUTHOR',  			'Autor');

// Steffen 0.8, 05/2005 
// user
define('_EZCOMMENTS_DISPLAY',  			'Darstellung');
define('_EZCOMMENTS_FLAT',  			  'Flach');
define('_EZCOMMENTS_THREADED',  		'Kommentarbaum');
define('_EZCOMMENTS_OLDESTFIRST',  	'absteigend');
define('_EZCOMMENTS_NEWESTFIRST',  	'aufsteigend');
define('_EZCOMMENTS_ORDER',  			  'Reihenfolge');

// admin
define('_EZCOMMENTS_MISCSETTINGS',  'Einstellungen');
define('_EZCOMMENTS_ITEMSPERPAGE',  'Kommentar pro Seite');
define('_EZCOMMENTS_TEMPLATE',  		'Layout');
define('_EZCOMMENTS_ALLOWANONUSERSETINFO',  'Anonyme User dürfen ihre Daten angeben');
define('_EZCOMMENTS_NOTIFICATIONSETTINGS',  'Benachrichtigungen');
define('_EZCOMMENTS_SENDINFOMAILMOD',			  'Bitten um Moderation mailen');
define('_EZCOMMENTS_MODERATIONSETTINGS',  	'Moderation');
define('_EZCOMMENTS_MODERERATE',  	'Moderierte Kommentare');
define('_EZCOMMENTS_ALWAYSMODERERATE',  		'Nur moderiert freigeben');
define('_EZCOMMENTS_MODLINKCOUNT',  'Höchstanzahl von Links, bevor freigeschaltet werden muss');
define('_EZCOMMENTS_MODLIST',  			'Wörter, die automatisch Moderation erfordern');
define('_EZCOMMENTS_SEPERATELINE',  'Ein Stichwort pro Zeile');
define('_EZCOMMENTS_BLACKLIST',  		'Wörter die zur Löschung führen');
define('_EZCOMMENTS_BLACKLISTNOTE', 'Achtung: Kommentare, die diese Wörter enthalten, werden nicht zugelassen');
define('_EZCOMMENTS_MODMAILSUBJECT',       'Neuer Kommentar');
define('_EZCOMMENTS_MODMAILBODY',          'Ein neuer Kommentar wurde erstellt und erfordert Moderation.');
define('_EZCOMMENTS_HELDFORMODERATION',    'Der Kommentar wird so bald wie möglich freigeschaltet. Vielen Dank');
define('_EZCOMMENTS_COMMENTBLACKLISTED',   'Der Kommentar wurde auf grund inhaltlicher Mängel nicht freigeschaltet');

define('_EZCOMMENTS_SEARCH',               'Kommentare durchsuchen');
define('_EZCOMMENTS_NOCOMMENTSFOUND',      'Keine passenden Kommentare gefunden');

define('_EZCOMMENTS_ANONNAME',             'Name');
define('_EZCOMMENTS_ANONMAIL',             'E-mail Adresse');

// comment statuses
define('_EZCOMMENTS_APPROVED', 'freigeschaltet');
define('_EZCOMMENTS_PENDING', 'wartet');
define('_EZCOMMENTS_REJECTED', 'abgelehnt');
?>
