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

define('_EZCOMMENTS',                       'Commentaires');
define('_EZCOMMENTS_NOAUTH',                'Pas d\'accès aux commentaires.');
define('_EZCOMMENTS_ONLYREG',               'Seuls les utilisateurs enregistrés peuvent ajouter un commentaire.');
define('_EZCOMMENTS_GOTOREG',               'S\'enregister/S\'identifier');
define('_EZCOMMENTS_ADD',                   'Ajouter');
define('_EZCOMMENTS_DEL',                   'Effacer ce commentaire');
define('_EZCOMMENTS_COMMENT_ADD',           'Ajouter un nouveau commentaire');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Répondre');
define('_EZCOMMENTS_COMMENT_FROM',          'par');
define('_EZCOMMENTS_COMMENT_ON',            'le');
define('_EZCOMMENTS_CREATED',               'Commentaire ajouté');
define('_EZCOMMENTS_DELETED',               'Commentaire effacé');
define('_EZCOMMENTS_FAILED',                'Erreur interne');
define('_EZCOMMENTS_NODIRECTACCESS',        'Pas d\'accès direct à ce module');
define('_EZCOMMENTS_RULES',                 'Définissez les règles pour vos commentaires ici');

define('_EZCOMMENTS_ADMIN',                 'Administration EZComments');
define('_EZCOMMENTS_ADMIN_MAIN',            'Administration principale EZComments');
define('_EZCOMMENTS_SENDINFOMAIL',          'envoyer un mail en cas de nouveau commentaire');
define('_EZCOMMENTS_OK',                    'Accepter');
define('_EZCOMMENTS_LASTCOMMENTS',          'Les derniers commentaires');
define('_EZCOMMENTS_USERNAME',              'Utilisateur');
define('_EZCOMMENTS_MODULE',                'Module');
define('_EZCOMMENTS_COMMENT',               'Commentaire');
define('_EZCOMMENTS_DELETESELECTED',        'Effacer commentaire selectée');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'Pas de commentaire orphelin');
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Retour');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'Cette fonctionnalité vous permet de supprimer les commentaires qui sont dans la base de données pour les modules enlevés.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Sélectionner le module :');
define('_EZCOMMENTS_CLEANUP_GO',            'Effacer tous les commentaires pour ce module');
define('_EZCOMMENTS_CLEANUP',               'Effacer les commentaires orphelins');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Importer les commentaires à partir d\'autres modules');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'Pas de plugin de migration disponible');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Retour');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Migrer :');
define('_EZCOMMENTS_MIGRATE_GO',            'Démarrer la migration');
define('_EZCOMMENTS_MIGRATE',               'Migrer les Commentaires');

define('_EZCOMMENTS_FAILED1',               'Erreur lors de la création d\'une table');
define('_EZCOMMENTS_FAILED2',               'Erreur lors de la création d\'un hook');
define('_EZCOMMENTS_FAILED3',               'Erreur lors de la suppression d\'une table');
define('_EZCOMMENTS_FAILED4',               'Erreur lors de la suppression d\'un hook');
define('_EZCOMMENTS_FAILED5',               'La mise à jour d\'une table a échoué');

define('_EZCOMMENTS_MAILSUBJECT',           'Un nouveau commentaire a été entré'); 
define('_EZCOMMENTS_MAILBODY',              'Un nouveau commentaire a été entré'); 

define('_EZCOMMENTS_POSTED',                'posté'); 
define('_EZCOMMENTS_REG_SINCE',             'enregistré'); 
define('_EZCOMMENTS_NOTREG',                'pas enregistré'); 
define('_EZCOMMENTS_STATUS',                'Status'); 
define('_EZCOMMENTS_OFFLINE',               'déconnecté'); 
define('_EZCOMMENTS_ONLINE',                'en ligne'); 
define('_EZCOMMENTS_PROFILE',               'Profil'); 
define('_EZCOMMENTS_SEND_PM',               'Envoi MP'); 
define('_EZCOMMENTS_FROM',                  'Location'); 
define('_EZCOMMENTS_SUBJECT',               'Sujet');

define('_EZCOMMENTS_EDIT',                  'Modifier le commentaire');
define('_EZCOMMENTS_ITEMSPERPAGE',          'Nombre de commentaires par page');

define('_EZCOMMENTS_AUTHOR',                'Auteur');
define('_EZCOMMENTS_COMMENT', 			    'Commentaire');

// navigation bar
define('_EZCOMMENTS_DISPLAY',               'Affichage');
define('_EZCOMMENTS_FLAT',                  'A plat');
define('_EZCOMMENTS_NEWESTFIRST',           'Les plus récents d\'abord');
define('_EZCOMMENTS_OLDESTFIRST',           'Les plus anciens d\'abord');
define('_EZCOMMENTS_ORDER',                 'Ordre');
define('_EZCOMMENTS_THREADED',              'Par discussions');

define('_EZCOMMENTS_ALLOWANONUSERSETINFO',  'Permettre aux invités de laisser des informations personnelles');
define('_EZCOMMENTS_ANONNAME',              'Nom');
define('_EZCOMMENTS_ANONMAIL',              'Addresse E-mail');

define('_EZCOMMENTS_SEARCH',                'Chercher dans les commentaires');
define('_EZCOMMENTS_NOCOMMENTSFOUND',       'Aucun commentaire ne correspond à votre recherche');

define('_EZCOMMENTS_TOP',                   'Début');
define('_EZCOMMENTS_BOTTOM',                'Fin');

// comment moderation
define('_EZCOMMENTS_MODERERATE',            'Activer la modération des commentaires');
define('_EZCOMMENTS_MODLINKCOUNT',          'Nombre de liens dans un commentaire pour provoquer la modération');
define('_EZCOMMENTS_MODLIST',               'Mots-clés provoquant la modération');
define('_EZCOMMENTS_BLACKLIST',             'Mots-clés interdits dans les commentaires');
define('_EZCOMMENTS_BLACKLISTNOTE',         'Note: Les commentaires contenant des mots-clés de cette liste seront complètement ignorés');
define('_EZCOMMENTS_SEPERATELINE',          'Séparez les mots par des retour à la ligne');
define('_EZCOMMENTS_SENDINFOMAILMOD',       'Envoi par email des commentaires nécessitant la modération');
define('_EZCOMMENTS_MODMAILSUBJECT',        'Nouveau commentaire sur votre site.');
define('_EZCOMMENTS_MODMAILBODY',           'Un nouveau commentaire nécessitant une modération a été inscrit sur votre site.');
define('_EZCOMMENTS_ALWAYSMODERERATE',      'Modérer tous les commentaires');
define('_EZCOMMENTS_HELDFORMODERATION',     'Votre commentaire est en attente de modération et sera examiné prochainement');
define('_EZCOMMENTS_COMMENTBLACKLISTED',    'Votre commentaire a un contenu inacceptable et a été rejeté');
define('_EZCOMMENTS_PROXYBLACKLIST',        'Blacklist comments from insecure proxies');

// comment statuses
define('_EZCOMMENTS_APPROVED',              'Approuvé');
define('_EZCOMMENTS_PENDING',               'En attente');
define('_EZCOMMENTS_REJECTED',              'Rejeté');

// modifyconfig fieldsets
define('_EZCOMMENTS_MISCSETTINGS',          'Divers');
define('_EZCOMMENTS_MODERATIONSETTINGS',    'Modération');
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
define('_EZCOMMENTS_BLOCKNUMITEMS',         'Nombre de commentaires à afficher :');
define('_EZCOMMENTS_BLOCKSHOWUNAME',        'Afficher le nom des utilisateurs ?');
define('_EZCOMMENTS_BLOCKLNKTOPROFILE',     'Lien avec le profil des utilisateurs ?');
define('_EZCOMMENTS_BLOCKSHOWDATE',         'Afficher la date ?');
define('_EZCOMMENTS_SELECT_MODULE',         'Show comments for the following module');
define('_EZCOMMENTS_ALLMODULES',            'All');

?>
