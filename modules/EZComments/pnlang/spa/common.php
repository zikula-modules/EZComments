<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

// new for 2.0 (Zikula-only)

define('_EZCOMMENTS_MODIFYOWNTIME',			'Número de horas en las que los usuarios están autorizados para modificar el texto de los comentarios propios. El valor -1 desactiva esta funcionalidad.');

// Search plugin
define('_EZCOMMENTS_SEARCHLENGTHHINT', 		'Los comentarios sólo pueden ser buscados con frases no menores a %minlen% y no mayores a %maxlen% caracteres!');
// ContactList integration
define('_EZCOMMENTS_USER_IGNORES_YOU',      'El usuario propietario del contenido te ha deshabilitado. No puedes publicar ningún comentario aquí!');

// admin interface
define('_EZCOMMENTS_TOOWNERORADMIN',        'El correo de notificación será enviado al propietario del contenido. Si no hay propietario conocido, será enviado al administrador del sitio.');
define('_EZCOMMENTS_OWNERUSERNAME',         'Propietario del contenido');
define('_EZCOMMENTS_UNKNOWNOWNER',          'desconocido');

// myprofile plugin
define('_EZCOMMENTS_TABTITLE',              'Panel de usuario');
define('_EZCOMMENTS_PINBOARDFOR',           'Panel de miembro');
define('_EZCOMMENTS_HOOKREGFAILED',         'Registro del Hook de EZComments hook para el módulo MyProfile falló');
define('_EZCOMMENTS_PINBOARDDEACT',         'El usuario ha desactivado el panel para su cuenta');
define('_EZCOMMENTS_PINBOARDENTRYS',        'Entradas del panel');
define('_EZCOMMENTS_REGISTERTOVIEW',        'Las entradas del panel sólo son visibles para usuarios registrados y con sesión activa.');
// my account panel
define('_EZCOMMENTS_MANAGEMYCOMMENTS',      'Mis comentarios');

define('_EZCOMMENTS_ILLEGALSTATUS',         'estado incorrecto');
define('_EZCOMMENTS_ANONNAMEMISSING',       'Falta el nombre del usuario anónimo ');
define('_EZCOMMENTS_ANONMAILMISSING',       'Falta el correo electrónico del usuario anónimo o es inválido');
define('_EZCOMMENTS_ANONWEBSITEINVALID',    'El sitio Web del usuario anónimo es inválido');
define('_EZCOMMENTS_SENDMEBACK',            'Enviarme el contenido comentado al finalizar');
define('_EZCOMMENTS_MISSINGVALUE',          'valor perdido');
// end of new defines






define('_EZCOMMENTS',                       'Comentarios');

// admin interface
define('_EZCOMMENTS_ONLYREG',               'Sólo usuarios registrados pueden publicar comentarios.');
define('_EZCOMMENTS_GOTOREG',               'regístrate/inicia sesión');
define('_EZCOMMENTS_ADD',                   'Enviar comentario');
define('_EZCOMMENTS_DEL',                   'Borrar este comentario');
define('_EZCOMMENTS_COMMENT_ADD',           'Añadir un nuevo comentario');
define('_EZCOMMENTS_COMMENT_ANSWER',        'Responder');
define('_EZCOMMENTS_CREATED',               'Comentario añadido');
define('_EZCOMMENTS_DELETED',               'Comentario borrado');
define('_EZCOMMENTS_FAILED',                'Error Interno');
define('_EZCOMMENTS_RULES',                 'Define las reglas para tus comentarios aquí');
define('_EZCOMMENTS_ADMIN',                 'Administración de EZComments');
define('_EZCOMMENTS_ADMIN_MAIN',            'Ver comentarios');
define('_EZCOMMENTS_SENDINFOMAIL',          'Enviar correo cuando haya un comentario nuevo');
define('_EZCOMMENTS_OK',                    'Aceptar');
define('_EZCOMMENTS_LASTCOMMENTS',          'Los últimos %c% comentarios');
define('_EZCOMMENTS_USERNAME',              'Usuario');
define('_EZCOMMENTS_MODULE',                'Módulo');
define('_EZCOMMENTS_TEMPLATE',              'Plantilla por defecto');
define('_EZCOMMENTS_ALLCOMMENTS',           'Todos los %s% comentarios');
define('_EZCOMMENTS_EDIT',                  'Edita comentario');
define('_EZCOMMENTS_ITEMSPERPAGE',          'Comentarios por página (vista de admin)');
define('_EZCOMMENTS_ALLOWANONUSERSETINFO',  'Permitir usuarios no registrados especificar sus datos');

// user errors/status messages
define('_EZCOMMENTS_EMPTYCOMMENT',          'Error! El comentario está vacío');

//cleanup of orphaned comments
define('_EZCOMMENTS_CLEANUP_NOTHINGTODO',   'No hay comentarios huérfanos');
define('_EZCOMMENTS_CLEANUP_GOBACK',        'Regresar');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',       'Esta funcionalidad te permite borrar comentarios que están en la base de datos para módulos removidos.');
define('_EZCOMMENTS_CLEANUP_LABEL',         'Seleccionar módulo:');
define('_EZCOMMENTS_CLEANUP_GO',            'Borrar todos los comentarios de este módulo');
define('_EZCOMMENTS_CLEANUP',               'Borrar comentarios huérfanos');

//comment migration
define('_EZCOMMENTS_MIGRATE_EXPLAIN',       'Importar comentarios de otros módulos');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO',   'No hay plugins de migración disponibles');
define('_EZCOMMENTS_MIGRATE_GOBACK',        'Regresar');
define('_EZCOMMENTS_MIGRATE_LABEL',         'Migrar:');
define('_EZCOMMENTS_MIGRATE_GO',            'Iniciar migración');
define('_EZCOMMENTS_MIGRATE',               'Migrar Comentarios');

//errors/status meesages for init script
define('_EZCOMMENTS_FAILED1',               'Error creando tabla');
define('_EZCOMMENTS_FAILED2',               'Error creando hook');
define('_EZCOMMENTS_FAILED3',               'Error borrando tabla');
define('_EZCOMMENTS_FAILED4',               'Error borrando hook');
define('_EZCOMMENTS_FAILED5',               'Falló la actualización de la Tabla');

// e-mail messages
define('_EZCOMMENTS_MAILSUBJECT',           'Se recibió un nuevo comentario');
define('_EZCOMMENTS_MAILBODY',              'Se recibió un nuevo comentario');
define('_EZCOMMENTS_SHOW',                  'Mostrar');
define('_EZCOMMENTS_MODERATE2',             'Moderar');
define('_EZCOMMENTS_DELETE',                'Eliminar');

//useful phrases for the user templates
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
define('_EZCOMMENTS_TOP',                   'Arriba');
define('_EZCOMMENTS_BOTTOM',                'Abajo');
define('_EZCOMMENTS_COMMENTSOFAR',          'Comentarios hasta ahora');
define('_EZCOMMENTS_WROTEON',               'escrito el');
define('_EZCOMMENTS_AT',                    'a las');
define('_EZCOMMENTS_LEAVEACOMMENT',         'Deja un comentario');
define('_EZCOMMENTS_COMMENT_FROM',          'por');
define('_EZCOMMENTS_COMMENT_ON',            'en');

// comment form
define('_EZCOMMENTS_ANONNAME',              'Nombre');
define('_EZCOMMENTS_ANONMAIL',              'Correo electrónico (no será publicado)');
define('_EZCOMMENTS_ANONWEBSITE',           'Sitio web'); 
define('_EZCOMMENTS_AUTHOR',                'Autor');
define('_EZCOMMENTS_COMMENT',               'Comentario');

// navigation bar
define('_EZCOMMENTS_DISPLAY',               'Mostrar');
define('_EZCOMMENTS_FLAT',                  'Plano');
define('_EZCOMMENTS_NEWESTFIRST',           'Los nuevos primero');
define('_EZCOMMENTS_OLDESTFIRST',           'Los antiguos primero');
define('_EZCOMMENTS_ORDER',                 'Orden');
define('_EZCOMMENTS_THREADED',              'Por hilos');

// search plugin
define('_EZCOMMENTS_SEARCH',                'Buscar comentarios');
define('_EZCOMMENTS_NOCOMMENTSFOUND',       'No se encontraron comentarios que coincidieran con tu búsqueda');

// comment moderation
define('_EZCOMMENTS_MODERERATE',              'Habilitar moderación de comentarios');
define('_EZCOMMENTS_MODLINKCOUNT',            'Número de enlaces en el comentario antes de moderar');
define('_EZCOMMENTS_MODLIST',                 'Palabras para activar la moderación');
define('_EZCOMMENTS_BLACKLINKCOUNT',          'Número de enlaces en el comentario antes de enviar a lista negra');
define('_EZCOMMENTS_BLACKLIST',               'Palabras no permitidas en comentarios');
define('_EZCOMMENTS_BLACKLISTNOTE',           'Nota: Los comentarios que contengan palabras listadas aquí serán ignorados or completo del módulo de comentarios');
define('_EZCOMMENTS_SEPERATELINE',            'Separar múltiples palabras con nuevas líneas');
define('_EZCOMMENTS_SENDINFOMAILMOD',         'Enviar correo notificando comentarios que requieran ser moderados');
define('_EZCOMMENTS_MODMAILSUBJECT',          'Nuevo comentario para tu sitio');
define('_EZCOMMENTS_MODMAILBODY',             'Se envió un nuevo comentario a tu sitio que requiere ser moderado');
define('_EZCOMMENTS_ALWAYSMODERERATE',        'Todos los comentarios requieren ser moderados');
define('_EZCOMMENTS_HELDFORMODERATION',       'Tu comentario se ha retenido par ser moderado y será revisado pronto');
define('_EZCOMMENTS_COMMENTBLACKLISTED',      'Tu comentario contiene contenido inaceptable y ha sido rechazado');
define('_EZCOMMENTS_PROXYBLACKLIST',          'Rechazar comentarios de proxies inseguros');
define('_EZCOMMENTS_DONTMODERATEIFCOMMENTED', 'No requerir moderación para comentarios de usuarios que han comentado antes');
define('_EZCOMMENTS_MODERATIONON',            '<strong>Ten en cuenta</strong> que los comentarios son moderados y es posible que la publicación de tu comentario se retrase. No hay necesidad de reenviar tu comentario.');
define('_EZCOMMENTS_APPLYMODRULES',           'Re-aplicar reglas de moderación');
define('_EZCOMMENTS_APPLYMODRULESINTRO',      'Esta página te permite volver a aplicar las reglas de moderación a todos los comentarios existentes permitiendo volver a verificarlos en caso que hayas cambiado las reglas de moderación.');
define('_EZCOMMENTS_APPLYMODRULESALL',        'Aplicar reglas de moderación a todos los comentarios');
define('_EZCOMMENTS_APPLYMODRULESSTATUS',     'Comentarios con el estado');
define('_EZCOMMENTS_TOBEREJECTED',            'Los siguientes comentarios serán rechazados');
define('_EZCOMMENTS_TOBEMODERATED',           'Los siguientes comentarios requerirán moderacion');
define('_EZCOMMENTS_APPLYNEWRULES',           'Aplicar nuevas reglas');

// comment statuses
define('_EZCOMMENTS_APPROVED',              'Aprobado');
define('_EZCOMMENTS_PENDING',               'Pendiente');
define('_EZCOMMENTS_REJECTED',              'Rechazado');
define('_EZCOMMENTS_SPAM',                  'Spam');

// modifyconfig fieldsets
define('_EZCOMMENTS_MISCSETTINGS',          'Misceláneo');
define('_EZCOMMENTS_MODERATIONSETTINGS',    'Moderación');
define('_EZCOMMENTS_NOTIFICATIONSETTINGS',  'Notificación');

// comment purging options
define('_EZCOMMENTS_PURGE',                 'Purgar comentarios');
define('_EZCOMMENTS_PURGEPENDING',          'Purgar todos los comentarios pendientes');
define('_EZCOMMENTS_PURGEREJECTED',         'Purgar todos los comentarios rechazados');

// Block
define('_EZCOMMENTS_NUMENTRIES',            'Número de comentarios a mostrar');
define('_EZCOMMENTS_SHOWUSERNAME',          'Mostrar nombre de usuario?');
define('_EZCOMMENTS_LINKUSERNAME',          'Enlazar nombre de usuario al perfil?');
define('_EZCOMMENTS_SHOWDATE',              'Mostrar fecha?');
define('_EZCOMMENTS_SHOWPENDING',           'Mostrar comentarios pendientes?');
define('_EZCOMMENTS_SELECT_MODULE',         'Mostrar comentarios para el siguiente módulo');
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
define('_EZCOMMENTS_TOTAL',                  'Total de comentarios');
define('_EZCOMMENTS_ITEM',                   'ID');
define('_EZCOMMENTS_CONFIRMDELETEMODULE',    'Confirma el borrar todos los comentarios del módulo \'%m%\'');
define('_EZCOMMENTS_CANCELDELETEMODULE',     'Cancelar el borrado de todos los comentarios del módulo \'%m%\'');
define('_EZCOMMENTS_CONFIRMDELETEITEM',      'Confirmar el borrado de todos los comentarios del objeto \'%o%\' en el módulo \'%m%\'');
define('_EZCOMMENTS_CANCELDELETEITEM',       'Cancelar el borrado de todos los comentarios del objeto \'%o%\' en el módulo \'%m%\'');

// comment typing
define('_EZCOMMENTS_TYPE',                    'Tipo de comentario');

// comment feeds
define('_EZCOMMENTS_FEEDS',                   'Feeds');
define('_EZCOMMENTS_FEEDTYPE',                'Tipo de feed');
define('_EZCOMMENTS_FEEDCOUNT',               'Número de elementos a mostrar en el feed');
define('_EZCOMMENTS_ATOM',                    'Atom 0.3');
define('_EZCOMMENTS_RSS',                     'RSS 2.0');
define('_EZCOMMENTS_FEEDNOTE',                'Nota: Tanto el tipo de feed y el número de feeds pueden ser sobreescritos usando los parámetros feedtype y feedcount añadido al URL del feed');

// pager defines
define('_EZCOMMENTS_ENABLEPAGER',             'Habilitar paginador (vista de usuario)');
define('_EZCOMMENTS_COMMENTSPERPAGE',         'Comentarios por página (vista de usuario)');

// status filter
define('_EZCOMMENTS_FILTERBYSTATUS',          'Filtrar por estado :');
define('_EZCOMMENTS_SHOWALL',                 'Mostrar todos los comentarios');

// akismet
define('_EZCOMMENTS_AKISMET',                 'Servicio de detección de spam Akismet');
define('_EZCOMMENTS_AKISMETNOTE',             '<a href="http://akismet.com/">Akismet</a> es un servicio de detección de spam que puede, en muchos casos, eliminar el spam de comentarios y trackback. Para usar Akismet necesitas instalar y configurar el <a href="http://code.zikula.org/ezcomments/downloads">módulo Akismet</a>.');
define('_EZCOMMENTS_ENABLEAKISMET',           'Habilitar akismet');
define('_EZCOMMENTS_AKISMETSTATUS',           'Estado a aplicar a los comentarios marcados como spam por akismet');

// username requried for guest comments
define('_EZCOMMENTS_ANON_NAME',               'Se requiere nombre para usuarios no registrados');
define('_EZCOMMENTS_ANON_NAME_FORM',          '(requerido para usuarios no registrados)');
define('_EZCOMMENTS_ANON_NAME_REJECT',        'Error! El nombre es requerido. Comentario rechazado');
