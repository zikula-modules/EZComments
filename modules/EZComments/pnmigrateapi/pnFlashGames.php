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
 * Migration of old pnflashgames comments to new ones
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
 * @author      Lee Eason <leason@pnflashgames.com>
 * @version     0.2
 * @link        http://lottasophie.sourceforge.net Support and documentation
 * @link        http://pnflashgames.com pnFlashGames Homepage
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Zikula
 * @subpackage  EZComments
 *
 * This migration file written by Lee Eason <leason@pnflashgames.com>.
 */
 

/**
 * Do the migration
 * 
 * With this function, the actual migration is done.
 * 
 * @return   boolean   true on sucessful migration, false else
 * @since    0.2
 */
function EZComments_migrateapi_pnFlashGames()
{
	// Security check
	if (!pnSecAuthAction(0, 'EZComments::', "::", ACCESS_ADMIN)) {
		return LogUtil::registerError('pnFlashGames comments migration: Not Admin');
	} 

	// Get datbase setup
    $dbconn = pnDBGetConn(true);
    $pntable = pnDBGetTables();

    $Commentstable = $pntable['pnFlashGames_comments'];
    $Commentscolumn = $pntable['pnFlashGames_comments_column'];

	$EZCommentstable  = $pntable['EZComments'];
	$EZCommentscolumn = $pntable['EZComments_column']; 

	$Usertable = $pntable['users'];
	$Usercolumn = $pntable['users_column'];

	$sql = "SELECT $Commentscolumn[gid], 
	               $Commentscolumn[uname],
	               $Commentscolumn[date],
				   $Commentscolumn[comment],
				   $Usercolumn[uid]
             FROM  $Commentstable 
             LEFT JOIN $Usertable
             	    ON $Commentscolumn[uname] = $Usercolumn[uname]";

	$result = $dbconn->Execute($sql); 
	if ($dbconn->ErrorNo() != 0) {
		return LogUtil::registerError('pnFlashGames migration: DB Error: ' . $sql . ' -- ' . mysql_error());
	} 

	// array to rebuild the parents
	$comments = array(0 => array('newid' => -1));
	
	// loop through the old comments and insert them one by one into the DB
	for (; !$result->EOF; $result->MoveNext()) {
		list($gid, $uname, $date, $comment, $uid) = $result->fields;

	    $id = pnModAPIFunc('EZComments',
            			   'user',
        	    		   'create',
        		    	   array('mod'  => 'pnFlashGames',
      	        	    		 'objectid' => pnVarPrepForStore($gid),
      			            	 'url'	    => 'index.php?module=pnFlashGames&func=display&id=' . $gid,
              				     'comment'  => $comment,
    							 'subject'  => '',
		    					 'uid'      => $uid,
								 'date'     => $date));

    	if (!$id) {
			return LogUtil::registerError('pnFlashGames comments migration: Error creating comment');
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
	LogUtil::registerStatus('pnFlashGames migration successful');
}

