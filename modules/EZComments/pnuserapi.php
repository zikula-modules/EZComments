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
 * @version     0.8
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

 
/**
 * get comments for a specific item inside a module
 * 
 * This function provides the main user interface to the comments
 * module. 
 * 
 * @param     $args['modname']   Name of the module to get comments for
 * @param     $args['objectid']  ID of the item to get comments for
 * @param     $args['search']    an array with words to search for and a boolean 
 * @param     $args['startnum']  First comment
 * @param     $args['numitems']  number of comments
 * @param     $args['sortorder'] order to sort the comments
 * @param     $args['sortby']    field to sort the comments by
 * @param     $args['status']    field to sort the comments by
 * @return    array              array of items, or false on failure
 */ 
function EZComments_userapi_getall($args)
{
    extract($args);

    if (!isset($startnum) || !is_numeric($startnum)) {
        $startnum = 1;
    }
    if (!isset($numitems) || !is_numeric($numitems)) {
        $numitems = -1;
    }
    if (!isset($status) || !is_numeric($status)) {
        $status = -1;
    }

    $items = array(); 

    // Security check
    if (isset($modname) && isset($objectid)) {
        if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:", ACCESS_READ)) {
            return $items;
        } 
        list($querymodname, $queryobjectid) = pnVarPrepForStore($modname, $objectid);
    } else {
        if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_OVERVIEW)) {
            return $items;
        }
    }

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 
    
    // form where clause
    $whereclause = array();
    if (isset($modname) && isset($objectid)) {
        $whereclause[] = "$EZCommentscolumn[modname] = '$querymodname'";
        $whereclause[] = "$EZCommentscolumn[objectid] = '$queryobjectid'";
    }
    if ($status != -1) {
        $whereclause[] = "$EZCommentscolumn[status] = '$status'";
    }
    if (isset($search)) {
        $where_array = array();
        foreach($search['words'] as $word) {
            $word = pnVarPrepForStore($word);
            $where_array[] = "( $EZCommentscolumn[subject] LIKE '%$word%' 
                             OR $EZCommentscolumn[comment] LIKE '%$word%' )";
        }
        if ($search['bool'] == 'AND') {
            $andor = ' AND ';
        } else {
            $andor = ' OR ';
        }
        $whereclause[] = implode($andor, $where_array);
    }

    $wherestring = '';
    if (!empty($whereclause)) {
        $wherestring = 'WHERE ' . implode(' AND ', $whereclause);
    }

    // form the order clause
    $orderstring = '';
    if (isset($sortby) && isset($EZCommentscolumn[$sortby])) {
        $orderstring = "ORDER BY $EZCommentscolumn[$sortby]";
    } else {
        $orderstring = "ORDER BY $EZCommentscolumn[date]";
    }

    $orderby = 'DESC';
    if (isset($sortorder) && (strtoupper($sortorder) == 'DESC' || strtoupper($sortorder) == 'ASC')) {
        $orderby = $sortorder;
    }

    // Get items
    $sql = "SELECT $EZCommentscolumn[id],
                   $EZCommentscolumn[modname],
                   $EZCommentscolumn[objectid],
                   $EZCommentscolumn[url],
                   $EZCommentscolumn[date],
                   $EZCommentscolumn[uid],
                   $EZCommentscolumn[comment],
                   $EZCommentscolumn[subject],
                   $EZCommentscolumn[replyto],
                   $EZCommentscolumn[anonname],
                   $EZCommentscolumn[anonmail],
                   $EZCommentscolumn[status]
            FROM $EZCommentstable
            $wherestring $orderstring $orderby";
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
        list($id, $modname, $objectid, $url, $date, $uid, $comment, $subject, $replyto, $anonname, $anonmail, $status) = $result->fields;
        if (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$id", ACCESS_READ)) {
            if ($uid == 1 && empty($anonname)) {
                $anonname = pnConfigGetVar('anonymous');
            }
            $items[] = compact('id',
                               'modname',
                               'objectid',
                               'url',
                               'date',
                               'uid',
                               'comment',
                               'subject',
                               'replyto',
                               'anonname',
                               'anonmail',
                               'status');
        } 
    } 
    $result->Close();
    // Return the items
    return $items;
} 


/**
 * create a new comment
 * 
 * This function creates a new comment and returns its ID. 
 * Access checking is done.
 * 
 * @param    $args['modname']    Name of the module to create comments for
 * @param    $args['objectid']   ID of the item to create comments for
 * @param    $args['comment']    The comment itself
 * @param    $args['subject']    The subject of the comment
 * @param    $args['replyto']    The reference ID
 * @return   integer             ID of new comment on success, false on failure
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

    if (!isset($replyto) || empty($replyto)) {
        $replyto = -1;
    }
    if (!isset($uid) || !is_numeric($uid)) {
        $uid = pnUserGetVar('uid');
    }
    if (!isset($date)) {
        $date = 'NOW()';
    } else {
        $date= "'" . pnVarPrepForStore($date) . "'";
    }
    
    // Security check
    if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:", ACCESS_COMMENT)) {
        pnSessionSetVar('errormsg', _EZCOMMENTS_NOAUTH);
        return false;
    } 

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 

    // Get next ID in table
    $nextId = $dbconn->GenId($EZCommentstable);

    $status = _EZComments_userapi_checkcomment(array('subject' => $subject, 'comment' => $comment));
    if (!isset($status)) return false;
    if ($status == 2) {
        pnSessionSetVar('errormsg', _EZCOMMENTS_COMMENTBLACKLISTED);
        return false;
    }
    
    list($modname, 
         $objectid,
         $url,
         $uid,
         $comment,
         $subject,
         $replyto,
         $anonname,
         $anonmail,
         $status  ) = pnVarPrepForStore($modname, 
                                        $objectid, 
                                        $url,
                                        $uid,
                                        $comment,
                                        $subject,
                                        $replyto,
                                        $anonname,
                                        $anonmail,
                                        $status); 
                                       
    // Add item
    $sql = "INSERT INTO $EZCommentstable (
              $EZCommentscolumn[id],
              $EZCommentscolumn[modname],
              $EZCommentscolumn[objectid],
              $EZCommentscolumn[url],
              $EZCommentscolumn[date],
              $EZCommentscolumn[uid],
              $EZCommentscolumn[comment],
              $EZCommentscolumn[subject],
              $EZCommentscolumn[replyto],
              $EZCommentscolumn[anonname],
              $EZCommentscolumn[anonmail],
              $EZCommentscolumn[status])
            VALUES (
              '$nextId',
              '$modname',
              '$objectid',
              '$url',
              $date,
              '$uid',
              '$comment',
              '$subject',
              '$replyto',
              '$anonname',
              '$anonmail',
              '$status')";
    $dbconn->Execute($sql); 

    // Check for an error with the database code
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    } 

    // set an approriate status/errormsg
    switch ($status) {
        case '0' :
            pnSessionSetVar('statusmsg', _EZCCOMMENTSCREATED);
            break;
        case '1' :
            pnSessionSetVar('statusmsg', _EZCOMMENTS_HELDFORMODERATION);
            break;
    }

    // Get the ID of the item that we inserted.
    $id = $dbconn->PO_Insert_ID($EZCommentstable, $EZCommentscolumn['id']); 
    
    // Inform admin about new comment
    if (pnModGetVar('EZComments', 'MailToAdmin') && $status == 0) {
        $pnRender =& new pnRender('EZComments');
        $pnRender->assign('comment', $comment);
        $pnRender->assign('url', $url);
        $pnRender->assign('moderate', pnModURL('EZComments', 'admin', 'modify', array('id' => $id)));
        $pnRender->assign('delete', pnModURL('EZComments', 'admin', 'delete', array('id' => $id)));
        $pnRender->assign('baseURL', pnGetBaseURL());
        $mailsubject = _EZCOMMENTS_MAILSUBJECT;
        $mailbody = $pnRender->fetch('ezcomments_mail_newcomment.htm');
        pnModAPIFunc('Mailer', 'user', 'sendmessage', 
                     array('toaddress' => pnConfigGetVar('adminmail'), 'toname' => pnConfigGetVar('sitename'),  
                            'fromaddress' => pnConfigGetVar('adminmail'), 'fromname' => pnConfigGetVar('sitename'), 
                           'subject' => $mailsubject, 'body' => $mailbody));
    }
    if (pnModGetVar('EZComments', 'moderationmail') && $status == 1) {
        $pnRender =& new pnRender('EZComments');
        $pnRender->assign('comment', $comment);
        $pnRender->assign('url', $url);
        $pnRender->assign('moderate', pnModURL('EZComments', 'admin', 'modify', array('id' => $id)));
        $pnRender->assign('delete', pnModURL('EZComments', 'admin', 'delete', array('id' => $id)));
        $pnRender->assign('baseURL', pnGetBaseURL());
        $mailsubject = _EZCOMMENTS_MODMAILSUBJECT;
        $mailbody = $pnRender->fetch('ezcomments_mail_modcomment.htm');
        pnModAPIFunc('Mailer', 'user', 'sendmessage', 
                     array('toaddress' => pnConfigGetVar('adminmail'), 'toname' => pnConfigGetVar('sitename'),  
                            'fromaddress' => pnConfigGetVar('adminmail'), 'fromname' => pnConfigGetVar('sitename'), 
                           'subject' => $mailsubject, 'body' => $mailbody));
    }
    // pnModCallHooks('item', 'create', $tid, 'tid');
    return $id;
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
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 
    // Get items
    $sql = "SELECT $EZCommentscolumn[modname],
                   $EZCommentscolumn[objectid],
                   $EZCommentscolumn[url],
                   $EZCommentscolumn[date],
                   $EZCommentscolumn[uid],
                   $EZCommentscolumn[comment],
                   $EZCommentscolumn[subject],
                   $EZCommentscolumn[replyto],
                     $EZCommentscolumn[anonname],
                   $EZCommentscolumn[anonmail],
                   $EZCommentscolumn[status]
            FROM $EZCommentstable
            WHERE $EZCommentscolumn[id] = '$id'";
    $result =& $dbconn->Execute($sql); 
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
         $comment,
         $subject,
         $replyto,
         $anonname,
         $anonmail,
         $status) = $result->fields;
    if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$id", ACCESS_READ)) {
        return false;
    } 
     
    $result->Close();
    // Return the items
    return compact('modname', 
                   'objectid',
                   'url',
                   'date', 
                   'uid', 
                   'comment',
                   'subject',
                   'replyto',
                   'anonname',
                   'anonmail',
                   'status');
} 


/**
 * count comments for a specific item inside a module
 * 
 * This function provides the main user interface to the comments
 * module. 
 * 
 * @param     $args['module']    Name of the module to get comments for
 * @param     $args['objectid']  ID of the item to get comments for
 * @return    array              array of items, or false on failure
 */ 
function EZComments_userapi_count($args)
{
    extract($args);

    if (!isset($module) || !isset($objectid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    } 

    if (!pnSecAuthAction(0, 'EZComments::', "$module:$objectid:", ACCESS_READ)) {
        return false;
    } 
    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column']; 
    
    $querymodname = pnVarPrepForStore($module);
    $queryobjectid = pnVarPrepForStore($objectid);
    // Get items
    $sql = "SELECT count(1)
            FROM $EZCommentstable
            WHERE $EZCommentscolumn[modname] = '$querymodname'
              AND $EZCommentscolumn[objectid] = '$queryobjectid'";

    $result =& $dbconn->Execute($sql); 
    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    } 

    list($count) = $result->fields;
    $result->Close(); 
    // Return the items
    return $count;
} 

/**
 * Utility function to count the number of items held by this module
 * 
 * Credits to Lee Eason from http://pnflashgames.com for giving the idea
 * to allow a module to find the number of comments that have been added 
 * to the module as a whole or to an individual item.
 * 
 * @param     $args['modname']  name of the module to get the number of comments for
 * @param     $args['objectid'] the objectid to get the number of comments for
 * @return    integer   number of items held by this module
 */
function EZComments_userapi_countitems($args)
{
    if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_OVERVIEW)) {
        return false;
    } 
    
    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable =& $pntable['EZComments'];
    $EZCommentscolumn =& $pntable['EZComments_column']; 
    
    $sql = "SELECT COUNT(1)
            FROM $EZCommentstable";

    if (isset($args['modname'])) {
        // Count comments for a specific module
        $modname = pnVarPrepForStore($args['modname']);
        $sql .= " WHERE $EZCommentscolumn[modname]='$modname'";
        if (isset($args['objectid'])) {
            // Count comments for a specific item in a specific mod
            $objectid = pnVarPrepForStore($args['objectid']);
            $sql .= " AND $EZCommentscolumn[objectid]='$objectid'";
        } 
    } 

    $result =& $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }
    list($numitems) = $result->fields;
    $result->Close();
    return $numitems;
}

/**
 * utility function to return a list of template sets for 
 * displaying the comments input/output
 * 
 * @return   array   array of template set names (directories)
 */
function EZComments_userapi_gettemplates()
{
    if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_READ)) {
        return false;
    } 

    $modinfo = pnModGetInfo(pnModGetIDFromName('EZComments'));

    $templates = array();
    $handle = opendir('modules/'.pnVarPrepForOS($modinfo['directory']).'/pntemplates/');
    while ($f = readdir($handle)) {
        if ($f != '.' && $f != '..' && $f != 'CVS' && !ereg("[.]", $f) && $f != 'plugins') {
            $templates[] = $f;
        }
    } 
    closedir($handle); 

    return $templates;

}

/**
 * work out the status for a comment
 *
 * this function checks the subject and text of a comment against 
 * the defined moderation rules and returns the an appropriate status
 *
 * @param  subject string the subject of the comment
 * @param  comment string the body of the comment
 * @author Mark West
 * @access prviate
 * @return mixed int 1 to require moderation, 0 for instant submission, 2 for discarding the comment, void error
 */
function _EZComments_userapi_checkcomment($args)
{
    extract($args);

    if (!isset($subject) && !isset($comment)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return;
    }

    // check we should moderate the comments
    if (!pnModGetVar('EZComments', 'moderation')) {
        return 0;
    }
    
    // check if we should moderate all comments
    if (pnModGetVar('EZComments', 'alwaysmoderate')) {
        return 1;
    } 

    // check blacklisted words - exit silently if found
    $blacklistedwords = explode("\n", pnModGetVar('EZComments', 'blacklist'));
    foreach($blacklistedwords as $blacklistedword) {
        $blacklistedword = trim($blacklistedword);
        if (empty($blacklistedword)) continue;
        if (stristr($comment, $blacklistedword)) return 2;
        if (stristr($subject, $blacklistedword)) return 2;
    }

    // check words to trigger a moderated comment
    $modlistedwords = explode("\n", pnModGetVar('EZComments', 'modlist'));
    foreach($modlistedwords as $modlistedword) {
        $modlistedword = trim($modlistedword);
        if (empty($modlistedword)) continue;
        if (stristr($comment, $modlistedword)) return 1;
        if (stristr($subject, $modlistedword)) return 1;
    }

    // check link count
    if (count(explode('http:', $comment))-1 >= pnModGetVar('EZComments', 'modlinkcount')) return 1;
    
    // check for open proxies
    // credit to wordpress for this logic function wp_proxy_check()
    $ipnum = pnServerGetVar('REMOTE_ADDR');
    if (pnModGetVar('EZComments', 'proxyblacklist') && !empty($ipnum) ) {
        $rev_ip = implode( '.', array_reverse( explode( '.', $ipnum ) ) );
        // opm.blitzed.org is appended to use thier proxy lookup service
        // results of gethostbyname are cached
        $lookup = $rev_ip . '.opm.blitzed.org';
        if ($lookup != gethostbyname($lookup)) {
            return 2;
        }
    }

    return 0;
}

?>
