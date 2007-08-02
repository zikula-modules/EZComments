<?php
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Author: Jörg Napp, http://postnuke.lottasophie.de
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

define('_EZCOMMENTS',                       'Comentarios');
define('_EZCOMMENTS_NOAUTH',                'No tienes acceso a los comentarios.');
define('_EZCOMMENTS_ONLYREG',               'Sólo los usuarios con sesión iniciada pueden comentar.');
define('_EZCOMMENTS_GOTOREG',               'registrarse/iniciar sesión');
define('_EZCOMMENTS_ADD',                   'Agregar');
define('_EZCOMMENTS_DEL',                   'Borrar este comentario');
define('_EZCOMMENTS_COMMENT_ADD',           'Agregar un nuevo comentario');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Respuesta');
define('_EZCOMMENTS_COMMENT_FROM',          'por');
define('_EZCOMMENTS_COMMENT_ON',            'en');
define('_EZCOMMENTS_CREATED',               'Comentario agregado');
define('_EZCOMMENTS_DELETED',               'Comentario borrado');
define('_EZCOMMENTS_FAILED',                'Error Interno');
define('_EZCOMMENTS_NODIRECTACCESS',        'No hay acceso directo a este módulo');
define('_EZCOMMENTS_RULES',                 'Define las reglas para tus comentarios aquí');

define('_EZCOMMENTS_ADMIN',                 'Administración de EZComments');
define('_EZCOMMENTS_ADMIN_MAIN',            'Administración Principal de EZComments');
define('_EZCOMMENTS_SENDINFOMAIL',          'Enviar correo cuando haya un comentario nuevo');
define('_EZCOMMENTS_OK',                    'Aceptar');
define('_EZCOMMENTS_LASTCOMMENTS',          'Los últimos comentarios');
define('_EZCOMMENTS_USERNAME',              'Usuario');
define('_EZCOMMENTS_MODULE',                'Módulo');
define('_EZCOMMENTS_TEMPLATE',              'Plantilla predeterminada');
define('_EZCOMMENTS_DELETESELECTED',        'Borrar comentarios seleccionados');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'No hay comentarios huérfanos');
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Regresar');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'Esta funcionalidad te permite borrar comentarios que están en la base de datos para módulos removidos.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Seleccionar módulo:');
define('_EZCOMMENTS_CLEANUP_GO',            'Borrar todos los comentarios de este módulo');
define('_EZCOMMENTS_CLEANUP',               'Borrar comentarios huérfanos');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Importar comentarios de otros módulos');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'No hay plugins de migración disponibles');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Regresar');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Migrar:');
define('_EZCOMMENTS_MIGRATE_GO',            'Iniciar migración');
define('_EZCOMMENTS_MIGRATE',               'Migrar Comentarios');

define('_EZCOMMENTS_FAILED1',               'Error creando tabla');
define('_EZCOMMENTS_FAILED2',               'Error creando hook');
define('_EZCOMMENTS_FAILED3',               'Error borrando tabla');
define('_EZCOMMENTS_FAILED4',               'Error borrando hook');
define('_EZCOMMENTS_FAILED5',               'Falló la actualización de la Tabla');

define('_EZCOMMENTS_MAILSUBJECT',           'Se recibió un nuevo comentario');
define('_EZCOMMENTS_MAILBODY',              'Se recibió un nuevo comentario');
define('_EZCOMMENTS_POSTED',                'escrito');
define('_EZCOMMENTS_REG_SINCE',             'registrado');
define('_EZCOMMENTS_NOTREG',                'no registrado'); 
define('_EZCOMMENTS_STATUS',                'Estado');
define('_EZCOMMENTS_OFFLINE',               'Desconectado');
define('_EZCOMMENTS_ONLINE',                'Conectado');
define('_EZCOMMENTS_PROFILE',               'Perfil');
define('_EZCOMMENTS_SEND_PM',               'enviar MP');
define('_EZCOMMENTS_FROM',                  'Localización');
define('_EZCOMMENTS_SUBJECT',               'Asunto');

define('_EZCOMMENTS_EDIT',                  'Editar Comentario');
define('_EZCOMMENTS_ITEMSPERPAGE',          'Artículos por página');

define('_EZCOMMENTS_AUTHOR',                'Autor');
define('_EZCOMMENTS_COMMENT',               'Comentario');

// navigation bar
define('_EZCOMMENTS_DISPLAY',               'Mostrar');
define('_EZCOMMENTS_FLAT',                  'Plano');
define('_EZCOMMENTS_NEWESTFIRST',           'Los más nuevos primero');
define('_EZCOMMENTS_OLDESTFIRST',           'Los más viejos primero');
define('_EZCOMMENTS_ORDER',                 'Orden');
define('_EZCOMMENTS_THREADED',              'Por hilos');

define('_EZCOMMENTS_ALLOWANONUSERSETINFO',  'Permitir usuarios no registrados especificar información de usuario');
define('_EZCOMMENTS_ANONNAME',              'Nombre');
define('_EZCOMMENTS_ANONMAIL',              'Dirección de correo electrónico');

define('_EZCOMMENTS_SEARCH',                'Buscar comentarios');
define('_EZCOMMENTS_NOCOMMENTSFOUND',       'Ningún comentario concuerda con tu búsqueda');

define('_EZCOMMENTS_TOP',                   'Arriba');
define('_EZCOMMENTS_BOTTOM',                'Abajo');

// comment moderation
define('_EZCOMMENTS_MODERERATE',            'Habilitar comentarios moderados');
define('_EZCOMMENTS_MODLINKCOUNT',          'Número de enlaces en el comentario antes de moderar');
define('_EZCOMMENTS_MODLIST',               'Palabras para activar la moderación');
define('_EZCOMMENTS_BLACKLIST',             'Palabras no permitidas en comentarios');
define('_EZCOMMENTS_BLACKLISTNOTE',         'Nota: Los comentarios que contengan palabras listadas aquí serán ignorados or completo del módulo de comentarios');
define('_EZCOMMENTS_SEPERATELINE',          'Separar múltiples palabras con nuevas líneas');
define('_EZCOMMENTS_SENDINFOMAILMOD',       'Enviar comentarios que requieran ser moderados');
define('_EZCOMMENTS_MODMAILSUBJECT',        'Nuevo comentario para tu sitio');
define('_EZCOMMENTS_MODMAILBODY',           'Se envió un nuevo comentario a tu sitio que requiere ser moderado');
define('_EZCOMMENTS_ALWAYSMODERERATE',      'Todos los comentarios requieren ser moderados');
define('_EZCOMMENTS_HELDFORMODERATION',     'Tu comentario se ha retenido par ser moderado y será revisado pronto');
define('_EZCOMMENTS_COMMENTBLACKLISTED',    'Tu comentario contiene contenido inaceptable y ha sido rechazado');
define('_EZCOMMENTS_PROXYBLACKLIST',        'Rechazar comentarios de proxies inseguros');
define('_EZCOMMENTS_DONTMODERATEIFCOMMENTED', 'No requerir moderación para comentarios de usuarios que han comentado antes');

// comment statuses
define('_EZCOMMENTS_APPROVED',              'Aprobado');
define('_EZCOMMENTS_PENDING',               'Pendiente');
define('_EZCOMMENTS_REJECTED',              'Rechazado');

// modifyconfig fieldsets
define('_EZCOMMENTS_MISCSETTINGS',          'Misceláneo');
define('_EZCOMMENTS_MODERATIONSETTINGS',    'Moderación');
define('_EZCOMMENTS_NOTIFICATIONSETTINGS',  'Notificación');

// mails
define('_EZCOMMENTS_SHOW',                  'Mostrar');
define('_EZCOMMENTS_MODERATE2',             'Moderar');
define('_EZCOMMENTS_DELETE',                'Eliminar');

// comment purging options
define('_EZCOMMENTS_PURGE',                 'Purgar comentarios');
define('_EZCOMMENTS_PURGEPENDING',          'Purgar todos los comentarios pendientes');
define('_EZCOMMENTS_PURGEREJECTED',         'Purgar todos los comentarios rechazados');

// Block
define('_EZCOMMENTS_NUMENTRIES',            'Número de comentarios a mostrar');
define('_EZCOMMENTS_SHOWUSERNAME',          '¿Mostrar nombre de usuario?');
define('_EZCOMMENTS_LINKUSERNAME',          '¿Enlazar nombre de usuario al perfil?');
define('_EZCOMMENTS_SHOWDATE',              '¿Mostrar fecha?');
define('_EZCOMMENTS_SELECT_MODULE',         'Mostrar comentarios para el módulo siguiente');
define('_EZCOMMENTS_ALLMODULES',            'Todos');

// ip address logging
define('_EZCOMMENTS_IPADDR',                'Dirección IP');
define('_EZCOMMENTS_LOGIPADDR',             'Grabar direcciones IP');
define('_EZCOMMENTS_IPADDRNOTLOGGED',       'Dirección IP no grabada');

// multiple comment processing
define('_EZCOMMENTSWITHSELECTED',            'Con los comentarios seleccionados: ');
define('_EZCOMMENTS_APPROVE',                'Aprobar');
define('_EZCOMMENTS_REJECT',                 'Rechazar');
define('_EZCOMMENTS_HOLD',                   'Esperar');

// comment stats
define('_EZCOMMENTS_STATS',                  'Estadísticas de comentarios');
define('_EZCOMMENTS_TOTAL',                  'Comentarios totales');
define('_EZCOMMENTS_ITEM',                   'ID del Item');
define('_EZCOMMENTS_CONFIRMDELETEMODULE',    'Confirma el borrar todos los comentarios del módulo \'%m%\'');
define('_EZCOMMENTS_CANCELDELETEMODULE',     'Cancelar el borrado de todos los comentarios del módulo \'%m%\'');
define('_EZCOMMENTS_CONFIRMDELETEITEM',      'Confirmar el borrado de todos los comentarios del objeto \'%o%\' en el módulo \'%m%\'');
define('_EZCOMMENTS_CANCELDELETEITEM',       'Cancelar el borrado de todos los comentarios del objeto \'%o%\' en el módulo \'%m%\'');

// comment typing
define('_EZCOMMENTS_TYPE',                    'Tipo de comentario');

// comment feeds
define('_EZCOMMENTS_FEEDS',                       'Feeds');
define('_EZCOMMENTS_FEEDTYPE',                'Tipo de feed');
define('_EZCOMMENTS_FEEDCOUNT',               'Número de elementos a mostrar en el feed');
define('_EZCOMMENTS_ATOM',                    'Atom 0.3');
define('_EZCOMMENTS_RSS',                     'RSS 2.0');
define('_EZCOMMENTS_FEEDNOTE',                'Nota: Tanto el tipo de feed y el número de feeds pueden ser sobreescritos usando los parámetros feedtype y feedcount añadido al URL del feed');

// some other users phrases in comment templates
define('_EZCOMMENTS_COMMENTSOFAR',            'Comentarios hasta ahora');
define('_EZCOMMENTS_WROTEON',                 'escrito el');
define('_EZCOMMENTS_AT',                      'a las');
define('_EZCOMMENTS_LEAVEACOMMENT',           'Deja un comentario');

// pager defines
define('_EZCOMMENTS_ENABLEPAGER',             'Habilitar paginador (vista de usuario)');
define('_EZCOMMENTS_COMMENTSPERPAGE',         'Comentarios por página (vista de usuario)');


