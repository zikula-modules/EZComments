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
 * @version     0.2
 * @link        http://lottasophie.sourceforge.net Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

 
/**
 * Return to index page
 * 
 * This is the default function called when EZComments is called 
 * as a module. As we do not intend to output anything, we just 
 * redirect to the start page.
 * 
 * @since    0.2
 */
function EZComments_user_main($args)
{
    pnredirect(pnGetBaseUrl());
    return true;
}


/**
 * Display comments for a specific item
 * 
 * This function provides the main user interface to the comments
 * module. 
 * 
 * @param    $args['objectid']     ID of the item to display comments for
 * @param    $args['extrainfo']    URL to return to if user chooses to comment
 * @param    $args['template']     Template file to use (with extension)
 * @return   output                the comments
 * @since    0.1
 */
function EZComments_user_view($args)
{
    $modname = pnModGetName();
    $objectid = $args['objectid'];

    if (!pnSecAuthAction(0, 'EZComments::', "$modname:$objectid: ", ACCESS_OVERVIEW)) {
        return _EZCOMMENTS_NOAUTH;
    } 

    if (!pnModAPILoad('EZComments', 'user')) {
        return _LOADFAILED;
    }
    
    $items = pnModAPIFunc('EZComments',
                          'user',
                          'getall',
                           compact('modname', 'objectid'));

    if ($items === false) {
        return _EZCOMMENTS_FAILED;
    }     
    
    $comments = EZComments_prepareCommentsForDisplay($items);

    // create the pnRender object
    $pnRender =& new pnRender('EZComments');

    // don't use caching (for now...)
    $pnRender->caching=false;
    
    $pnRender->assign('comments',   $comments);
    $pnRender->assign('allowadd',   pnSecAuthAction(0, 'EZComments::', "$modname:$objectid: ", ACCESS_COMMENT));
    if (!is_array($args['extrainfo'])) {
    	$pnRender->assign('redirect',   pnVarPrepForDisplay($args['extrainfo']));
    } else {
		$pnRender->assign('redirect',   pnVarPrepForDisplay($args['extrainfo']['returnurl']));
    }
    $pnRender->assign('objectid',   pnVarPrepForDisplay($objectid));

	// check for some useful hooks
	if (pnModIsHooked('pn_bbcode', 'EZComments')) {
		$pnRender->assign('bbcode', true);
	}
	if (pnModIsHooked('pn_bbsmile', 'EZComments')) {
		$pnRender->assign('smilies', true);
	}
    // find out which template to use
    $template = isset($args['template']) ? $args['template'] : 'ezcomments_user_view.htm';
    return $pnRender->fetch(pnModGetVar('EZComments', 'template') . '/'. $template);
} 


/**
 * Display a comment form 
 * 
 * This function displays a comment form, if you do not want users to
 * comment on the same page as the item is.
 * 
 * @param    $EZComments_comment     the comment (taken from HTTP put)
 * @param    $EZComments_modname     the name of the module the comment is for (taken from HTTP put)
 * @param    $EZComments_objectid    ID of the item the comment is for (taken from HTTP put)
 * @param    $EZComments_redirect    URL to return to (taken from HTTP put)
 * @param    $EZComments_subject     The subject of the comment (if any) (taken from HTTP put)
 * @param    $EZComments_replyto     The ID of the comment for which this an anser to (taken from HTTP put)
 * @param    $EZComments_template    The name of the template file to use (with extension)
 * @todo     Check out it this function can be merged with _view!
 * @since    0.2
 */
function EZComments_user_comment($args)
{
    list($EZComments_modname,
         $EZComments_objectid,
         $EZComments_redirect,
         $EZComments_comment,
         $EZComments_subject,
         $EZComments_replyto,
         $EZComments_template) = pnVarCleanFromInput('EZComments_modname',
                                                     'EZComments_objectid',
                                                     'EZComments_redirect',
                                                     'EZComments_comment',
                                                     'EZComments_subject',
                                                     'EZComments_replyto',
                                                     'EZComments_template');

    extract($args);
    
    if (!pnModAPILoad('EZComments', 'user')) {
        return _LOADFAILED;
    }

    $items = pnModAPIFunc('EZComments',
                          'user',
                          'getall',
                           array('modname'  => $EZComments_modname, 
                                 'objectid' => $EZComments_objectid));

    if ($items === false) {
        return _EZCOMMENTS_FAILED;
    } 

    $comments = EZComments_prepareCommentsForDisplay($items);

    $pnRender =& new pnRender('EZComments');

    // don't use caching (for now...)
    $pnRender->caching=false;

    $pnRender->assign('comments', $comments);
    $pnRender->assign('authid',   pnSecGenAuthKey('EZComments'));
    $pnRender->assign('allowadd', pnSecAuthAction(0, 'EZComments::', "$EZComments_modname:$EZComments_objectid: ", ACCESS_COMMENT));
    $pnRender->assign('addurl',   pnModURL('EZComments', 'user', 'create'));
    $pnRender->assign('redirect', $EZComments_redirect);
    $pnRender->assign('modname',  pnVarPrepForDisplay($EZComments_modname));
    $pnRender->assign('objectid', pnVarPrepForDisplay($EZComments_objectid));
    $pnRender->assign('subject',  pnVarPrepForDisplay($EZComments_subject));
    $pnRender->assign('replyto',  pnVarPrepForDisplay($EZComments_replyto));

    $template = isset($EZComments_template) ? $EZComments_template : 'ezcomments_user_comment.htm';
    return $pnRender->fetch(pnModGetVar('EZComments', 'template') . '/'. $template);
}



/**
 * Create a comment for a specific item
 * 
 * This is a standard function that is called with the results of the
 * form supplied by EZComments_user_view to create a new item
 * 
 * @param    $EZComments_comment     the comment (taken from HTTP put)
 * @param    $EZComments_modname     the name of the module the comment is for (taken from HTTP put)
 * @param    $EZComments_objectid    ID of the item the comment is for (taken from HTTP put)
 * @param    $EZComments_redirect    URL to return to (taken from HTTP put)
 * @param    $EZComments_subject     The subject of the comment (if any) (taken from HTTP put)
 * @param    $EZComments_replyto     The ID of the comment for which this an anser to (taken from HTTP put)
 * @since    0.1
 */
function EZComments_user_create($args)
{
    list($EZComments_modname,
         $EZComments_objectid,
         $EZComments_redirect,
         $EZComments_comment,
         $EZComments_subject,
         $EZComments_replyto) = pnVarCleanFromInput('EZComments_modname',
                                                      'EZComments_objectid',
                                                      'EZComments_redirect',
                                                     'EZComments_comment',
                                                     'EZComments_subject',
                                                     'EZComments_replyto');
    // Confirm authorisation code.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect($EZComments_redirect);
        return true;
    } 
    // Load API
    if (!pnModAPILoad('EZComments', 'user')) {
        pnSessionSetVar('errormsg', _LOADFAILED);
        pnRedirect($EZComments_redirect);
        return false;
    } 

    $id = pnModAPIFunc('EZComments',
                       'user',
                       'create',
                       array('modname'  => $EZComments_modname,
                             'objectid' => $EZComments_objectid,
                             'url'      => $EZComments_redirect,
                             'comment'  => $EZComments_comment,
                             'subject'  => $EZComments_subject,
                             'replyto'  => $EZComments_replyto,
                             'uid'      => pnUserGetVar('uid')));

    if ($id != false) {
        // Success
        pnSessionSetVar('statusmsg', _EZCCOMMENTSCREATED);
    } 

    // decoding the URL. Credits to tmyhre for fixing.
    $EZComments_redirect = rawurldecode($EZComments_redirect);
    $EZComments_redirect = str_replace('&amp;', '&', $EZComments_redirect);

    pnRedirect($EZComments_redirect);
    return true;
} 


/**
 * Delete a comment
 * 
 * This is a standard function that is called with the results of the
 * form supplied by EZComments_user_view to delete a comment
 * 
 * @param    $EZComments_id         ID of the the comment to delete (taken from HTTP put)
 * @param    $EZComments_redirect   URL to return to (taken from HTTP put)
 * @since    0.1
 */
function EZComments_user_delete($args)
{
    list($EZComments_id,
     $EZComments_redirect) = pnVarCleanFromInput('EZComments_id',
                                                 'EZComments_redirect'); 

    // Confirm authorisation code.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect($return_url);
        return true;
    } 
    // Load API
    if (!pnModAPILoad('EZComments', 'user')) {
        pnSessionSetVar('errormsg', _LOADFAILED);
        return false;
    } 
    $id = pnModAPIFunc('EZComments',
                       'user',
                       'delete',
                       array('id' => $EZComments_id));

    if ($id != false) {
        pnSessionSetVar('statusmsg', _EZCCOMMENTSDELETED);
    } 

    $EZComments_redirect = rawurldecode($EZComments_redirect);
    $EZComments_redirect = str_replace('&amp;', '&', $EZComments_redirect);
    pnRedirect($EZComments_redirect);
    return true;
} 


/**
 * Prepare comments to be displayed
 * 
 * We loop through the "raw data" returned from the API to prepare these data
 * to be displayed. 
 * We check for necessary rights, and derive additional information (e.g. user
 * data) drom other modules.
 * 
 * @param    $items    An array of comment items as returned from the API
 * @return   array     An array to display (augmented information / perm. check)
 * @since    0.2
 */
function EZComments_prepareCommentsForDisplay($items)
{
    $comments = array();
    foreach ($items as $item) {
        if (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$item[id]", ACCESS_READ)) {
            $comment = $item;

            if ($item['uid'] > 0) {
                $userinfo = pnUserGetVars($item['uid']);
			 	//print_r ($userinfo);
				$comment	= array_merge ($comment, $userinfo);
                
				$dbconn =& pnDBGetConn(true);
				$pntable =& pnDBGetTables();
				$activetime = time() - (pnConfigGetVar('secinactivemins') * 60);
				$userhack = "SELECT pn_uid
                         FROM ".$pntable['session_info']."

                         WHERE pn_uid = '".$userinfo['pn_uid']."'

                         AND pn_lastused > '".pnVarPrepForStore($activetime)."'";
				$userresult = $dbconn->Execute($userhack);
	            $online_state = $userresult->GetRowAssoc(false);
                $comment['online'] = false;
                if($online_state['pn_uid'] == $item['uid']) {
                    $comment['online'] = true;
    				$userresult->Close();
	            }
            } else {
                $comment['uname'] = pnConfigGetVar('Anonymous');
            }

            list($item['comment']) = pnModCallHooks('item', 'transform', 'x', array($item['comment']));
            $comment['comment'] = pnVarPrepHTMLDisplay(pnVarCensor(nl2br($item['comment'])));
            
            $comment['del'] = (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$item[id]", ACCESS_DELETE));
            
            $comments[] = $comment;
        } 
    }
    return $comments;
}


/**
 * Sort comments by thread
 * 
 * 
 * @param    $comments    An array of comments
 * @return   array        The sorted array
 * @since    0.2
 */
function EZComments_threadComments($comments)
{
    return EZComments_displayChildren($comments, -1, 0);
}


/**
 * Get all child comments
 * 
 * This function returns all child comments to a given comment.
 * It is called recursively
 * 
 * @param    $comments    An array of comments
 * @param    $id          The id of the parent comment
 * @param    $level       The indentation level 
 * @return   array        The sorted array
 * @access   private
 * @since    0.2
 */
function EZComments_displayChildren($comments, $id, $level)
{
    $comments2 = array();
    foreach ($comments as $comment) {
        if ($comment['replyto'] == $id) {
            $comment['level'] = $level;
            $comments2[] = $comment;
            $comments2 = array_merge($comments2, 
                                     EZComments_displayChildren($comments, $comment['id'], $level+1));
        }
    }
    return $comments2;
}

?>
