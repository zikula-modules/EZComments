<?php
/**
 * $Id: common.php 459 2008-03-15 12:19:55Z quan $
 * 
 * * EZComments *
 * 
 * Functions that are needed by admin and user interface should be placed in here
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
 * @author      Mark West <markwest at zikula dot org>
 * @author      Jean-Michel Vedrine
 * @author		Florian Schieﬂl <florian.schiessl at ifs-net.de>
 * @version     1.5
 * @link        http://code.zikula.org/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Zikula
 * @subpackage  EZComments
 */

/**
 * process multiple comments
 *
 * This function process the comments selected in the admin view page.
 * Multiple comments may have thier state changed or be deleted
 *
 * @author       The Zikula Development Team
 * @param        Comments   the ids of the items to be deleted
 * @param        confirmation  confirmation that this item can be deleted
 * @param        redirect      the location to redirect to after the deletion attempt
 * @return       bool          true on sucess, false on failure
 */
function ezc_processSelected($args) 
{
    // Get parameters from whatever input we need. 
    list($comments, $action) = FormUtil::getPassedValue('comments', 'action');

    // extract any input passed directly to the function
    extract($args);

  	// get the type of function call: admin or user
  	$type = FormUtil::getPassedValue('type','user');
  	if (($type != "admin") && ($type != "user")) $type = "user";

    // If we get here it means that the user has confirmed the action
    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('EZComments', 'admin', 'main'));
    }

    // loop round each comment deleted them in turn 
    foreach ($comments as $comment) {
		switch(strtolower($action)) {
			case 'delete':
				// The API function is called. 
				if (pnModAPIFunc('EZComments', 'admin', 'delete', array('id' => $comment))) {
					// Success
					LogUtil::registerStatus(_DELETESUCCEDED);
				}
				break;
			case 'approve':
				if (pnModAPIFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 0))) {
					// Success
					LogUtil::registerStatus(_UPDATESUCCEDED);
				}
				break;
			case 'hold':
				if (pnModAPIFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 1))) {
					// Success
					LogUtil::registerStatus(_UPDATESUCCEDED);
				}
				break;
			case 'reject':
				if (pnModAPIFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 2))) {
					// Success
					LogUtil::registerStatus(_UPDATESUCCEDED);
				}
				break;
		}
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    if (!empty($redirect)) {
        return pnRedirect($redirect);
    } else {
        if ($type == "user") return pnRedirect(pnModURL('EZComments', 'user', 'main'));
        else return pnRedirect(pnModURL('EZComments', 'admin', 'main'));
    }

}

/**
 * modify a comment
 *
 * This is a standard function that is called whenever an administrator
 * wishes to modify a comment
 *
 * @author       The Zikula Development Team
 * @param        tid          the id of the comment to be modified
 * @return       string       the modification page
 */
function ezc_modify($args) 
{
  	// get the type of function call: admin or user
  	$type = FormUtil::getPassedValue('type','user');
  	if (($type != "admin") && ($type != "user")) $type = "user";

    // get our input
    $id = FormUtil::getPassedValue('id', isset($args['id']) ? $args['id'] : null,             'GETPOST');

    // Security check 
    $securityCheck = pnModAPIFunc('EZComments','user','checkPermission',array(
					'module'	=> '',
					'objectid'	=> '',
					'commentid'	=> $id,
					'level'		=> ACCESS_EDIT			));
    if(!$securityCheck) {
      	$redirect = base64_decode(FormUtil::getPassedValue('redirect'));
      	if (!isset($redirect)) $redirect = 'index.php';
        return LogUtil::registerPermissionError($redirect);
    }
    
    // load edithandler class from file
    if ($type == "user") Loader::requireOnce('modules/EZComments/pnincludes/ezcomments_user_modifyhandler.class.php');
    else Loader::requireOnce('modules/EZComments/pnincludes/ezcomments_admin_modifyhandler.class.php');

    // Create pnForm output object
    $pnf = FormUtil::newpnForm('EZComments');

    // Return the output that has been generated by this function
    if ($type == "user") return $pnf->pnFormExecute('ezcomments_user_modify.htm', new EZComments_user_modifyhandler());
    else return $pnf->pnFormExecute('ezcomments_admin_modify.htm', new EZComments_admin_modifyhandler());
}
?>