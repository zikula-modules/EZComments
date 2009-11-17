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
 * @since    0.6
 */
function EZComments_migrateapi_reviews()
{
    // Security check
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError('Reviews migration: Not Admin');
    } 

    // Get datbase setup
    pnModDBInfoLoad('Reviews', 'EZComments/pnmigrateapi/Reviews', true);
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
        return LogUtil::registerError('Reviews migration: DB Error');
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
                                 'url'      => 'index.php?name=Reviews&req=showcontent&id=' . $rid,
                                 'comment'  => $comment,
                                 'subject'  => '',
                                 'uid'      => $uid,
                                 'date'     => $date));

        if (!$id) {
            return LogUtil::registerError('Reviews migration: Error creating comment');
        } 
    } 
    $result->Close(); 

    // activate the ezcomments hook for the news module
    pnModAPIFunc('Modules', 'admin', 'enablehooks',
                 array('callermodname' => 'Reviews',
                       'hookmodname' => 'EZComments'));

    LogUtil::registerStatus('Reviews migration successful');
}
