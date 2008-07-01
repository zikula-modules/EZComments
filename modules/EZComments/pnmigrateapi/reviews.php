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
 * @author Joerg Napp <jnapp@users.sourceforge.net>
 * @author Mark West <markwest at zikula dot org>
 * @author Jean-Michel Vedrine
 * @author Florian Schieﬂl <florian.schiessl at ifs-net.de>
 * @author Frank Schummertz
 * @version 1.6
 * @link http://code.zikula.org/ezcomments/ Support and documentation
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package Zikula_3rdParty_Modules
 * @subpackage EZComments
 */ 

/**
 * Do the migration
 * 
 * With this function, the actual migration is done.
 * 
 * @return   boolean   true on sucessful migration, false else
 * @since    0.6
 */
function EZComments_migrateapi_reviews()
{
    // Security check
    if (!pnSecAuthAction(0, 'EZComments::', "::", ACCESS_ADMIN)) {
        return LogUtil::registerError('News migration: Not Admin');
        return false;
    } 

    // Get datbase setup
    $dbconn = pnDBGetConn(true);
    $pntable = pnDBGetTables();

    $EZCommentstable  = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 

    $Commentstable = $pntable['reviews_comments'];
    $Commentscolumn = $pntable['reviews_comments_column'];

    $Usertable = $pntable['users'];
    $Usercolumn = $pntable['users_column'];

    // note: there's nothing we can do with the score......
    $sql = "SELECT $Commentscolumn[cid], 
                   $Commentscolumn[rid],
                   $Commentscolumn[date], 
                   $Usercolumn[uid], 
                   $Commentscolumn[comments],
                   $Commentscolumn[score]
             FROM  $Commentstable LEFT JOIN $Usertable
               ON $Commentscolumn[userid] = $Usercolumn[uname]";

    $result = $dbconn->Execute($sql); 
    if ($dbconn->ErrorNo() != 0) {
        return LogUtil::registerError('News migration: DB Error');
    } 

    // array to rebuild the patents
    $comments = array(0 => array('newid' => -1));
    
    // loop through the old comments and insert them one by one into the DB
    for (; !$result->EOF; $result->MoveNext()) {
        list($cid, $rid, $date, $uid, $comment, $score) = $result->fields;

        $id = pnModAPIFunc('EZComments',
                           'user',
                           'create',
                           array('mod'  => 'Reviews',
                                   'objectid' => pnVarPrepForStore($rid),
                                   'url'        => 'index.php?name=Reviews&req=showcontent&id=' . $rid,
                                   'comment'  => $comment,
                                 'subject'  => '',
                                 'uid'      => $uid,
                                 'date'     => $date));

        if (!$id) {
            return LogUtil::registerError('News migration: Error creating comment');
        } 
    } 
    $result->Close(); 

    // activate the ezcomments hook for the news module
    pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'Reviews', 'hookmodname' => 'EZComments'));

    LogUtil::registerStatus('News migration successful');
}
