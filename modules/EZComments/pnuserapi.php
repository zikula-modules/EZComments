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
 * get comments for a specific item inside a module
 *
 * This function provides the main user interface to the comments
 * module.
 *
 * @param $args['mod']         Name of the module to get comments for
 * @param $args['objectid']    ID of the item to get comments for
 * @param $args['search']      an array with words to search for and a boolean
 * @param $args['startnum']    First comment
 * @param $args['numitems']    number of comments
 * @param $args['sortorder']   order to sort the comments
 * @param $args['sortby']      field to sort the comments by
 * @param $args['status']      get all comments of this status
 * @param $args['uid']         (optional) get all comments of this user
 * @param $args['owneruid']    (optional) get all comments of this content owner
 * @param $args['admin']       (optional) is set to 1 for admin mode (permission check)
 * @return array array of items, or false on failure
 */
function EZComments_userapi_getall($args)
{
    if (!isset($args['startnum']) || !is_numeric($args['startnum'])) {
        $args['startnum'] = 1;
    }
    if (!isset($args['numitems']) || !is_numeric($args['numitems'])) {
        $args['numitems'] = -1;
    }
    if (!isset($args['status']) || !is_numeric($args['status'])) {
        $args['status'] = -1;
    }

    // create empty array
    $items = array();

    // Security check
    if (isset($args['mod']) && isset($args['objectid'])) {
        if (!SecurityUtil::checkPermission('EZComments::', $args['mod'].':'.$args['objectid'].':', ACCESS_READ)) {
            return $items;
        }
    } else {
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_OVERVIEW)) {
            return $items;
        }
    }

    // Get database setup
    $pntable = pnDBGetTables();

    $EZCommentscolumn = &$pntable['EZComments_column'];

    // form where clause
    $whereclause = array();
    // object id
    if (isset($args['mod'])) {
        $whereclause[] = "$EZCommentscolumn[modname] = '" . DataUtil::formatForStore($args['mod']) . "'";
        if (isset($args['objectid'])) {
            $whereclause[] = "$EZCommentscolumn[objectid] = '" . DataUtil::formatForStore($args['objectid']) . "'";
        }
    }
    // comment's status
    if ($args['status'] >= 0) {
        $whereclause[] = "$EZCommentscolumn[status] = '" . DataUtil::formatForStore($args['status']) . "'";
    }
    // do a search?
    if (isset($args['search'])) {
        $where_array = array();
        foreach($args['search']['words'] as $word) {
            $word = DataUtil::formatForStore($word);
            $where_array[] = "( $EZCommentscolumn[subject] LIKE '%$word%'
                             OR $EZCommentscolumn[comment] LIKE '%$word%' )";
        }
        if ($args['search']['bool'] == 'AND') {
            $andor = ' AND ';
        } else {
            $andor = ' OR ';
        }
        $whereclause[] = implode($andor, $where_array);
    }
    // include own content or own comments
    $owneruid = (int)$args['owneruid'];
    $uid = (int)$args['uid'];
	if (($owneruid > 1) && ($uid > 1)) {
	  	$whereclause[] = "(".$EZCommentscolumn['owneruid']." = '".$args['owneruid']."' OR ".$EZCommentscolumn['uid']." = '".$args['uid']."' )";;
	}
	else if ($uid > 1) {
	  	$whereclause[] = $EZCommentscolumn['uid']." = '".$args['uid']."'";
	}
	else if ($owneruid > 1) {
	  	$whereclause[] = $EZCommentscolumn['owneruid']." = '".$args['owneruid']."'";
	}
	// admin mode: only show comments for modules considering permission checks
	$admin = (int)$args['admin'];
	if ($admin == 1) {
		// get list of modules
		$modlist = pnModGetAllMods();
		$permclause = array();
		foreach ($modlist as $module) {
		  	// simple permission check
			$inst = $module['name'].":".$item['objectid'].":".$item['id'];
			if (SecurityUtil::checkPermission('EZComments::', $inst, ACCESS_EDIT)) {
				$permclause[] = $EZCommentscolumn['modname']." = '".$module['name']."'";
			}
		}
		$whereclause[] = "(".implode(' OR ', $permclause).")";
	}
	
 	// create where clause
    $where = '';
    if (!empty($whereclause)) {
        $where = 'WHERE ' . implode(' AND ', $whereclause);
    }
    // form the orderby clause
    $orderby = '';
    if (isset($args['sortby']) && isset($EZCommentscolumn[$args['sortby']])) {
        $orderby = 'ORDER BY '. $EZCommentscolumn[$args['sortby']];
    } else {
        $orderby = "ORDER BY $EZCommentscolumn[date]";
    }
   
    if (isset($args['sortorder']) && (strtoupper($args['sortorder']) == 'DESC' || strtoupper($args['sortorder']) == 'ASC')) {
        $orderby .= ' ' . $args['sortorder'];
    } else {
        $orderby .= ' DESC';
    }

    $permFilter[]  = array ('realm'            =>  0,
                            'component_left'   =>  'EZComments',
                            'component_middle' =>  '',
                            'component_right'  =>  '',
                            'instance_left'    =>  'modname',
                            'instance_middle'  =>  'objectid',
                            'instance_right'   =>  'id',
                            'level'            =>  ACCESS_READ);
    $items = DBUtil::selectObjectArray('EZComments', $where, $orderby, $args['startnum']-1, $args['numitems'], '', $permFilter);
    
    // backwards compatibilty: modname -> mod
    foreach ($items as $key => $dummy) {
        $items[$key]['mod'] = $items[$key]['modname'];
    }

    // Return the items
    return $items;
}

/**
 * create a new comment
 *
 * This function creates a new comment and returns its ID.
 * Access checking is done.
 *
 * @param $args['mod']        Name of the module to create comments for
 * @param $args['objectid']   ID of the item to create comments for
 * @param $args['comment']    The comment itself
 * @param $args['subject']    The subject of the comment
 * @param $args['replyto']    The reference ID
 * @param $args['uid']        The user ID (optional)
 * @param $args['owneruid']   The user ID whoose content was commented(optional)
 * @param $args['useurl']     The url that should be used for storing in db and email to admin
 * @param $args['type']       The type of comment (optional) currently trackback, pingback are only allowed values
 * @return integer ID of new comment on success, false on failure
 */
function EZComments_userapi_create($args)
{
    extract($args);

    if (!isset($mod) ||
        !isset($objectid) ||
        !isset($comment)) {
        return LogUtil::registerError(_MODARGSERROR);
    }
    $owneruid 	= (int)$args['owneruid'];
    // Sometimes the displayurl for the redirect is another url then the url, 
	// that should be sent via email.
    $useurl 	= $args['useurl'];
    $redirect 	= $args['redirect'];
    if (isset($useurl) && !empty($useurl)) {
	    $useurl 		= str_replace('&amp;', '&', $useurl);
		$url 			= $useurl;
	}
	else {
	  	$baseURL = pnGetBaseURL();
		$args['url'] = $baseURL.str_replace($baseURL,'',$redirect);
		$url = $args['url'];
	}
    
    // ContactList ignore check. If the user is ignored by the 
	// content owner the user will not be able to post any comment...
	if (	(pnUserGetVar('uid') > 1) 		&& 
			($owneruid>0) 					&& 
			pnModAvailable('ContactList') 	&& 
			pnModAPIFunc('ContactList','user','isIgnored',array(
					'iuid' => pnUserGetVar('uid'),
					'uid' => $owneruid
					))
		) {
		return LogUtil::registerError('_EZCOMMENTS_USER_IGNORES_YOU');
	}
	
    // check unregistered user included name (if required)
	$anonname = trim($anonname);
	if (!pnUserLoggedIn()) {
    	if (pnModGetVar('EZComments', 'anonusersrequirename') && empty($anonname)) {
        	return LogUtil::registerError(_EZCOMMENTS_ANON_NAME_REJECT);
    	}
	}
    if (!isset($replyto) || empty($replyto)) {
        $replyto = -1;
    }
    $loggedin = pnUserLoggedIn();
	if (!$loggedin) {
        $uid = 0;
    }
    if (!isset($uid) || !is_numeric($uid)) {
        $uid = pnUserGetVar('uid');
    }

    if (!isset($date)) {
        $date = date("Y-m-d H:i:s",time());
    } else {
        $date= "'" . DataUtil::formatForStore($date) . "'";
    }

	if (!isset($type) || !is_string($type) || ($type != 'trackback' && $type != 'pingback')) {
		$type = '';
	}

	// get the users ip
	$ipaddr = '';
	if (pnModGetVar('EZComments', 'logip')) {
		$ipaddr = pnServerGetVar('REMOTE_ADDR');
	}

    // Security check
    if (!SecurityUtil::checkPermission("EZComments::$type", "$mod:$objectid:", ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError('index.php');
    }

    // Get database setup
    $dbconn = pnDBGetConn(true);
    $pntable = pnDBGetTables();

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
    if (pnModAvailable('akismet') && pnModGetVar('EZComments', 'akismet')) {
        if (pnModAPIFunc('akismet', 'user', 'isspam', 
                          array('author' => $loggedin ?  pnUserGetVar('uname') : $anonname,
                                'authoremail' => $loggedin ? pnUserGetVar('email') : $anonmail,
                                'authorurl' => $loggedin ? pnUserGetVar('url') : $anonwebsite,
                                'content' => $comment,
                                'permalink' => $url))) {
            $status[] = pnModGetVar('EZComments', 'akismetstatus');
        }
    }

	// always moderate trackback or pingback comments
    if ($type == 'trackback' || $type == 'pingback' ) {
        $status[] = 1;
    }

	// check for a blacklisted return
	if (in_array(2, $status)) {
		return LogUtil::registerError(_EZCOMMENTS_COMMENTBLACKLISTED);
	}
	// check for a moderated return
	$maxstatus = 0;
	if (in_array(1, $status)) {
		$maxstatus = 1 ;
	}
	
	// build new object
	$newcomment = array (
			'modname'		=> $mod,
			'objectid'		=> $objectid,
			'url'			=> $url,
			'date'			=> $date,
			'uid'			=> $uid,
			'owneruid'		=> $owneruid,
			'comment'		=> $comment,
			'subject'		=> $subject,
			'replyto'		=> $replyto,
			'anonname'		=> $anonname,
			'anonmail'		=> $anonmail,
			'status'		=> $maxstatus,
			'ipaddr'		=> $ipaddr,
			'type'			=> $type,
			'anonwebsite'	=> $anonwebsite
		);

    if (!DBUtil::insertObject($newcomment,'EZComments')) {
        return LogUtil::registerError(_CREATEFAILED);
    }
	
    // set an approriate status/errormsg
    switch ($maxstatus) {
        case '0' :
            LogUtil::registerStatus(_EZCOMMENTS_CREATED);
            break;
        case '1' :
            LogUtil::registerStatus(_EZCOMMENTS_HELDFORMODERATION);
            break;
    }

    // Get the ID of the item that we inserted.
	$id = $newcomment['id'];

	if (isset($owneruid) && ($owneruid > 0)) {
	  	$owner['email'] = pnUserGetVar('email',$owneruid);
	  	$owner['uname'] = pnUserGetVar('uname',$owneruid);
	  	if ((strlen($owner['email']) > 0) && (strlen($owner['uname']) > 0)) {
		    $toaddress 	= $owner['email'];
		    $toname		= $owner['uname'];
		}
		else {
		  	$toaddress	= pnConfigGetVar('adminmail');
		  	$toname		= pnConfigGetVar('sitename');
		}
	}
	else {
	  	$toaddress	= pnConfigGetVar('adminmail');
	  	$toname		= pnConfigGetVar('sitename');
	}
    // Inform the content owner or the admin about a new comment
    if (pnModGetVar('EZComments', 'MailToAdmin') && $maxstatus == 0) {
        $renderer = pnRender::getInstance('EZComments', false);
        $renderer->assign('comment', $comment);
        $renderer->assign('url', $url);
        $renderer->assign('moderate', pnModURL('EZComments', 'user', 'modify', array('id' => $id)));
        $renderer->assign('delete', pnModURL('EZComments', 'user', 'modify', array('id' => $id)));
        // by AM - 8 lines: added subject, date, username or nick:
		$renderer->assign('subject', $subject);
		$renderer->assign('date', $date);
		if ($uid > 0) {
			$username = pnUserGetVars($uid);
			$renderer->assign('user', $username['uname']);
		} else {
			$renderer->assign('user', $anonname." ".$anonmail);
		}
		$renderer->assign('id', $id);        $mailsubject = _EZCOMMENTS_MAILSUBJECT;
        $mailbody = $renderer->fetch('ezcomments_mail_newcomment.htm');
        pnModAPIFunc('Mailer', 'user', 'sendmessage',
                     array('toaddress' => $toaddress, 'toname' => $toname,
                            'fromaddress' => pnConfigGetVar('adminmail'), 'fromname' => pnConfigGetVar('sitename'),
                           'subject' => $mailsubject, 'body' => $mailbody));
    }
    if (pnModGetVar('EZComments', 'moderationmail') && $maxstatus == 1) {
        $renderer = pnRender::getInstance('EZComments', false);
        $renderer->assign('comment', $comment);
        $renderer->assign('url', $url);
        $renderer->assign('moderate', pnModURL('EZComments', 'user', 'modify', array('id' => $id)));
        $renderer->assign('delete', pnModURL('EZComments', 'user', 'modify', array('id' => $id)));
        // by AM - 8 lines: added subject, date, username or nick:
		$renderer->assign('subject', $subject);
		$renderer->assign('date', $date);
		if ($uid > 0) {
			$username = pnUserGetVars($uid);
			$renderer->assign('user', $username['uname']);
		} else {
			$renderer->assign('user', $anonname." ".$anonmail);
		}
        $mailsubject = _EZCOMMENTS_MODMAILSUBJECT;
        $mailbody = $renderer->fetch('ezcomments_mail_modcomment.htm');
        pnModAPIFunc('Mailer', 'user', 'sendmessage',
                     array('toaddress' => $toaddress, 'toname' => $toname,
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
    if (!isset($args['id']) || empty($args['id']) ) {
        return LogUtil::registerError(_MODARGSERROR);
    }
    
    // init empty comment
    $comment = array();

    // load table information
    pnModDBInfoLoad('EZComments', 'EZComments');
    
    $permFilter[]  = array ('realm'            =>  0,
                            'component_left'   =>  'EZComments',
                            'component_middle' =>  '',
                            'component_right'  =>  '',
                            'instance_left'    =>  'modname',
                            'instance_middle'  =>  'objectid',
                            'instance_right'   =>  'id',
                            'level'            =>  ACCESS_READ);
    
    $comment = DBUtil::selectObjectByID('EZComments', $args['id']); 
    
    if($comment <> false && is_array($comment)) {
        // backwards compatibility
        $comment['mod'] = $comment['modname'];
    }
    return $comment;
}

/**
 * Utility function to count the number of items held by this module
 *
 * Credits to Lee Eason from http://pnflashgames.com for giving the idea
 * to allow a module to find the number of comments that have been added
 * to the module as a whole or to an individual item.
 *
 * @param $args['mod']  name of the module to get the number of comments for
 * @param $args['objectid'] the objectid to get the number of comments for
 * @param $args['status']  Status of the comments to get (default: all)
 * @param $args['owneruid']  (optional) UID of owner 
 * @param $args['uid']  (optional) UID of poster
 * @param $args['admin']  (optional) set to 1 if called from admin mode
 * @return integer number of items held by this module
 */
function EZComments_userapi_countitems($args)
{
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_OVERVIEW)) {
        return false;
    }
    // get parameters
    $owneruid = (int)$args['owneruid'];
    $uid = (int)$args['uid'];

    // Get database setup
    $dbconn = pnDBGetConn(true);
    $pntable = pnDBGetTables();

    $EZCommentstable = $pntable['EZComments'];
    $EZCommentscolumn = $pntable['EZComments_column'];

    $sql = "SELECT COUNT(1)
            FROM $EZCommentstable";
        
	$queryargs = array();

	if (($owneruid > 1) && ($uid > 1)) {
	  	$queryargs[] = $EZCommentscolumn['owneruid']." = '".$args['owneruid']."' OR ".$EZCommentscolumn['uid']." = '".$args['uid']."'";;
	}
	else if ($uid > 1) {
	  	$queryargs[] = $EZCommentscolumn['uid']." = '".$args['uid']."'";
	}
	else if ($owneruid > 1) {
	  	$queryargs[] = $EZCommentscolumn['owneruid']." = '".$args['owneruid']."'";
	}

    if (isset($args['mod'])) {
        // Count comments for a specific module
        $mod = DataUtil::formatForStore($args['mod']);
        $queryargs[] = "$EZCommentscolumn[modname]='$mod'";
        if (isset($args['objectid'])) {
            // Count comments for a specific item in a specific mod
            $objectid = DataUtil::formatForStore($args['objectid']);
            $queryargs[] = "$EZCommentscolumn[objectid]='$objectid'";
        }
    }

	$statussql = '';
	if (isset($args['status']) && is_numeric($args['status']) && $args['status'] >= 0 && $args['status'] <= 2) {
		$args['status'] = DataUtil::formatForStore($args['status']);
		$queryargs[] = "$EZCommentscolumn[status] = '$args[status]'";
	}

   // admin mode: only count comments for modules considering permission checks
	$admin = (int)$args['admin'];
	if ($admin == 1) {
		// get list of modules
		$modlist = pnModGetAllMods();
		$permclause = array();
		foreach ($modlist as $module) {
		  	// simple permission check
			$inst = $module['name'].":".$item['objectid'].":".$item['id'];
			if (SecurityUtil::checkPermission('EZComments::', $inst, ACCESS_EDIT)) {
				$permclause[] = $EZCommentscolumn['modname']." = '".$module['name']."'";
			}
		}
		$queryargs[] = implode(' OR ', $permclause);
	}

	$wheresql = '';
	if (!empty($queryargs)) {
		$wheresql .= ' WHERE '.implode(' AND ', $queryargs);
	}
	$sql .= $wheresql;
    $result = $dbconn->Execute($sql);

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
 * @return array array of template set names (directories)
 */
function EZComments_userapi_gettemplates()
{
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_READ)) {
        return false;
    }

    $templates = array();

    $modinfo = pnModGetInfo(pnModGetIDFromName('EZComments'));
    $osmoddir = DataUtil::formatForOS($modinfo['directory']);
    $ostheme = DataUtil::formatForOS(pnUserGetTheme());
    $rootdirs = array('modules/'.$osmoddir.'/pntemplates/',
                      'config/templates/'.$osmoddir.'/',
                      'themes/'.$ostheme.'/templates/'.$osmoddir.'/');

    // read each directory for template sets
    foreach ($rootdirs as $rootdir) {
        if (is_dir($rootdir)) {
            $handle = opendir($rootdir);
            while ($f = readdir($handle)) {
                if ($f != '.' && $f != '..' && $f != '.svn' && $f != 'CVS' && !ereg("[.]", $f) && $f != 'plugins') {
                    $templates[] = $f;
                }
            }
            closedir($handle);
        }
    }

    // remove any duplicates
    $templates = array_unique($templates);

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
	if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_OVERVIEW)) {
		return $items;
	}

    // Get database setup
    $dbconn = pnDBGetConn(true);
    $pntable = pnDBGetTables();

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
	if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_OVERVIEW)) {
		return false;
	}

	// check for a valid module
	if (!isset($mod) || !is_string($mod)) {
		return false;
	}
	$mod = DataUtil::formatForOS($mod);

    // Get database setup
    $dbconn = pnDBGetConn(true);
    $pntable = pnDBGetTables();

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
        return LogUtil::registerError(_GETFAILED);
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

/**
 * advanced checkPermission-function
 *
 * This function is neccessary because the regular permission system
 * of the CMS does not provide all functionallity we need. For example, if
 * EZComments is hooked to the MyProfile module a profile owner must be able
 * to delete comments other users wrote into his profile page.
 * This function first does the regular Zikula checkPermission call and if
 * this function call is "false", we'll do some more checks
 *
 * @author Florian Schieﬂl
 * @param $args['module'] string module's name
 * @param $args['objectid'] int object's id
 * @param $args['commentid'] int id of comment
 * @param $args['uid'] int commenting user's uid (opt)
 * @param $args['level'] string security level for SecurityUtil
 *
 * @return boolean
 */
function EZComments_userapi_checkPermission($args) {
  	
	$module 	= $args['module'];
  	$objectid 	= $args['objectid'];
  	$commentid	= $args['commentid'];
  	$level 		= $args['level']; 
	$inst 		= $module.":".$objectid.":".$commentid;	   	
	$uid		= pnUserGetVar('uid');

	// own comments = ok
	if ($uid == (int)$args['uid']) return true;

	// parameter check
  	if ((!isset($module)) || (!isset($level)) || (!isset($objectid)) || (!isset($commentid))) return false;

  	// regular securityUtil::checkPermission check. Return true on success
	if (SecurityUtil::checkPermission('EZComments::', $inst, $level)) return true;

	// otherwise: get the comment, check the owneruid and return the result
	$comment = DBUtil::selectObjectByID('EZComments',$commentid);
	if (($comment['owneruid'] == $uid) || ($comment['uid'] == $uid)) return true;	
	// otherwise return false because no security check had a positive result
  	return false;
}
