<?php
/**
 * $Id$
 * 
 * * EZComments *
 * 
 * Attach comments to any module calling hooks
 * 
 *  * Purpose *
 * 
 * Migration of old comments to new ones
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
 * Do the migration
 * 
 * With this function, the actual migration is done.
 * 
 * @return   boolean   true on sucessful migration, false else
 * @since    0.2
 */
function EZComments_migrate()
{
	// Security check
	if (!pnSecAuthAction(0, 'EZComments::', "::", ACCESS_ADMIN)) {
		pnSessionSetVar('errormsg', 'News migration: Not Admin');
		return false;
	} 
	// Load API
	if (!pnModAPILoad('EZComments', 'user')) {
		pnSessionSetVar('errormsg', 'News migration: Unable to load API ');
		return false;
	} 

	// Get datbase setup
	list($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$EZCommentstable  = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column']; 

	$Commentstable = $pntable['comments'];
	$Commentscolumn = $pntable['comments_column'];

	$Usertable = $pntable['users'];
	$Usercolumn = $pntable['users_column'];

	$sql = "SELECT $Commentscolumn[tid], 
	               $Commentscolumn[sid],
	               $Commentscolumn[date], 
				   $Usercolumn[uid], 
				   $Commentscolumn[comment],
				   $Commentscolumn[subject],
				   $Commentscolumn[pid]
             FROM  $Commentstable LEFT JOIN $Usertable
			   ON $Commentscolumn[name] = $Usercolumn[uname]";

	$result = $dbconn->Execute($sql); 
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', 'News migration: DB Error');
		return false;
	} 

	// array to rebuild the patents
	$comments = array(0 => array('newid' => -1));
	
	// loop through the old comments and insert them one by one into the DB
	for (; !$result->EOF; $result->MoveNext()) {
		list($tid, $sid, $date, $uid, $comment, $subject, $replyto) = $result->fields;

	    $id = pnModAPIFunc('EZComments',
            			   'user',
        	    		   'create',
        		    	   array('modname'  => 'News',
      	        	    		 'objectid' => pnVarPrepForStore($sid),
      			            	 'url'	    => 'modules.php?op=modload&name=News&file=article&sid=' . $sid,
              				     'comment'  => $comment,
    							 'subject'  => $subject,
		    					 'uid'      => $uid));

    	if (!$id) {
			pnSessionSetVar('errormsg', 'News migration: Error creating comment');
		    return false;
	    } 
		$comments[$tid] = array('newid' => $id, 
		                        'pid'   => $replyto);
		
	} 
	$result->Close(); 

	// rebuild the links to the parents
	foreach ($comments as $k=>$v) {
		if ($k!=0) {
			$sql = "UPDATE $EZCommentstable 
			           SET $EZCommentscolumn[replyto]=" . $comments[$v['pid']]['newid'] . "
			         WHERE $EZCommentscolumn[id]=$v[newid]";
		
			$result = $dbconn->Execute($sql); 
		}
	}
	pnSessionSetVar('errormsg', 'News migration successful');
    return true;
} 
?>