<?php 
// $Id$
// LICENSE
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Jörg Napp, http://postnuke.lottasophie.de
// ----------------------------------------------------------------------

/**
 * initialise the EZComments module
 */
function EZComments_init()
{ 
	// Create tables
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$EZCommentstable = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column'];

	$sql = "CREATE TABLE $EZCommentstable (
              $EZCommentscolumn[id]       int(11)     NOT NULL auto_increment,
              $EZCommentscolumn[modname]  varchar(64) NOT NULL default '',
              $EZCommentscolumn[objectid] text        NOT NULL default '',
              $EZCommentscolumn[url]      text        NOT NULL default '',
              $EZCommentscolumn[date]     datetime    default NULL,
              $EZCommentscolumn[uid]      int(11)     default '0',
              $EZCommentscolumn[comment]  text        NOT NULL,
              PRIMARY KEY(id)
              ) COMMENT='Table for EZComments'";
	$dbconn->Execute($sql);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED1);
		return false;
	} 
	// register Hook
	if (!pnModRegisterHook('item',
				'display',
			  	'GUI',
				'EZComments',
				'user',
				'view')) {
		pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED2);
		return false;
	} 
	
	// Note that filenames may contain backslashes as separators. 
	// We need to convert them to slashes before doing anything else...
//	pnModSetVar('EZComments', 'smartypath', pnVarPrepForStore(str_replace('\\', '/', dirname(__FILE__) 
//											. '/pnclass/Smarty/')));
	pnModSetVar('EZComments', 'smartypath', dirname(__FILE__) 
						. DIRECTORY_SEPARATOR . 'pnclass'
						. DIRECTORY_SEPARATOR . 'Smarty'
						. DIRECTORY_SEPARATOR);
	// Initialisation successful
	return true;
} 

/**
 * upgrade the EZComments module from an old version
 */
function EZComments_upgrade($oldversion)
{ 
	// This is the first release, we do not have older versions.
	return true;
} 

/**
 * delete the EZComments module
 */
function EZComments_delete()
{
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();
	$sql = "DROP TABLE $pntable[EZComments]";
	$dbconn->Execute($sql);
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED3);
		return false;
	} 

	if (!pnModUnregisterHook('item',
				 'display',
				 'GUI',
				 'EZComments',
				 'user',
				 'view')) {
		pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED4);
		return false;
	} 
	pnModDelVar('EZComments', 'smartypath');
	
	// Deletion successful
	return true;
} 
?>
