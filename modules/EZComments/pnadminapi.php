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
 * get all comments
 * 
 * This function provides the main user interface to the comments
 * module. 
 * 
 * @param $args['startnum'] First comment
 * @param $args['numitems'] number of comments
 * @returns array
 * @return array of items, or false on failure
 */ 
function EZComments_adminapi_getall($args)
{
	extract($args);
    if (!isset($startnum)) {
        $startnum = 1;
    }
    if (!isset($numitems)) {
        $numitems = -1;
    }

	$items = array(); 
	// Security check
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_OVERVIEW)) {
		return $items;
	} 
	
	// Get datbase setup
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$EZCommentstable = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column']; 
	// Get items
	$sql = "SELECT $EZCommentscolumn[id],
                   $EZCommentscolumn[modname],
                   $EZCommentscolumn[objectid],
                   $EZCommentscolumn[url],
				   $EZCommentscolumn[date],
                   $EZCommentscolumn[uid],
                   $EZCommentscolumn[comment]
            FROM $EZCommentstable
            ORDER BY $EZCommentscolumn[date] DESC";

    $result = $dbconn->SelectLimit($sql, $numitems, $startnum-1);			
	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _GETFAILED);
		return false;
	} 
	// Put items into result array.  Note that each item is checked
	// individually to ensure that the user is allowed access to it before it
	// is added to the results array
	for (; !$result->EOF; $result->MoveNext()) {
		list($id, 
			 $modname,
			 $objectid,
			 $url,
		     $date, 
			 $uid, 
			 $comment) = $result->fields;

		if (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$id", ACCESS_READ)) {
			$items[] = array('id'       => $id,
							 'modname'  => $modname,
							 'objectid' => $objectid,
							 'url'	    => $url,
				             'date'     => $date,
				             'uid'      => $uid,
				             'comment'  => $comment);
		} 
	} 

	$result->Close(); 
	// Return the items
	return $items;
} 


function EZComments_adminapi_countitems()
{
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_OVERVIEW)) {
		return false;
	} 
	
	// Get datbase setup
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$EZCommentstable = $pntable['EZComments'];
    $sql = "SELECT COUNT(1)
            FROM $EZCommentstable";
    $result = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }
    list($numitems) = $result->fields;
    $result->Close();
    return $numitems;
}


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
	
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$table = $pntable['EZComments'];
	$column = &$pntable['EZComments_column']; 

    $sql = "SELECT    $column[modname]
            FROM      $table
			GROUP BY  $column[modname]";
    $result = $dbconn->Execute($sql);

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
	
	
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$table = $pntable['EZComments'];
	$column = &$pntable['EZComments_column']; 

    $sql = "DELETE FROM $table
			WHERE $column[modname] = '$args[module]'";
			
    $result = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
	echo $dbconn->ErrorMsg;
        return false;
    }
	$result->Close(); 

	return true;
}


?>