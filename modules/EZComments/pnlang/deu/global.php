<?php 
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Author: J�rg Napp
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

define('_EZCOMMENTS',                       'Kommentare');
define('_EZCOMMENTS_NOAUTH',                'Keine Berechtigung f�r die Kommentare.');
define('_EZCOMMENTS_ONLYREG',               'Nur angemeldete Benutzer d�rfen Kommentare verfassen.');
define('_EZCOMMENTS_GOTOREG',               'Zur Registrierung/Anmeldung');
define('_EZCOMMENTS_ADD',                   'Hinzuf�gen');
define('_EZCOMMENTS_DEL',                   'Diesen Kommentar l�schen');
define('_EZCOMMENTS_COMMENT_ADD',           'Einen Kommentar hinzuf�gen');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Hierauf antworten');
define('_EZCOMMENTS_COMMENT_FROM',          'von');
define('_EZCOMMENTS_COMMENT_ON',            'am');
define('_EZCOMMENTS_CREATED',               'Kommentar hinzugef�gt');
define('_EZCOMMENTS_DELETED',               'Kommentar gel�scht');
define('_EZCOMMENTS_FAILED',                'Interner Fehler');
define('_EZCOMMENTS_NODIRECTACCESS',        'Kein direkter Zugriff auf dieses Modul');
define('_EZCOMMENTS_RULES',                 'Hier sollten die Regeln f�r die Benutzung der Kommentaren hinterlegt werden.');

define('_EZCOMMENTS_ADMIN',                 'EZComments Administration');
define('_EZCOMMENTS_ADMIN_MAIN',            'Hauptmen�');
define('_EZCOMMENTS_SENDINFOMAIL',          'Mail bei neuen Kommentaren verschicken');
define('_EZCOMMENTS_OK',                    'Best�tigen');
define('_EZCOMMENTS_LASTCOMMENTS',          'Die letzten Kommentare');
define('_EZCOMMENTS_USERNAME',              'Benutzer');
define('_EZCOMMENTS_MODULE',                'Modul');
define('_EZCOMMENTS_TEMPLATE',              'Layout');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'Es gibt keine verwaisten Kommentare');
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Zur�ck');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'Mit dieser Funktionalit�t k�nnen Kommentare zu Modulen, die entfernt wurden, gel�scht werden.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Modul ausw�hlen:');
define('_EZCOMMENTS_CLEANUP_GO',            'Alle Kommentare zu diesem Modul l�schen');
define('_EZCOMMENTS_CLEANUP',               'Verwaiste Kommentare l�schen');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Hiermit k�nnen Kommentare aus bestehenden Modulen importiert werden.');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'Es gibt keine zu migrierenden Module');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Zur�ck');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Migration von:');
define('_EZCOMMENTS_MIGRATE_GO',            'Migration starten');
define('_EZCOMMENTS_MIGRATE',               'Kommentare migrieren');

define('_EZCOMMENTS_FAILED1',               'Erstellung der Tabelle fehlgeschlagen');
define('_EZCOMMENTS_FAILED2',               'Registrierung des Hooks fehlgeschlagen');
define('_EZCOMMENTS_FAILED3',               'L�schen der Tabelle fehlgeschlagen');
define('_EZCOMMENTS_FAILED4',               'L�schen des Hooks fehlgeschlagen');
define('_EZCOMMENTS_FAILED5',               'Update der Tabellen fehlgeschlagen');

define('_EZCOMMENTS_MAILSUBJECT',           'Neuer Kommentar'); 
define('_EZCOMMENTS_MAILBODY',              'Ein neuer Kommentar wurde eingegeben'); 

define('_EZCOMMENTS_POSTED',                'verfasst'); 
define('_EZCOMMENTS_REG_SINCE',             'registriert'); 
define('_EZCOMMENTS_NOTREG',                'nicht registriert'); 
define('_EZCOMMENTS_STATUS',                'Status'); 
define('_EZCOMMENTS_OFFLINE',               'Offline'); 
define('_EZCOMMENTS_ONLINE',                'Online'); 
define('_EZCOMMENTS_PROFILE',               'Profil'); 
define('_EZCOMMENTS_SEND_PM',               'PM senden'); 
define('_EZCOMMENTS_FROM',                  'Wohnort'); 
define('_EZCOMMENTS_SUBJECT',               'Titel');

define('_EZCOMMENTS_EDIT',                  'Kommentar bearbeiten');
define('_EZCOMMENTS_ITEMSPERPAGE',          'Kommentare pro Seite');

define('_EZCOMMENTS_AUTHOR',                'Autor');
define('_EZCOMMENTS_COMMENT',               'Kommentar');

// navigation bar
define('_EZCOMMENTS_DISPLAY',               'Darstellung');
define('_EZCOMMENTS_FLAT',                  'Flach');
define('_EZCOMMENTS_THREADED',              'Kommentarbaum');
define('_EZCOMMENTS_OLDESTFIRST',           'absteigend');
define('_EZCOMMENTS_NEWESTFIRST',           'aufsteigend');
define('_EZCOMMENTS_ORDER',                 'Reihenfolge');

define('_EZCOMMENTS_ALLOWANONUSERSETINFO',  'Anonyme User d�rfen ihre Daten angeben');
define('_EZCOMMENTS_ANONNAME',              'Name');
define('_EZCOMMENTS_ANONMAIL',              'E-mail Adresse');

define('_EZCOMMENTS_SEARCH',                'Kommentare durchsuchen');
define('_EZCOMMENTS_NOCOMMENTSFOUND',       'Keine passenden Kommentare gefunden');

define('_EZCOMMENTS_TOP',                   'Top');
define('_EZCOMMENTS_BOTTOM',                'Bottom');

// comment moderation
define('_EZCOMMENTS_MODERERATE',            'Moderierte Kommentare');
define('_EZCOMMENTS_MODLINKCOUNT',          'H�chstanzahl von Links, bevor freigeschaltet werden muss');
define('_EZCOMMENTS_MODLIST',               'W�rter, die automatisch Moderation erfordern');
define('_EZCOMMENTS_BLACKLIST',             'W�rter die zur L�schung f�hren');
define('_EZCOMMENTS_BLACKLISTNOTE',         'Achtung: Kommentare, die diese W�rter enthalten, werden nicht zugelassen');
define('_EZCOMMENTS_SEPERATELINE',          'Ein Stichwort pro Zeile');
define('_EZCOMMENTS_SENDINFOMAILMOD',       'Bitten um Moderation mailen');
define('_EZCOMMENTS_MODMAILSUBJECT',        'Neuer Kommentar');
define('_EZCOMMENTS_MODMAILBODY',           'Ein neuer Kommentar wurde erstellt und erfordert Moderation.');
define('_EZCOMMENTS_ALWAYSMODERERATE',      'Nur moderiert freigeben');
define('_EZCOMMENTS_HELDFORMODERATION',     'Der Kommentar wird so bald wie m�glich freigeschaltet. Vielen Dank');
define('_EZCOMMENTS_COMMENTBLACKLISTED',    'Der Kommentar wurde aufgrund inhaltlicher M�ngel nicht freigeschaltet');
define('_EZCOMMENTS_PROXYBLACKLIST',        'Kommentare, die �ber unsichere Proxies abgegeben werden, ablehnen');
define('_EZCOMMENTS_DONTMODERATEIFCOMMENTED', 'Benutzer, die bereits Kommentare abgegeben haben, nicht moderieren');

// comment statuses
define('_EZCOMMENTS_APPROVED',              'freigeschaltet');
define('_EZCOMMENTS_PENDING',               'wartet');
define('_EZCOMMENTS_REJECTED',              'abgelehnt');

// modifyconfig fieldsets
define('_EZCOMMENTS_MISCSETTINGS',          'Einstellungen');
define('_EZCOMMENTS_NOTIFICATIONSETTINGS',  'Benachrichtigungen');
define('_EZCOMMENTS_MODERATIONSETTINGS',    'Moderation');

// mails
define('_EZCOMMENTS_SHOW',                  'Anzeigen');
define('_EZCOMMENTS_MODERATE2',             'Freigeben/Ablehnen');
define('_EZCOMMENTS_DELETE',                'L�schen');

// comment purging options
define('_EZCOMMENTS_PURGE',                 'Kommentare entfernen');
define('_EZCOMMENTS_PURGEPENDING',          'Alle noch nicht freigegebenen Kommentare entfernen');
define('_EZCOMMENTS_PURGEREJECTED',         'Alle zur�ckgewiesenen Kommentare entfernen');

// Block
define('_EZCOMMENTS_NUMENTRIES',            'Anzahl der anzuzeigenden Kommentare');
define('_EZCOMMENTS_SHOWUSERNAME',          'Benutzernamen anzeigen?');
define('_EZCOMMENTS_LINKUSERNAME',          'Benutzername mit Link zum Profil versehen?');
define('_EZCOMMENTS_SHOWDATE',              'Datum des Kommentars anzeigen?');
define('_EZCOMMENTS_SELECT_MODULE',         'Kommentare f�r das folgende Modul anzeigen');
define('_EZCOMMENTS_ALLMODULES',            'Alle');

// ip address logging
define('_EZCOMMENTS_IPADDR',                'IP-Adressen');
define('_EZCOMMENTS_LOGIPADDR',             'IP-Adressen protokollieren');
define('_EZCOMMENTS_IPADDRNOTLOGGED',       'IP-Adressen nicht protokolliert');

// multiple comment processing
define('_EZCOMMENTSWITHSELECTED',            'Die ausgew�hlten Kommentare');
define('_EZCOMMENTS_APPROVE',                'freischalten');
define('_EZCOMMENTS_REJECT',                 'ablehnen');
define('_EZCOMMENTS_HOLD',                   'auf "wartend" setzen');

// comment stats
define('_EZCOMMENTS_STATS',                  'Kommentarstatistiken');
define('_EZCOMMENTS_TOTAL',                  'Gesamtanzahl');
define('_EZCOMMENTS_ITEM',                   'Element-ID');
define('_EZCOMMENTS_CONFIRMDELETEMODULE',    'Die L�schung aller zum Modul %m% geh�rigen Kommentaren best�tigen');
define('_EZCOMMENTS_CANCELDELETEMODULE',     'Die L�schung aller zum Modul %m% geh�rigen Kommentaren abbrechen');
define('_EZCOMMENTS_CONFIRMDELETEITEM',      'Die L�schung aller zum Modul %m%, Element %o% geh�rigen Kommentaren best�tigen');
define('_EZCOMMENTS_CANCELDELETEITEM',       'Die L�schung aller zum Modul %m%, Element %o% geh�rigen Kommentaren abbrechen');

// comment typing
define('_EZCOMMENTS_TYPE',                    'Kommentartyp');

// comment feeds
define('_EZCOMMENTS_FEEDS',	                  'Feeds');
define('_EZCOMMENTS_FEEDTYPE',                'Art des Feeds');
define('_EZCOMMENTS_FEEDCOUNT',               'Anzahl der Kommentare im Feed');
define('_EZCOMMENTS_ATOM',                    'Atom 0.3');
define('_EZCOMMENTS_RSS',                     'RSS 2.0');
define('_EZCOMMENTS_FEEDNOTE',                'Hinweis: Sowohl der Typ des Feeds als auch die Anzahl der angezeigten Kommentare k�nnen �berschrieben werden, indem die Parameter feedtype und feedcount an die URL des Feeds angeh�ngt werden.');

?>
