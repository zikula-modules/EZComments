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
 * @author      Mark West <markwest at postnuke dot com>
 * @author      Jean-Michel Vedrine
 * @version     1.4
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
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
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column'];

    $sql = "CREATE TABLE $EZCommentstable (
              $EZCommentscolumn[id]           int(11)      NOT NULL auto_increment,
              $EZCommentscolumn[modname]      varchar(64)  NOT NULL default '',
              $EZCommentscolumn[objectid]     text         NOT NULL default '',
              $EZCommentscolumn[url]          text         NOT NULL default '',
              $EZCommentscolumn[date]         datetime     default NULL,
              $EZCommentscolumn[uid]          int(11)      default '0',
              $EZCommentscolumn[comment]      text         NOT NULL,
              $EZCommentscolumn[subject]      text         NOT NULL default '',
              $EZCommentscolumn[replyto]      int(11)      NOT NULL default '-1',
              $EZCommentscolumn[anonname]     varchar(255) NOT NULL default '',
              $EZCommentscolumn[anonmail]     varchar(255) NOT NULL default '',
              $EZCommentscolumn[status]       int(4)       NOT NULL default 0,
              $EZCommentscolumn[ipaddr]       varchar(85)  NOT NULL default '',
              $EZCommentscolumn[type]         varchar(64)  NOT NULL default '',
              $EZCommentscolumn[anonwebsite]  varchar(255) NOT NULL default '',
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
    
    // register  delete Hook (Timo)
    // TODO: Check the Hook's name!
    if (!pnModRegisterHook('item',
                           'delete',
                           'API',
                           'EZComments',
                           'admin',
                           'deletebyitem')) {
        pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED2);
        return false;
    }

    // register the module delete hook
    if (!pnModRegisterHook('module',
                           'remove',
                           'API',
                           'EZComments',
                           'admin',
                           'deletemodule')) {
        pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED2);
    }    

    pnModSetVar('EZComments', 'MailToAdmin',    false);
    pnModSetVar('EZComments', 'migrated',       serialize(array('dummy')));
    pnModSetVar('EZComments', 'template',       'Plain');
    pnModSetVar('EZComments', 'itemsperpage',   25);
    pnModSetVar('EZComments', 'anonusersinfo',  false);
    pnModSetVar('EZComments', 'moderation',     0);
    pnModSetVar('EZComments', 'modlist',        '');
    pnModSetVar('EZComments', 'blacklist',      '');
    pnModSetVar('EZComments', 'modlinkcount',   2);
    pnModSetVar('EZComments', 'blacklinkcount', 5);
    pnModSetVar('EZComments', 'moderationmail', false);
    pnModSetVar('EZComments', 'alwaysmoderate', false);
    pnModSetVar('EZComments', 'proxyblacklist', false);
	pnModSetVar('EZComments', 'logip',          false);
	pnModSetVar('EZComments', 'dontmoderateifcommented', false);
	pnModSetVar('EZComments', 'feedtype', 'rss');
	pnModSetVar('EZComments', 'feedcount', '10');
	pnModSetVar('EZComments', 'enablepager', false);
	pnModSetVar('EZComments', 'commentsperpage', '25');
    pnModSetVar('EZComments', 'akismet', false);
    pnModSetVar('EZComments', 'apikey', '');

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
    // setup the db connection
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column'];

    if ($oldversion == '0.1') {
        // new functionality: MailToAdmin
        pnModSetVar('EZComments', 'MailToAdmin', false);
        // new functionality: Migration
        pnModSetVar('EZComments', 'migrated', serialize(array()));

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

    if ($oldversion == '0.2') {
        pnModDelVar('EZComments', 'smartypath');

        // register  delete Hook (Timo)
        // TODO: Check the Hook's name!
        if (!pnModRegisterHook('item',
                               'delete',
                               'API',
                               'EZComments_delete',
                               'admin',
                               'deletebyitem')) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED2);
            return false;
        }
        $oldversion = '0.3 CVS';
    }
    if ($oldversion == '0.3' || $oldversion == '0.3 CVS') {
        // the hook bug for different hook types has been resolved so lets fix that
        // in this version. We need to unregister the old delete hook, register the
        // new hook and re-create the hooks for all modules hooked to EZComments.
        // get all modules hooked to ezcomments
        $hookedmodules = pnModAPIFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname'=> 'EZComments'));
        if (!pnModUnregisterHook('item',
                                 'delete',
                                 'API',
                                 'EZComments_delete',
                                 'admin',
                                 'deletebyitem')) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED4);
            return false;
        }
        if (!pnModRegisterHook('item',
                               'delete',
                               'API',
                               'EZComments',
                               'admin',
                               'deletebyitem')) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED2);
            return false;
        }
        foreach ($hookedmodules as $modname => $hooktype) {
            // disable the hooks for this module
            pnModAPIFunc('Modules', 'admin', 'disablehooks', array('callermodname' => $modname, 'hookmodname' => 'EZComments'));
            // re-enable the hooks for this module
            pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => $modname, 'hookmodname' => 'EZComments'));
        }
        $oldversion = '0.4';
    }
    if ($oldversion == '0.4') {
        pnModSetVar('EZComments', 'template', 'AllOnOnePage');
        $oldversion = '0.5';
    }
    if ($oldversion == '0.5') {
        pnModSetVar('EZComments', 'itemsperpage', 25);
        // get all modules hooked to ezcomments
        $hookedmodules = pnModAPIFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname'=> 'EZComments'));
        if (!pnModRegisterHook('module',
                               'remove',
                               'API',
                               'EZComments',
                               'admin',
                               'deletemodule')) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED2);
        }    
        foreach ($hookedmodules as $modname => $hooktype) {
            // disable the hooks for this module
            pnModAPIFunc('Modules', 'admin', 'disablehooks', array('callermodname' => $modname, 'hookmodname' => 'EZComments'));
            // re-enable the hooks for this module
            pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => $modname, 'hookmodname' => 'EZComments'));
        }
        $oldversion = '0.6';
    }

    if ($oldversion == '0.6') {
        pnModSetVar('EZComments', 'anonusersinfo', false);
        // Add additional for unregistered users info
        $sql = "ALTER TABLE $EZCommentstable 
                        ADD $EZCommentscolumn[anonname] varchar(255) NOT NULL default '',
                        ADD $EZCommentscolumn[anonmail] varchar(255) NOT NULL default ''";
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED5 . ': ' . $dbconn->ErrorMsg());
            return false;
        }
        $oldversion = '0.7';
    }

    if ($oldversion == '0.7') {
        // Add additional field for published status flag on comments
        $sql = "ALTER TABLE $EZCommentstable 
                        ADD $EZCommentscolumn[status] int(4) NOT NULL default 0";
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED5 . ': ' . $dbconn->ErrorMsg());
            return false;
        }
        // add additional vars for comment moderation, blacklists and link count for moderation
        pnModSetVar('EZComments', 'moderation', 0);
        pnModSetVar('EZComments', 'modlist', '');
        pnModSetVar('EZComments', 'blacklist', '');
        pnModSetVar('EZComments', 'modlinkcount', 2);
        pnModSetVar('EZComments', 'moderationmail', false);
        pnModSetVar('EZComments', 'alwaysmoderate', false);
        pnModSetVar('EZComments', 'proxyblacklist', false);
		$oldversion = '0.8';
    }

    if ($oldversion == '0.8') {
		pnModSetVar('EZComments', 'dontmoderateifcommented', false);
        pnModSetVar('EZComments', 'logip', false);
        // Add additional for unregistered users info
        $sql = "ALTER TABLE $EZCommentstable 
                        ADD $EZCommentscolumn[ipaddr] varchar(85) NOT NULL default ''";
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED5 . ': ' . $dbconn->ErrorMsg());
            return false;
        }
		$oldversion = '0.9';
    }

    if ($oldversion == '0.9') {
        // Add additional field for published status flag on comments
        $sql = "ALTER TABLE $EZCommentstable 
                        ADD $EZCommentscolumn[type] varchar(64) NOT NULL default ''";
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED5 . ': ' . $dbconn->ErrorMsg());
            return false;
        }
		$oldversion = '1.0';
    }

	if ($oldversion == '1.0') {
		pnModSetVar('EZComments', 'feedtype', 'rss');
		pnModSetVar('EZComments', 'feedcount', '10');
		$oldversion = '1.1';
	}

    if ($oldversion == '1.1') {
        // Add additional for unregistered users info
        $sql = "ALTER TABLE $EZCommentstable 
                        ADD $EZCommentscolumn[anonwebsite] varchar(255) NOT NULL default ''";
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED5 . ': ' . $dbconn->ErrorMsg());
            return false;
        }
		$oldversion = '1.2';
    }

	if ($oldversion == '1.2') {
		pnModSetVar('EZComments', 'enablepager', false);
		pnModSetVar('EZComments', 'commentsperpage', '25');
		$oldversion = '1.3';
	}

	if ($oldversion == '1.3') {
        pnModSetVar('EZComments', 'blacklinkcount', 5);
        pnModSetVar('EZComments', 'akismet', false);
        pnModSetVar('EZComments', 'apikey', '');
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
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
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

    if (!pnModUnregisterHook('item',
                             'delete',
                             'API',
                             'EZComments',
                             'admin',
                             'deletebyitem')) {
        pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED4);
        return false;
    }

    if (!pnModUnregisterHook('module',
                             'remove',
                             'API',
                             'EZComments',
                             'admin',
                             'deletemodule')) {
        pnSessionSetVar('errormsg', _EZCOMMENTS_FAILED4);
        return false;
    }

	// delete all module vars for the ezcomments module
    pnModDelVar('EZComments');

    // Deletion successful
    return true;
} 

?>