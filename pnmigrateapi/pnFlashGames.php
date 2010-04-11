<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
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
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError('pnFlashGames comments migration: Not Admin');
    } 

    // Get datbase setup
    $dbconn  = pnDBGetConn(true);
    $pntable = pnDBGetTables();

    $Commentstable  = $pntable['pnFlashGames_comments'];
    $Commentscolumn = $pntable['pnFlashGames_comments_column'];

    $EZCommentstable  = $pntable['EZComments'];
    $EZCommentscolumn = $pntable['EZComments_column']; 

    $Usertable  = $pntable['users'];
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
    for (; !$result->EOF; $result->MoveNext())
    {
        list($gid, $uname, $date, $comment, $uid) = $result->fields;

        $id = pnModAPIFunc('EZComments', 'user', 'create',
                           array('mod'      => 'pnFlashGames',
                                 'objectid' => DataUtil::formatForStore($gid),
                                 'url'      => pnModURL('pnFlashGames', 'user', 'display', array('id' => $gid)),
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
    $tids = array_keys($comments);
    foreach ($tids as $tid) {
        if ($tid != 0) {
            $v = $comments[$tid];
            $sql = "UPDATE $EZCommentstable 
                       SET $EZCommentscolumn[replyto] = '" . $comments[$v['pid']]['newid'] . "'
                     WHERE $EZCommentscolumn[id] = '$v[newid]'";

            $result = $dbconn->Execute($sql);
        }
    }

    return LogUtil::registerStatus('pnFlashGames migration successful');
}
