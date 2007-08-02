<?php 
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Translation: Teb (Dutch Postnuke Community, http://postnuke.opencms.nl)
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

define('_EZCOMMENTS',                       'Commentaren');
define('_EZCOMMENTS_NOAUTH',                'Geen toegang tot de commentaren');
define('_EZCOMMENTS_ONLYREG',               'Alleen ingelogde gebruikers kunnen commentaar plaatsen');
define('_EZCOMMENTS_GOTOREG',               'Inloggen / aanmelden');
define('_EZCOMMENTS_ADD',                   'Commentaar versturen');
define('_EZCOMMENTS_DEL',                   'Dit commentaar verwijderen');
define('_EZCOMMENTS_COMMENT_ADD',           'Commentaar invoegen');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Reageren');
define('_EZCOMMENTS_COMMENT_FROM',          'door');
define('_EZCOMMENTS_COMMENT_ON',            'op');
define('_EZCOMMENTS_CREATED',               'Commentaar toegevoegd');
define('_EZCOMMENTS_DELETED',               'Commentaar verwijderd');
define('_EZCOMMENTS_FAILED',                'Interne foutmelding');
define('_EZCOMMENTS_NODIRECTACCESS',        'Directe toegang tot deze module is niet toegestaan');
define('_EZCOMMENTS_RULES',                 'Hier worden de regels voor het invoegen van commentaar gedefinieerd.');

define('_EZCOMMENTS_ADMIN',                 'EZComments Beheer');
define('_EZCOMMENTS_ADMIN_MAIN',            'Hoofdmenu');
define('_EZCOMMENTS_SENDINFOMAIL',          'Verstuur mail bij nieuw commentaar');
define('_EZCOMMENTS_OK',                    'Accepteren');
define('_EZCOMMENTS_LASTCOMMENTS',          'De laatste %c% commentaren');
define('_EZCOMMENTS_USERNAME',              'Gebruiker');
define('_EZCOMMENTS_MODULE',                'Module');
define('_EZCOMMENTS_TEMPLATE',              'Sjabloon');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'Er zijn geen wees-commentaren');
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Terug');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'Hiermee kun je commentaren verwijderen die in de database nog gekoppeld staan aan reeds verwijderde modules.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Selecteer module:');
define('_EZCOMMENTS_CLEANUP_GO',            'Verwijder alle commentaren voor deze module');
define('_EZCOMMENTS_CLEANUP',               'Verwijder wees-commentaren');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Hiermee kunnen commentaren uit andere modules geïmporteerd worden.');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'Geen geschikte module gevonden die het migratieproces ondersteunt');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Terug');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Migreren van:');
define('_EZCOMMENTS_MIGRATE_GO',            'Start migratieproces');
define('_EZCOMMENTS_MIGRATE',               'Commentaren migreren');

define('_EZCOMMENTS_FAILED1',               'Tabel creëren mislukt');
define('_EZCOMMENTS_FAILED2',               'Hook registratie mislukt');
define('_EZCOMMENTS_FAILED3',               'Tabel verwijderen mislukt');
define('_EZCOMMENTS_FAILED4',               'Hook verwijderen mislukt');
define('_EZCOMMENTS_FAILED5',               'Bijwerken van tabellen mislukt');

define('_EZCOMMENTS_MAILSUBJECT',           'Nieuw commentaar'); 
define('_EZCOMMENTS_MAILBODY',              'Er is een nieuw commentaar ingestuurd'); 

define('_EZCOMMENTS_POSTED',                'geplaatst'); 
define('_EZCOMMENTS_REG_SINCE',             'geregistreerd'); 
define('_EZCOMMENTS_NOTREG',                'niet geregistreerd'); 
define('_EZCOMMENTS_STATUS',                'Status'); 
define('_EZCOMMENTS_OFFLINE',               'Offline'); 
define('_EZCOMMENTS_ONLINE',                'Online'); 
define('_EZCOMMENTS_PROFILE',               'Profiel'); 
define('_EZCOMMENTS_SEND_PM',               'Verstuur Persoonlijk Bericht'); 
define('_EZCOMMENTS_FROM',                  'Lokatie'); 
define('_EZCOMMENTS_SUBJECT',               'Onderwerp');

define('_EZCOMMENTS_EDIT',                  'Commentaar bewerken');
define('_EZCOMMENTS_ITEMSPERPAGE',          'Commentaren per pagina (in beheerderspaneel)');

define('_EZCOMMENTS_AUTHOR',                'Auteur');
define('_EZCOMMENTS_COMMENT',               'Commentaar');

// navigation bar
define('_EZCOMMENTS_DISPLAY',               'Weergave');
define('_EZCOMMENTS_FLAT',                  'Plat');
define('_EZCOMMENTS_THREADED',              'Boomstructuur');
define('_EZCOMMENTS_OLDESTFIRST',           'Oudste eerst');
define('_EZCOMMENTS_NEWESTFIRST',           'Nieuwste eerst');
define('_EZCOMMENTS_ORDER',                 'Volgorde');

define('_EZCOMMENTS_ALLOWANONUSERSETINFO',  'Anonieme gebruikers toestaan gebruikersinformatie in te vullen');
define('_EZCOMMENTS_ANONNAME',              'Naam');
define('_EZCOMMENTS_ANONMAIL',              'E-mail Adres (wordt niet getoond!)');
define('_EZCOMMENTS_ANONWEBSITE',           'Website'); 

define('_EZCOMMENTS_SEARCH',                'Zoek in Commentaren');
define('_EZCOMMENTS_NOCOMMENTSFOUND',       'Geen commentaar gevonden die aan de zoekcriteria voldeed');

define('_EZCOMMENTS_TOP',                   'Boven');
define('_EZCOMMENTS_BOTTOM',                'Onder');

// comment moderation
define('_EZCOMMENTS_MODERERATE',            'Moderatie voor commentaar inschakelen');
define('_EZCOMMENTS_MODLINKCOUNT',          'Aantal vereiste links in commentaar voordat moderatie toegestaan is');
define('_EZCOMMENTS_MODLIST',               'Woorden die moderatie inschakelen');
define('_EZCOMMENTS_BLACKLIST',             'Woorden die niet toegestaan zijn in commentaren');
define('_EZCOMMENTS_BLACKLISTNOTE',         'LET OP: als één van onderstaande woorden in een commentaar wordt gevonden, zal de module de gehele invoer negeren.');
define('_EZCOMMENTS_SEPERATELINE',          'Eén woord per regel invoeren');
define('_EZCOMMENTS_SENDINFOMAILMOD',       'Verstuur email als moderatie nodig is');
define('_EZCOMMENTS_MODMAILSUBJECT',        'Nieuw commentaar voor de website ingestuurd');
define('_EZCOMMENTS_MODMAILBODY',           'Er is een nieuw commentaar ter moderatie ingestuurd voor uw website.');
define('_EZCOMMENTS_ALWAYSMODERERATE',      'Voor al het commentaar is moderatie vereist');
define('_EZCOMMENTS_HELDFORMODERATION',     'Het ingezonden commentaar wordt zo snel mogelijk gekeurd en geplaatst. Bedankt voor uw bijdrage.');
define('_EZCOMMENTS_COMMENTBLACKLISTED',    'Het ingezonden commentaar bevat woorden die niet toegestaan zijn op deze website, en is derhalve genegeerd.');
define('_EZCOMMENTS_PROXYBLACKLIST',        'Commentaar van onveilige proxies negeren');
define('_EZCOMMENTS_DONTMODERATEIFCOMMENTED', 'Moderatie uitschakelen voor commentaren van gebruikers die al eerder een commentaar achter lieten');
define('_EZCOMMENTS_MODERATIONON',            '<strong>Let op:</strong> Moderatie voor commentaar is ingeschakeld en kan derhalve het plaatsen van commentaar vertragen. U hoeft dus niet uw commentaar opnieuw in te sturen.');

// comment statuses
define('_EZCOMMENTS_APPROVED',              'Geaccepteerd');
define('_EZCOMMENTS_PENDING',               'In de wachtrij');
define('_EZCOMMENTS_REJECTED',              'Verworpen');

// modifyconfig fieldsets
define('_EZCOMMENTS_MISCSETTINGS',          'Overige instellingen');
define('_EZCOMMENTS_NOTIFICATIONSETTINGS',  'Notificatie');
define('_EZCOMMENTS_MODERATIONSETTINGS',    'Moderatie');

// mails
define('_EZCOMMENTS_SHOW',                  'Tonen');
define('_EZCOMMENTS_MODERATE2',             'Accepteren / Verwerpen');
define('_EZCOMMENTS_DELETE',                'Verwijderen');

// comment purging options
define('_EZCOMMENTS_PURGE',                 'Commentaren verwijderen');
define('_EZCOMMENTS_PURGEPENDING',          'Alle wachtende commentaren verwijderen');
define('_EZCOMMENTS_PURGEREJECTED',         'Alle afgewezen commentaren verwijderen');

// Block
define('_EZCOMMENTS_NUMENTRIES',            'Aantal te tonen commentaren');
define('_EZCOMMENTS_SHOWUSERNAME',          'Gebruikersnaam tonen?');
define('_EZCOMMENTS_LINKUSERNAME',          'Gebruikersnaam aan profiel koppelen?');
define('_EZCOMMENTS_SHOWDATE',              'Datum tonen?');
define('_EZCOMMENTS_SHOWPENDING',           'Wachtende commentaren tonen?');
define('_EZCOMMENTS_SELECT_MODULE',         'Commentaar tonen voor onderstaande modules:');
define('_EZCOMMENTS_ALLMODULES',            'Alle');

// ip address logging
define('_EZCOMMENTS_IPADDR',                'IP-Adressen');
define('_EZCOMMENTS_LOGIPADDR',             'IP-Adressen opslaan in log');
define('_EZCOMMENTS_IPADDRNOTLOGGED',       'IP-Adres niet opgeslagen');

// multiple comment processing
define('_EZCOMMENTSWITHSELECTED',            'Met geselecteerde commentaren: ');
define('_EZCOMMENTS_APPROVE',                'goedkeuren');
define('_EZCOMMENTS_REJECT',                 'afwijzen');
define('_EZCOMMENTS_HOLD',                   'als "wachtend" markeren');

// comment stats
define('_EZCOMMENTS_STATS',                  'Commentaren statistieken');
define('_EZCOMMENTS_TOTAL',                  'Totaal aantal');
define('_EZCOMMENTS_ITEM',                   'Item-ID');
define('_EZCOMMENTS_CONFIRMDELETEMODULE',    'Verwijderen van alle commentaren voor module %m% bevestigen');
define('_EZCOMMENTS_CANCELDELETEMODULE',     'Verwijderen van alle commentaren voor module %m% afbreken');
define('_EZCOMMENTS_CONFIRMDELETEITEM',      'Verwijderen van alle commentaren voor module %m% en ID %o% bevestigen');
define('_EZCOMMENTS_CANCELDELETEITEM',       'Verwijderen van alle commentaren voor module %m% en ID %o% afbreken');

// comment typing
define('_EZCOMMENTS_TYPE',                    'Commentaar type');

// comment feeds
define('_EZCOMMENTS_FEEDS',	                  'Feeds');
define('_EZCOMMENTS_FEEDTYPE',                'Type Feed');
define('_EZCOMMENTS_FEEDCOUNT',               'Aantal te tonen commentaren in feed');
define('_EZCOMMENTS_ATOM',                    'Atom 0.3');
define('_EZCOMMENTS_RSS',                     'RSS 2.0');
define('_EZCOMMENTS_FEEDNOTE',                'Let op: Zowel de type feed als het aantal te tonen items kunnen aangepast worden door respectievelijk de parameters \'feedtype\' en \'feedcount\' aan de URL te koppelen.');

// some other users phrases in comment templates
define('_EZCOMMENTS_COMMENTSOFAR',            'Commentaren tot nu toe');
define('_EZCOMMENTS_WROTEON',                 'geschreven op');
define('_EZCOMMENTS_AT',                      'om');
define('_EZCOMMENTS_LEAVEACOMMENT',           'Laat commentaar achter');

// pager defines
define('_EZCOMMENTS_ENABLEPAGER',             'Gebruik meerdere pagina\'s (in gebruikersoverzicht)');
define('_EZCOMMENTS_COMMENTSPERPAGE',         'Aantal commentaren per pagina (in gebruikersoverzicht)');

