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

define('_EZCOMMENTS', 		  			'Comentarios');
define('_EZCOMMENTS_NOAUTH',   			'No tienes acceso a los comentarios.');
define('_EZCOMMENTS_ONLYREG',   		'Sólo los usuarios con sesión iniciada pueden comentar.');
define('_EZCOMMENTS_GOTOREG',   		'registrarse/iniciar sesión');
define('_EZCOMMENTS_ADD', 	  			'Agregar');
define('_EZCOMMENTS_DEL',      			'Borrar este comentario');
define('_EZCOMMENTS_COMMENT_ADD', 		'Agregar un nuevo comentario');
define('_EZCOMMENTS_COMMENT_ANSWER', 	'Respuesta');
define('_EZCOMMENTS_COMMENT_FROM',  	'por');
define('_EZCOMMENTS_COMMENT_ON',    	'en');
define('_EZCCOMMENTSCREATED',			'Comentario agregado');
define('_EZCCOMMENTSDELETED', 			'Comentario borrado');
define('_EZCOMMENTS_FAILED',   			'Error Interno');
define('_EZCOMMENTS_NODIRECTACCESS',	'No hay acceso directo a este módulo');
define('_EZCOMMENTS_RULES',	            'Define las reglas para tus comentarios aquí');

define('_EZCOMMENTS_ADMIN',				'Administración de EZComments');
define('_EZCOMMENTS_ADMIN_MAIN',		'Administración Principal de EZComments');
define('_EZCOMMENTS_SENDINFOMAIL',		'Enviar correo cuando haya un comentario nuevo');
define('_EZCOMMENTS_OK', 				'Aceptar');
define('_EZCOMMENTS_LASTCOMMENTS', 		'Los últimos comentarios');
define('_EZCOMMENTS_USERNAME', 			'Usuario');
define('_EZCOMMENTS_MODULE', 			'Módulo');
define('_EZCOMMENTS_TEMPLATE',          'Plantilla predeterminada');

define('_EZCOMMENTS_CLEANUP_NOTHINGTODO', 'No hay comentarios huérfanos');
define('_EZCOMMENTS_CLEANUP_GOBACK',      'Regresar');
define('_EZCOMMENTS_CLEANUP_EXPLAIN',     'Esta funcionalidad te permite borrar comentarios que están en la base de datos para módulos removidos.');
define('_EZCOMMENTS_CLEANUP_LABEL',       'Seleccionar módulo:');
define('_EZCOMMENTS_CLEANUP_GO',          'Borrar todos los comentarios de este módulo');
define('_EZCOMMENTS_CLEANUP',             'Borrar comentarios huérfanos');

define('_EZCOMMENTS_MIGRATE_EXPLAIN',     'Importar comentarios de otros módulos');
define('_EZCOMMENTS_MIGRATE_NOTHINGTODO', 'No hay plugins de migración disponibles');
define('_EZCOMMENTS_MIGRATE_GOBACK',      'Regresar');
define('_EZCOMMENTS_MIGRATE_LABEL',       'Migrar:');
define('_EZCOMMENTS_MIGRATE_GO',          'Iniciar migración');
define('_EZCOMMENTS_MIGRATE',             'Migrar Comentarios');

define('_EZCOMMENTS_FAILED1', 			'Error creando tabla');
define('_EZCOMMENTS_FAILED2', 			'Error creando hook');
define('_EZCOMMENTS_FAILED3', 			'Error borrando tabla');
define('_EZCOMMENTS_FAILED4', 			'Error borrando hook');
define('_EZCOMMENTS_FAILED5', 			'Falló la actualización de la Tabla');

define('_EZCOMMENTS_MAILSUBJECT',		'Se recibió un nuevo comentario'); 
define('_EZCOMMENTS_MAILBODY',  		'Se recibió un nuevo comentario'); 

// Steffen 01/2005
define('_EZCOMMENTS_POSTED',  			'escrito'); 
define('_EZCOMMENTS_REG_SINCE',  		'registrado'); 
define('_EZCOMMENTS_STATUS',  			'Estado'); 
define('_EZCOMMENTS_OFFLINE',  			'Desconectado'); 
define('_EZCOMMENTS_ONLINE',  			'Conectado'); 
define('_EZCOMMENTS_PROFILE',  			'Perfil'); 
define('_EZCOMMENTS_SEND_PM',  			'enviar MP'); 
define('_EZCOMMENTS_FROM',  			'Localización'); 

define('_EZCOMMENTS_SUBJECT',           'Asunto');
define('_EZCOMMENTS_EDIT',              'Editar Comentario');
define('_EZCOMMENTS_DELETE',            'Borrar Comentario');
define('_EZCOMMENTS_ITEMSPERPAGE',      'Artículos por página');

define('_EZCOMMENTS_AUTHOR',            'Autor');
define('_EZCOMMENTS_COMMENT',           'Comentario');

// navigation bar
define('_EZCOMMENTS_DISPLAY',            'Mostrar');
define('_EZCOMMENTS_FLAT',               'Plano');
define('_EZCOMMENTS_NEWESTFIRST',        'Los más nuevos primero');
define('_EZCOMMENTS_OLDESTFIRST',        'Los más viejos primero');
define('_EZCOMMENTS_ORDER',              'Orden');
define('_EZCOMMENTS_THREADED',           'Por hilos');

define('_EZCOMMENTS_ALLOWANONUSERSETINFO', 'Permitir usuarios no registrados especificar información de usuario');
define('_EZCOMMENTS_ANONNAME',             'Nombre');
define('_EZCOMMENTS_ANONMAIL',             'Dirección de correo electrónico'); 

define('_EZCOMMENTS_SEARCH',               'Buscar comentarios');
define('_EZCOMMENTS_NOCOMMENTSFOUND',      'Ningún comentario concuerda con tu búsqueda');

define('_EZCOMMENTS_TOP',                  'Arriba');
define('_EZCOMMENTS_BOTTOM',               'Abajo');

?>
