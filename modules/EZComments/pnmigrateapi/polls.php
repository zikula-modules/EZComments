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
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
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
function EZComments_migrateapi_polls()
{
    // Security check
    if (!pnSecAuthAction(0, 'EZComments::', "::", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', 'Polls migration: Not Admin');
        return false;
    } 

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable  = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 

    $Commentstable = $pntable['pollcomments'];
    $Commentscolumn = $pntable['pollcomments_column'];

    $Usertable = $pntable['users'];
    $Usercolumn = $pntable['users_column'];

    $sql = "SELECT $Commentscolumn[tid], 
                   $Commentscolumn[pollid],
                   $Commentscolumn[date], 
                   $Usercolumn[uid], 
                   $Commentscolumn[comment],
                   $Commentscolumn[subject],
                   $Commentscolumn[pid]
             FROM  $Commentstable LEFT JOIN $Usertable
               ON $Commentscolumn[name] = $Usercolumn[uname]";

    $result =& $dbconn->Execute($sql); 
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', 'Polls migration: DB Error');
        return false;
    } 

    // array to rebuild the patents
    $comments = array(0 => array('newid' => -1));
    
    // loop through the old comments and insert them one by one into the DB
    for (; !$result->EOF; $result->MoveNext()) {
        list($tid, $sid, $date, $uid, $comment, $subject, $replyto) = $result->fields;

        // set the correct user id for anonymous users
        if (empty($uid)) {
            $uid = 1;
        }

        $id = pnModAPIFunc('EZComments',
                           'user',
                           'create',
                           array('modname'  => 'Polls',
                                   'objectid' => pnVarPrepForStore($sid),
                                   'url'        => 'name=Polls&req=results&pollID=' . $pollid,
                                   'comment'  => $comment,
                                 'subject'  => $subject,
                                 'uid'      => $uid,
                                 'date'     => $date));

        if (!$id) {
            pnSessionSetVar('errormsg', 'Polls migration: Error creating comment');
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
        
            $result =& $dbconn->Execute($sql); 
        }
    }
    
    // activate the ezcomments hook for the news module
    pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'Polls', 'hookmodname' => 'EZComments'));

    pnSessionSetVar('errormsg', 'Polls migration successful');
    return true;
}

?>
