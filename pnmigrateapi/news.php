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
function EZComments_migrateapi_news()
{
    // Security check
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError('News migration: Not Admin');
    }

    // Get datbase setup
    $dbconn  = pnDBGetConn(true);
    $pntable = pnDBGetTables();

    $EZCommentstable  = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column'];

    $Commentstable  = $pntable['comments'];
    $Commentscolumn = $pntable['comments_column'];

    if (version_compare(PN_VERSION_NUM, '1', '>=')) {
        EZComments_get76xcolumns_news($Commentstable, $Commentscolumn);
    }
    if (is_null($Commentstable) || is_null($Commentscolumn)) {
        return LogUtil::registerError('News migration: Comments tables not found');
    }

    $Usertable  = $pntable['users'];
    $Usercolumn = $pntable['users_column'];

    $sql = "SELECT $Commentscolumn[tid],
                   $Commentscolumn[sid],
                   $Commentscolumn[date], 
                   $Usercolumn[uid],
                   $Commentscolumn[comment],
                   $Commentscolumn[subject],
                   $Commentscolumn[pid]
              FROM $Commentstable
         LEFT JOIN $Usertable
                ON $Commentscolumn[name] = $Usercolumn[uname]";

    $result = $dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0) {
        return LogUtil::registerError('News migration: DB Error');
    }

    // array to rebuild the patents
    $comments = array(0 => array('newid' => -1));

    // loop through the old comments and insert them one by one into the DB
    for (; !$result->EOF; $result->MoveNext())
    {
        list($tid, $sid, $date, $uid, $comment, $subject, $replyto) = $result->fields;

        // set the correct user id for anonymous users
        if (empty($uid)) {
            $uid = 1;
        }

        $id = pnModAPIFunc('EZComments', 'user', 'create',
                           array('mod'      => 'News',
                                 'objectid' => DataUtil::formatForStore($sid),
                                 'url'      => pnModURL('News', 'user', 'display', array('sid' => $sid)),
                                 'comment'  => $comment,
                                 'subject'  => $subject,
                                 'uid'      => $uid,
                                 'date'     => $date));

        if (!$id) {
            return LogUtil::registerError('News migration: Error creating comment');
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

    // activate the ezcomments hook for the news module
    pnModAPIFunc('Modules', 'admin', 'enablehooks',
                 array('callermodname' => 'News',
                       'hookmodname'   => 'EZComments'));

 	return LogUtil::registerStatus('News migration successful');
}

function EZComments_get76xcolumns_news(&$Commentstable, &$Commentscolumn)
{
    $Commentstable  = DBUtil::getLimitedTablename('comments');
    $Commentscolumn = array(
        'tid'       => 'pn_tid',
        'pid'       => 'pn_pid',
        'sid'       => 'pn_sid',
        'date'      => 'pn_date',
        'name'      => 'pn_name',
        'email'     => 'pn_email',
        'url'       => 'pn_url',
        'host_name' => 'pn_host_name',
        'subject'   => 'pn_subject',
        'comment'   => 'pn_comment',
        'score'     => 'pn_score',
        'reason'    => 'pn_reason'
    );
}
