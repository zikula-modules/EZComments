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
 * @author      Joerg Napp <jnapp@users.sourceforge.net>
 * @version     0.2
 * @link        http://lottasophie.sourceforge.net Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

 
/**
 * initialise the EZComments module
 * 
 * This function initializes the module to be used. it creates tables,
 * registers hooks,...
 * 
 * @return boolean true on success, false otherwise.
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
		      $EZCommentscolumn[subject]  text        NOT NULL default '',
			  $EZCommentscolumn[replyto]  int(11)     NOT NULL default '-1',
              PRIMARY KEY(id)
              ) COMMENT='Table for EZComments'";
	$dbconn->Execute($sql);

	if ($dbconn->ErrorNo() != 0) { 
		pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED1  . ': ' . $dbconn->ErrorMsg());
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
	pnModSetVar('EZComments', 'smartypath', dirname(__FILE__) 
						. DIRECTORY_SEPARATOR . 'pnclass'
						. DIRECTORY_SEPARATOR . 'Smarty'
						. DIRECTORY_SEPARATOR);
	pnModSetVar('EZComments', 'MailToAdmin', false);
	// Initialisation successful
	return true;
} 


/**
 * upgrade the EZComments module from an old version
 * 
 * This function upgrades the module to be used. It updates tables,
 * registers hooks,...
 * 
 * @return boolean true on success, false otherwise.
 */
function EZComments_upgrade($oldversion)
{ 
    if ($oldversion == '0.1') {
		// new functionality: MailToAdmin
		pnModSetVar('EZComments', 'MailToAdmin', false);
		// new functionality: Migration
		pnModSetVar('EZComments', 'migrated', serialize(array()));

		list($dbconn) = pnDBGetConn();
		$pntable = pnDBGetTables();

		$EZCommentstable = $pntable['EZComments'];
		$EZCommentscolumn = &$pntable['EZComments_column'];

		// Rename the table fom nuke_EZComments to nuke_ezcomments
		$oldtable = pnConfigGetVar('prefix') . '_EZComments';
		$sql = "ALTER TABLE $oldtable RENAME $EZCommentstable";
		$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) {
			pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED5 . ': ' . $dbconn->ErrorMsg());
			return false;
		}
		
		// Add additional fields used for threading		
		$sql = "ALTER TABLE $EZCommentstable 
		                ADD $EZCommentscolumn[subject] text    NOT NULL default '',
						ADD $EZCommentscolumn[replyto] int(11) NOT NULL default '-1'";
		$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) {
			pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED5 . ': ' . $dbconn->ErrorMsg());
			return false;
		}

		$oldversion = '0.2';
	}
	return true;
} 


/**
 * delete the EZComments module from an old version
 * 
 * This function deletes the module to be used. It deletes tables,
 * registers hooks,...
 * 
 * @return boolean true on success, false otherwise.
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
	pnModDelVar('EZComments', 'MailToAdmin');
	pnModDelVar('EZComments', 'migrated');
	
	// Deletion successful
	return true;
} 
?>
