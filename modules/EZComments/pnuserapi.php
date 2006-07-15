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
 * @version     1.3
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
 * @param     $args['mod']   Name of the module to get comments for
 * @param     $args['objectid']  ID of the item to get comments for
 * @param     $args['search']    an array with words to search for and a boolean
 * @param     $args['startnum']  First comment
 * @param     $args['numitems']  number of comments
 * @param     $args['sortorder'] order to sort the comments
 * @param     $args['sortby']    field to sort the comments by
 * @param     $args['status']    get all comments of this status
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
    if (isset($mod) && isset($objectid)) {
        if (!pnSecAuthAction(0, 'EZComments::', "$mod:$objectid:", ACCESS_READ)) {
            return $items;
        }
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
    if (isset($mod)) {
        $querymodname = pnVarPrepForStore($mod);
        $whereclause[] = "$EZCommentscolumn[modname] = '$querymodname'";
        if (isset($objectid)) {
            $queryobjectid = pnVarPrepForStore($objectid);
            $whereclause[] = "$EZCommentscolumn[objectid] = '$queryobjectid'";
        }
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
                   $EZCommentscolumn[status],
                   $EZCommentscolumn[ipaddr],
                   $EZCommentscolumn[type],
                   $EZCommentscolumn[anonwebsite]
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
        list($id, $mod, $objectid, $url, $date, $uid, $comment, $subject, $replyto, $anonname, $anonmail, $status, $ipaddr, $type, $anonwebsite) = $result->fields;
        if (pnSecAuthAction(0, 'EZComments::', "$mod:$objectid:$id", ACCESS_READ)) {
            if ($uid == 1 && empty($anonname)) {
                $anonname = pnConfigGetVar('anonymous');
            }
            $items[] = compact('id',
                               'mod',
                               'objectid',
                               'url',
                               'date',
                               'uid',
                               'comment',
                               'subject',
                               'replyto',
                               'anonname',
                               'anonmail',
                               'status',
							   'ipaddr',
							   'type',
							   'anonwebsite');
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
 * @param    $args['mod']    Name of the module to create comments for
 * @param    $args['objectid']   ID of the item to create comments for
 * @param    $args['comment']    The comment itself
 * @param    $args['subject']    The subject of the comment
 * @param    $args['replyto']    The reference ID
 * @param    $args['uid']        The user ID (optional)
 * @param    $args['type']       The type of comment (optional) currently trackback, pingback are only allowed values
 * @return   integer             ID of new comment on success, false on failure
 */
function EZComments_userapi_create($args)
{
    extract($args);

    if ((!isset($mod)) ||
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

	if (!isset($type) && !is_string($type) && $type != 'trackback' && $type != 'pingback') {
		$type = '';
	}

	// get the users ip
	$ipaddr = '';
	if (pnModGetVar('EZComments', 'logip')) {
		$ipaddr = pnServerGetVar('REMOTE_ADDR');
	}

    // Security check
    if (!pnSecAuthAction(0, "EZComments::$type", "$mod:$objectid:", ACCESS_COMMENT)) {
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

    // check we should moderate the comments
	$status[] = 0;
    if (!pnModGetVar('EZComments', 'moderation')) {
        $status[] = 0;
    } else {
		// check if we should moderate all comments
		if (pnModGetVar('EZComments', 'alwaysmoderate')) {
			$status[] = 1;
		} else {
			$checkvars = array($subject, $comment, $anonname, $anonmail, $anonwebsite);
			foreach($checkvars as $checkvar) {
				$status[] = _EZComments_userapi_checkcomment($checkvar);
			}
		}
		$status[] = _EZComments_userapi_checksubmitter();
	}
    // akismet
    $loggedin = pnUserLoggedIn();
    if (pnModGetVar('EZComments', 'akismet')) {
        if (!pnModAPIFunc('EZComments', 'akismet', 'check', 
                          array('author' => $loggedin ?  pnUserGetVar('uname') : $anonname,
                                'authoremail' => $loggedin ? pnUserGetVar('email') : $anonmail,
                                'authorurl' => $loggedin ? pnUserGetVar('url') : $anonwebsite,
                                'content' => $comment,
                                'permalink' => $url))) {
            $status[] = 1;
        }
    }

	// always moderate trackback or pingback comments
    if ($type == 'trackback' || $type == 'pingback' ) {
        $status[] = 1;
    }

	// check for a blacklisted return
	if (in_array(2, $status)) {
		pnSessionSetVar('errormsg', _EZCOMMENTS_COMMENTBLACKLISTED);
		return false;
	}
	// check for a moderated return
	$maxstatus = 0;
	if (in_array(1, $status)) {
		$maxstatus = 1 ;
	}

    list($mod,
         $objectid,
         $url,
         $uid,
         $comment,
         $subject,
         $replyto,
         $anonname,
         $anonmail,
         $maxstatus,
		 $ipaddr,
		 $type,
		 $anonwebsite) = pnVarPrepForStore($mod,
                                        $objectid,
                                        $url,
                                        $uid,
                                        $comment,
                                        $subject,
                                        $replyto,
                                        $anonname,
                                        $anonmail,
                                        $maxstatus,
										$ipaddr,
                                        $type,
										$anonwebsite);

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
              $EZCommentscolumn[status],
			  $EZCommentscolumn[ipaddr],
			  $EZCommentscolumn[type],
			  $EZCommentscolumn[anonwebsite])
            VALUES (
              '$nextId',
              '$mod',
              '$objectid',
              '$url',
              $date,
              '$uid',
              '$comment',
              '$subject',
              '$replyto',
              '$anonname',
              '$anonmail',
              '$maxstatus',
			  '$ipaddr',
			  '$type',
			  '$anonwebsite')";
    $dbconn->Execute($sql);

    // Check for an error with the database code
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // set an approriate status/errormsg
    switch ($maxstatus) {
        case '0' :
            pnSessionSetVar('statusmsg', _EZCOMMENTS_CREATED);
            break;
        case '1' :
            pnSessionSetVar('statusmsg', _EZCOMMENTS_HELDFORMODERATION);
            break;
    }

    // Get the ID of the item that we inserted.
    $id = $dbconn->PO_Insert_ID($EZCommentstable, $EZCommentscolumn['id']);

    // Inform admin about new comment
    if (pnModGetVar('EZComments', 'MailToAdmin') && $maxstatus == 0) {
        $pnRender =& new pnRender('EZComments');
		$pnRender->caching = false;
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
    if (pnModGetVar('EZComments', 'moderationmail') && $maxstatus == 1) {
        $pnRender =& new pnRender('EZComments');
		$pnRender->caching = false;
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
                   $EZCommentscolumn[status],
                   $EZCommentscolumn[ipaddr],
                   $EZCommentscolumn[type],
				   $EZCommentscolumn[anonwebsite]
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
    list($mod,
         $objectid,
         $url,
         $date,
         $uid,
         $comment,
         $subject,
         $replyto,
         $anonname,
         $anonmail,
         $status,
		 $ipaddr,
		 $type,
		 $anonwebsite) = $result->fields;
    if (!pnSecAuthAction(0, 'EZComments::', "$mod:$objectid:$id", ACCESS_READ)) {
        return false;
    }

    $result->Close();
    // Return the items
    return compact('mod',
                   'objectid',
                   'url',
                   'date',
                   'uid',
                   'comment',
                   'subject',
                   'replyto',
                   'anonname',
                   'anonmail',
                   'status',
				   'ipaddr',
				   'type',
				   'anonwebsite');
}

/**
 * Utility function to count the number of items held by this module
 *
 * Credits to Lee Eason from http://pnflashgames.com for giving the idea
 * to allow a module to find the number of comments that have been added
 * to the module as a whole or to an individual item.
 *
 * @param     $args['mod']  name of the module to get the number of comments for
 * @param     $args['objectid'] the objectid to get the number of comments for
 * @param     $args['status']    Status of the comments to get (default: all)
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

	$queryargs = array();

    if (isset($args['mod'])) {
        // Count comments for a specific module
        $mod = pnVarPrepForStore($args['mod']);
        $queryargs[] = "$EZCommentscolumn[modname]='$mod'";
        if (isset($args['objectid'])) {
            // Count comments for a specific item in a specific mod
            $objectid = pnVarPrepForStore($args['objectid']);
            $queryargs[] = "$EZCommentscolumn[objectid]='$objectid'";
        }
    }

	$statussql = '';
	if (isset($args['status']) && is_numeric($args['status']) && $args['status'] >= 0 && $args['status'] <= 2) {
		$status = pnVarPrepForStore($status);
		$queryargs[] = "$EZCommentscolumn[status] = '$args[status]'";
	}

	$wheresql = '';
	if (!empty($queryargs)) {
		$wheresql .= ' WHERE '.implode(' AND ', $queryargs);
	}
	$sql .= $wheresql;
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
 * this function checks a piece of text against
 * the defined moderation rules and returns the an appropriate status
 *
 * @todo turn this into a normal API
 * @param  var string to check
 * @author Mark West
 * @access prviate
 * @return mixed int 1 to require moderation, 0 for instant submission, 2 for discarding the comment, void error
 */
function _EZComments_userapi_checkcomment($var)
{
	if (!isset($var)) return 0;

    // check blacklisted words - exit silently if found
    $blacklistedwords = explode("\n", pnModGetVar('EZComments', 'blacklist'));
    foreach($blacklistedwords as $blacklistedword) {
        $blacklistedword = trim($blacklistedword);
        if (empty($blacklistedword)) continue;
        if (stristr($var, $blacklistedword)) return 2;
    }

    // count the number of links
    $linkcount = count(explode('http:', $var));

    // check link count for blacklisting
    if ($linkcount - 1 >= pnModGetVar('EZComments', 'blacklinkcount')) return 2;

    // check words to trigger a moderated comment
    $modlistedwords = explode("\n", pnModGetVar('EZComments', 'modlist'));
    foreach($modlistedwords as $modlistedword) {
        $modlistedword = trim($modlistedword);
        if (empty($modlistedword)) continue;
        if (stristr($var, $modlistedword)) return 1;
    }

    // check link count for moderation
    if ($linkcount - 1 >= pnModGetVar('EZComments', 'modlinkcount')) return 1;

	// comment passed
    return 0;
}

/**
 * work out the status for a comment
 *
 * this function checks for blacklisted proxies and if the user
 * has already commented
 *
 * @author Mark West
 * @access prviate
 * @return mixed int 1 to require moderation, 0 for instant submission, 2 for discarding the comment, void error
 */
function _EZComments_userapi_checksubmitter($type = '', $uid = null)
{
    // check for open proxies
    // credit to wordpress for this logic function wp_proxy_check()
    $ipnum = pnServerGetVar('REMOTE_ADDR');

    // set the current uid if not present
    if (!isset($uid)) {
        pnUserGetVar('uid');
    }

    if (pnModGetVar('EZComments', 'proxyblacklist') && !empty($ipnum) ) {
        $rev_ip = implode( '.', array_reverse( explode( '.', $ipnum ) ) );
        // opm.blitzed.org is appended to use thier proxy lookup service
        // results of gethostbyname are cached
        $lookup = $rev_ip . '.opm.blitzed.org';
        if ($lookup != gethostbyname($lookup)) {
            return 2;
        }
    }

	// check if the comment comes from user that we trust
	// i.e. one who has an approved comment already
	if (pnUserLoggedIn() && pnModGetVar('EZComments', 'dontmoderateifcommented')) {
		$commentedlist = pnModAPIFunc('EZcomments', 'user', 'getcommentingusers');
		if (is_array($commentedlist) && in_array($uid, $commentedlist)) {
			return 0;
		} else {
			return 1;
		}
	}

    return 0;
}

/**
 * get all users who have commented on the site so far
 *
 * @author Mark West
 * @return array users who've commented so far
 */
function EZComments_userapi_getcommentingusers($args)
{
    extract($args);

    $items = array();

    // Security check
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_OVERVIEW)) {
		return $items;
	}

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column'];

	// setup the query
	$sql = "SELECT DISTINCT $EZCommentscolumn[uid] FROM $EZCommentstable WHERE $EZCommentscolumn[status] = 0";
	$items = $dbconn->GetCol($sql);
    return $items;
}

/**
 * get all comments attached to a module
 *
 * @author Mark West
 * @return mixed array of items if successful, false otherwise
 */
function EZComments_userapi_getallbymodule($args)
{
    extract($args);

    $items = array();

    // Security check
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_OVERVIEW)) {
		return false;
	}

	// check for a valid module
	if (!isset($mod) || !is_string($mod)) {
		return false;
	}
	$mod = pnVarPrepForOS($mod);

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = &$pntable['EZComments_column'];

	$sql = "SELECT $EZCommentscolumn[objectid],
				   $EZCommentscolumn[url],
				   count(*)
	        FROM $EZCommentstable
			WHERE $EZCommentscolumn[modname] = '$mod'
			GROUP BY $EZCommentscolumn[objectid]
			ORDER BY $EZCommentscolumn[objectid]";
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
        list($objectid, $url, $count) = $result->fields;
		$items[] = compact('objectid', 'url', 'count');
    }
    $result->Close();
    // Return the items
    return $items;

}

?>