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

/**
 * get comments for a specific item inside a module
 * 
 * This function provides the main user interface to the comments
 * module. 
 * 
 * @param $args['modname'] Name of the module to get comments for
 * @param $args['objectid'] ID of the item to get comments for
 * @returns array
 * @return array of items, or false on failure
 */ 
function EZComments_userapi_getall($args)
{
	extract($args);

	if (!isset($modname) || !isset($objectid)) {
		pnSessionSetVar('errormsg', _MODARGSERROR);
		return false;
	} 
	$modname = pnVarPrepForStore($modname);
	$objectid = pnVarPrepForStore($objectid);

	$items = array(); 
	// Security check
	if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:", ACCESS_READ)) {
		return $items;
	} 
	// Get datbase setup
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$EZCommentstable = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column']; 
	// Get items
	$sql = "SELECT $EZCommentscolumn[id],
				   $EZCommentscolumn[date],
                   $EZCommentscolumn[uid],
                   $EZCommentscolumn[comment]
            FROM $EZCommentstable
            WHERE $EZCommentscolumn[modname] = '$modname'
              AND $EZCommentscolumn[objectid] = '$objectid'
            ORDER BY $EZCommentscolumn[date]";

	$result = $dbconn->Execute($sql); 
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
		list($id, $date, $uid, $comment) = $result->fields;
		if (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$id", ACCESS_READ)) {
			$items[] = array('id' => $id,
				'date' => $date,
				'uid' => $uid,
				'comment' => $comment);
		} 
	} 
	// All successful database queries produce a result set, and that result
	// set should be closed when it has been finished with
	$result->Close(); 
	// Return the items
	return $items;
} 

/**
 * create a new comment
 * 
 * This function creates a new comment and returns its ID. Access checking is done.
 * 
 * @param $args['modname'] Name of the module to create comments for
 * @param $args['objectid'] ID of the item to create comments for
 * @param $args['comment'] The comment itself
 * @returns int
 * @return ID of new comment on success, false on failure
 */ 
function EZComments_userapi_create($args)
{
	extract($args);

	if ((!isset($modname)) ||
			(!isset($objectid)) ||
			(!isset($comment))) {
		pnSessionSetVar('errormsg', _MODARGSERROR);
		return false;
	} 
	// Security check
	if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:", ACCESS_COMMENT)) {
		pnSessionSetVar('errormsg', _EZCOMMENTS_NOAUTH);
		return false;
	} 
	// Get datbase setup
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$EZCommentstable = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column']; 
	// Get next ID in table
	$nextId = $dbconn->GenId($EZCommentstable);

	$uid = pnUserGetVar('uid');

	list($modname, 
	     $objectid,
		 $url,
		 $uid, 
		 $comment) = pnVarPrepForStore($modname, 
		                               $objectid, 
									   $url,
									   $uid, 
									   $comment); 
	// Add item
	$sql = "INSERT INTO $EZCommentstable (
			  $EZCommentscolumn[id],
              $EZCommentscolumn[modname],
              $EZCommentscolumn[objectid],
              $EZCommentscolumn[url],
              $EZCommentscolumn[date],
              $EZCommentscolumn[uid],
              $EZCommentscolumn[comment])
            VALUES (
              '$nextId',
			  '$modname',
			  '$objectid',
			  '$url',
			  now(),
			  '$uid',
			  '$comment')";
	$dbconn->Execute($sql); 
	// Check for an error with the database code
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _CREATEFAILED);
		return false;
	} 
	// Get the ID of the item that we inserted.
	$id = $dbconn->PO_Insert_ID($EZCommentstable, $EZCommentscolumn['id']); 
	
	// Inform admin about new comment
	if (pnModGetVar('EZComments', 'MailToAdmin')) {
		$headers =  'From:' . pnConfigGetVar('sitename') . '<' . pnConfigGetVar('adminmail') . ">\n";
		// Send it as HTML mail
    	$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		// Who wants to receive as well?
		//$headers .= "cc: birthdayarchive@php.net\n";

		$subject = _EZCOMMENTS_MAILSUBJECT;
		$body    = _EZCOMMENTS_MAILBODY . ":\n" . $comment . "\n\n\nLink:" . $url;
		pnmail(pnConfigGetVar('adminmail'), 
               $subject,
			   $body, 
	       	   $headers);
	}
	
	// pnModCallHooks('item', 'create', $tid, 'tid');
	return $id;
} 

/**
 * delete a comment
 * 
 * This function deletes a given comment. Access checking is done.
 * 
 * @param $args['id'] ID of the comment to delete
 * @return true on success, false on failure
 */  
function EZComments_userapi_delete($args)
{
	extract($args);

	if (!isset($id)) {
		pnSessionSetVar('errormsg', _MODARGSERROR);
		return false;
	} 

	//credits to markwest for providing this 
	$CommentDetails = pnModAPIFunc('EZComments',
					 			   'user',
			  					   'get',
								   compact('id'));
	if (!$CommentDetails) {
		pnSessionSetVar('errormsg', _MODARGSERROR);
		return false;
	}
	extract($CommentDetails);

	// Security check
	if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$id", ACCESS_DELETE)) {
		pnSessionSetVar('errormsg', _EZCOMMENTS_NOAUTH);
		return false;
	} 

	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$EZCommentstable = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column']; 
	// Delete the item
	$sql = "DELETE FROM $EZCommentstable
            WHERE $EZCommentscolumn[id] = " . pnVarPrepForStore($id);
	$dbconn->Execute($sql);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _DELETEFAILED);
		return false;
	} 
	// pnModCallHooks('item', 'delete', $tid, ''); 
	return true;
} 

/**
 * get comments for a specific item inside a module
 * 
 * This function provides the main user interface to the comments
 * module. 
 * 
 * @param $args['id'] ID of the comment
 * @returns array
 * @return details, or false on failure
 */ 
function EZComments_userapi_get($args)
{
	extract($args);
	if (!isset($id)) {
		pnSessionSetVar('errormsg', _MODARGSERROR);
		return false;
	} 
	// Get datbase setup
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$EZCommentstable = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column']; 
	// Get items
	$sql = "SELECT $EZCommentscolumn[modname],
                   $EZCommentscolumn[objectid],
                   $EZCommentscolumn[url],
                   $EZCommentscolumn[date],
                   $EZCommentscolumn[uid],
                   $EZCommentscolumn[comment]
            FROM $EZCommentstable
            WHERE $EZCommentscolumn[id] = '$id'";

	$result = $dbconn->Execute($sql); 
	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _GETFAILED);
		return false;
	} 

	if ($result->EOF) {
		pnSessionSetVar('errormsg', _GETFAILED);
		return false;
	} 
	// Put items into result array.  Note that each item is checked
	// individually to ensure that the user is allowed access to it before it
	// is added to the results array
	list($modname, 
		 $objectid,
		 $url,
	     $date, 
		 $uid, 
		 $comment) = $result->fields;
	if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$id", ACCESS_READ)) {
		return false;
	} 
	 
	// All successful database queries produce a result set, and that result
	// set should be closed when it has been finished with
	$result->Close(); 
	// Return the items
	return compact('modname', 
		           'objectid',
		           'url',
	               'date', 
		           'uid', 
		           'comment');
} 

?>