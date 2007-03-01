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
 * EZComments_adminapi_getUsedModules()
 * 
 * This function returns an array of the modules
 * for which a comment is available. This is used
 * for the "clean-up" feature that eliminates
 * orphaned comments after a module is deletd.
 * 
 * @return list of all modules used 
 */
function EZComments_adminapi_getUsedModules()
{
    if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        return false;
    } 
    
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $table = $pntable['EZComments'];
    $column = &$pntable['EZComments_column']; 

    $sql = "SELECT    $column[modname]
            FROM      $table
            GROUP BY  $column[modname]";
    $result =& $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    $mods = array();
    for (; !$result->EOF; $result->MoveNext()) {
        list($mods[]) = $result->fields;
    } 
    $result->Close(); 

    return $mods;
}

/**
 * EZComments_adminapi_deleteall()
 * 
 * Delete all comments for a given module. Used to clean
 * up orphaned comments.
 * 
 * @param $args[module] the module for which to delete for
 * @return boolean sucess status
 **/
function EZComments_adminapi_deleteall($args)
{
    if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        return false;
    } 

    if (!isset($args['module'])) { 
        return false;
    }
    
    
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $table = $pntable['EZComments'];
    $column = &$pntable['EZComments_column']; 

    $sql = "DELETE FROM $table
            WHERE $column[modname] = '$args[module]'";
            
    $result =& $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
    echo $dbconn->ErrorMsg;
        return false;
    }
    $result->Close(); 

    return true;
}

/**
 * EZComments_adminapi_deletebyitem()
 *
 * Delete all comments for a given item. Used to clean
 * up orphaned comments.
 *
 * @author Timo (Numerobis)
 * @since 0.3
 * @param $args[mod] the module for which to delete for
 * @return boolean sucess status
 **/
function EZComments_adminapi_deletebyitem($args)
{
    if (!isset($args['objectid'])) {
        return false;
    } 
	if (!isset($args['mod'])) {
	    $mod = pnModGetName();
	} else {
		$mod = $args['mod'];
	}
    $objectid = $args['objectid'];
    
    if (!pnSecAuthAction(0, 'EZComments::', "$mod:$objectid:", ACCESS_ADMIN)) {
        return false;
    } 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $table = $pntable['EZComments'];
    $column = &$pntable['EZComments_column'];

    $sql = "DELETE FROM $table
            WHERE $column[modname] = '$mod' AND $column[objectid] = '$objectid'";

    $result =& $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        echo $dbconn->ErrorMsg;
        return false;
    } 
    $result->Close();

    return true;
} 

/**
 * delete an item
 * 
 * @param    $args['id']    ID of the item
 * @return   bool           true on success, false on failure
 */
function EZComments_adminapi_delete($args)
{
    // Get arguments from argument array 
    extract($args);

    // Argument check
    if ((isset($id) && !is_numeric($id))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.
    $item = pnModAPIFunc('EZComments', 'user', 'get', array('id' => $id));

    if (!$item) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Security check 
    if (!pnSecAuthAction(0, 'EZComments::', "::$id", ACCESS_DELETE)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $table = $pntable['EZComments'];
    $column = &$pntable['EZComments_column'];

    // Delete the item
    $sql = "DELETE FROM $table
            WHERE $column[id] = '" . (int)pnVarPrepForStore($id) ."'";
    $dbconn->Execute($sql);

    // Check for an error with the database code
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted an item.
    pnModCallHooks('item', 'delete', $id, array('module' => 'EZComments'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * update an item
 * 
 * @param    $args['id']         the ID of the item
 * @param    $args['subject']    the new subject of the item
 * @param    $args['comment']    the new text of the item
 * @return   bool             true on success, false on failure
 */
function EZComments_adminapi_update($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($subject)) ||
        (!isset($comment)) ||
        (isset($id) && !is_numeric($id)) ||
        (isset($status) && !is_numeric($status))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // optional arguments
    if (!isset($anonname)) $anonname = '';
    if (!isset($anonmail)) $anonmail = '';
    if (!isset($anonwebsite)) $anonwebsite = '';

    // Get the item
    $item = pnModAPIFunc('EZComments', 'user', 'get', array('id' => $id));

    if (!$item) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Security check.
    if (!pnSecAuthAction(0, 'EZComments::', "::$id", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 

    list($subject, $comment, $id, $status) = pnVarPrepForStore($subject, $comment, $id, $status);

    // Update the item
    $sql = "UPDATE $EZCommentstable
            SET $EZCommentscolumn[subject] = '".$subject."',
                $EZCommentscolumn[comment] = '".$comment."',
                $EZCommentscolumn[anonname] = '".$anonname."',
                $EZCommentscolumn[anonmail] = '".$anonmail."',
                $EZCommentscolumn[anonwebsite] = '".$anonwebsite."',
                $EZCommentscolumn[status] = '".(int)$status."'
            WHERE $EZCommentscolumn[id] = '".(int)$id."'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }

    // Let any hooks know that we have updated an item.
    pnModCallHooks('item', 'update', $id, array('module' => 'EZComments'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * clean up comments for a removed module
 * 
 * @param    $args['extrainfo']   array extrainfo array
 * @return   array extrainfo array
 */
function EZComments_adminapi_deletemodule($args)
{
    // Get arguments from argument array
    extract($args);

    // optional arguments
    if (!isset($extrainfo)) {
        $extrainfo = array();
    }

    // When called via hooks, the module name may be empty, so we get it from
    // the current module
    if (empty($extrainfo['module'])) {
        $mod = pnModGetName();
    } else {
        $mod = $extrainfo['module'];
    }

    // Database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 

    // Get items
    $sql = "DELETE FROM $EZCommentstable
            WHERE $EZCommentscolumn[modname] = '" . pnVarPrepForStore($mod) . "'";
    $result =& $dbconn->Execute($sql);

    return $extrainfo;
}

/**
 * delete an item
 * 
 * @param    $args['purgerejected']    Purge all rejected comments
 * @param    $args['purgepending']     Purge all pending comments
 * @return   bool           true on success, false on failure
 */
function EZComments_adminapi_purge($args)
{
    // Get arguments from argument array 
    extract($args);

    // Argument check
    if (!isset($purgerejected) && !isset($purgepending)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Security check 
    if (!pnSecAuthAction(0, 'EZComments::', "::", ACCESS_DELETE)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $table = $pntable['EZComments'];
    $column = &$pntable['EZComments_column'];

    if ((bool)$purgerejected) {
        $sql = "DELETE FROM $table
                WHERE $column[status] = '2'";
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _DELETEFAILED);
            return false;
        }
    }

    if ((bool)$purgepending) {
        $sql = "DELETE FROM $table
                WHERE $column[status] = '1'";
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _DELETEFAILED);
            return false;
        }
    }

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * update an item status
 * 
 * @param    $args['id']         the ID of the item
 * @param    $args['status']     the new status of the item
 * @return   bool             true on success, false on failure
 */
function EZComments_adminapi_updatestatus($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (isset($id) && !is_numeric($id) && isset($status) && !is_numeric($status)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get the comment
    $item = pnModAPIFunc('EZComments', 'user', 'get', array('id' => $id));

    if (!$item) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Security check.
    if (!pnSecAuthAction(0, 'EZComments::', "::$id", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 

    // 
    list($id, $status) = pnVarPrepForStore($id, $status);

    // Update the item
    $sql = "UPDATE $EZCommentstable
            SET $EZCommentscolumn[status] = '".(int)$status."'
            WHERE $EZCommentscolumn[id] = '".(int)$id."'";
    $dbconn->Execute($sql);

    // Check for an error with the database code
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }

    // Let any hooks know that we have updated an item.
    pnModCallHooks('item', 'update', $id, array('module' => 'EZComments'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * count items
 * 
 * maintained for backwards compatability
 * simply passes parameters onto the user api
 */
function ezcomments_adminapi_countitems($args)
{
    return pnModAPIFunc('EZComments', 'user', 'countitems', $args);
}

?>