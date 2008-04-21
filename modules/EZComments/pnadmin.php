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
 * @author		Florian Schieﬂl <florian.schiessl at ifs-net.de>
 * @version     1.5
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

/**
 * Main administration function
 * 
 * This function provides the main administration interface to the comments
 * module. 
 * 
 * @return string output the admin interface
 */
function EZComments_admin_main() 
{
    // Security check 
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }

    // get the status filter
    $status = FormUtil::getPassedValue('status', -1, 'GETPOST');
    if (!isset($status) || !is_numeric($status) || $status < -1 || $status > 2) {
        $status = -1;
    }

    // presentation values
    $showall = (bool)FormUtil::getPassedValue('showall', true, 'GETPOST');
    $itemsperpage = $showall == true ? -1 : pnModGetVar('EZComments', 'itemsperpage');
    $startnum = FormUtil::getPassedValue('startnum', null, 'GETPOST');
    if (!isset($showall)) {
         $showall = false;
    }

    // Create output object
    $pnRender = pnRender::getInstance('EZComments', false);

    // assign the module vars
    $pnRender->assign(pnModGetVar('EZComments'));

    // call the api to get all current comments
    $items = pnModAPIFunc('EZComments',
                          'user',
                          'getall',
                          array('startnum' => $showall == true ? true : $startnum,
                                'numitems' => $itemsperpage,
                                'status'   => $status));
    if ($items === false) {
        return LogUtil::registerError(_EZCOMMENTS_FAILED);
    } 

    // loop through each item adding the relevant links
    $comments = array();
    foreach ($items as $item) {
	    $securityCheck = pnModAPIFunc('EZComments','user','checkPermission',array(
					'module'	=> $item['mod'],
					'objectid'	=> $item['objectid'],
					'commentid'	=> $item['id'],
					'level'		=> ACCESS_EDIT			));
        if ($securityCheck) {
	        $options = array(array('url' => $item['url'] . '#comments',
	                               'title' => _VIEW)); 
            $options[] = array('url'   => pnModURL('EZComments', 'admin', 'modify', array('id' => $item['id'])),
                               'title' => _EDIT);
            $item['options'] = $options;
            $comments[] = $item;
	    }
    }

    // assign the items to the template
    $pnRender->assign('items', $comments);

    // assign values for the filters
    $pnRender->assign('status', $status);
    $pnRender->assign('showall', $showall);

    // assign the values for the smarty plugin to produce a pager
    $pnRender->assign('pager', array('numitems'     => pnModAPIFunc('EZComments', 'user', 'countitems', array('status' => $status)),
                                     'itemsperpage' => $itemsperpage));

    // Return the output
    return $pnRender->fetch('ezcomments_admin_view.htm');
}

/**
 * modify a comment
 *
 * This is a standard function that is called whenever an administrator
 * wishes to modify a comment
 *
 * @author       The PostNuke Development Team
 * @param        tid          the id of the comment to be modified
 * @return       string       the modification page
 */
function EZComments_admin_modify($args)
{
    Loader::requireOnce('modules/EZComments/pnincludes/common.php');
	return ezc_modify($args);
}

/**
 * delete item
 *
 * This is a standard function that is called whenever an administrator
 * wishes to delete a current module item.
 *
 * @author       The PostNuke Development Team
 * @param        id            the id of the item to be deleted
 * @param        redirect      the location to redirect to after the deletion attempt
 * @return       bool            true on sucess, false on failure
 */
function EZComments_admin_delete($args)
{
    // delete functionalityx has been moved to the modify function which uses pnForms.
    // We need this function for backwards compatibility only

    // Get parameters from whatever input we need. 
    $id             = FormUtil::getPassedValue('id',       isset($args['id']) ? $args['id'] : null, 'GETPOST');
    $objectid       = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'GETPOST');
    $redirect       = FormUtil::getPassedValue('redirect', isset($args['redirect']) ? $args['redirect'] : '', 'GETPOST');
    return pnRedirect(pnModURL('EZComments', 'admin', 'modify', 
                               array('id'       => $id,
                                     'objectid' => $objectid,
                                     'redirect' => $redirect)));
}

/**
 * process multiple comments
 *
 * This function process the comments selected in the admin view page.
 * Multiple comments may have thier state changed or be deleted
 *
 * @author       The PostNuke Development Team
 * @param        Comments   the ids of the items to be deleted
 * @param        confirmation  confirmation that this item can be deleted
 * @param        redirect      the location to redirect to after the deletion attempt
 * @return       bool          true on sucess, false on failure
 */
function EZComments_admin_processselected($args)
{
    // Get parameters from whatever input we need. 
    list($comments, $action) = pnVarCleanFromInput('comments', 'action');

    // extract any input passed directly to the function
    extract($args);

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
        return pnRedirect(pnModURL('EZComments', 'admin', 'main'));
    }
}

/**
 * Modify configuration
 *
 * This is a standard function to modify the configuration parameters of the
 * module
 *
 * @author       The PostNuke Development Team
 * @return       string       The configuration page
 */
function EZComments_admin_modifyconfig() 
{
    // Security check 
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }
//    if(!pnModAPIFunc('EZComments','user','checkPermission',array('module'))) {
//        return LogUtil::registerPermissionError('index.php');
//    }

    // load edithandler class from file
    Loader::requireOnce('modules/EZComments/pnincludes/ezcomments_admin_modifyconfighandler.class.php');

    // Create pnForm output object
    $pnf = FormUtil::newpnForm('EZComments');

    // Return the output that has been generated by this function
    return $pnf->pnFormExecute('ezcomments_admin_modifyconfig.htm', new EZComments_admin_modifyconfighandler());
}

/**
 * Migration functionality
 * 
 * This function provides a common interface to migration scripts.
 * The migration scripts will upgrade from different other modules 
 * (like NS-Comments, Reviews, My_eGallery, ...) to EZComments.
 * 
 * @return output the migration interface
 */
function EZComments_admin_migrate()
{
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }

    $migrated=unserialize(pnModGetVar('EZComments', 'migrated'));
    $d = opendir('modules/EZComments/pnmigrateapi');
    $selectitems = array();
    while($f = readdir($d)) {
        if(substr($f, -3, 3) == 'php') {
            if (!isset($migrated[substr($f, 0, strlen($f) -4)]) || !$migrated[substr($f, 0, strlen($f) -4)]) {
                $selectitems[substr($f, 0, strlen($f) -4)] = substr($f, 0, strlen($f) -4);
            }
        }
    }
    closedir($d);

    if (!$selectitems) {
        LogUtil::registerStatus(_EZCOMMENTS_MIGRATE_NOTHINGTODO);
        return pnRedirect(pnModURL('EZComments', 'admin'));
    } 

    // Create output object
    $pnRender = pnRender::getInstance('EZComments', false);

    // assign the migratation options
     $pnRender->assign('selectitems', $selectitems);

    // Return the output that has been generated by this function
    return $pnRender->fetch('ezcomments_admin_migrate.htm');
}


/**
 * Do the migration
 * 
 * This is the function that is called to do the actual
 * migration.
 * 
 * @param $migrate The plugin to do the migration
 */
function EZComments_admin_migrate_go()
{
    // Permissions
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }

    // Authentication key
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('EZComments', 'admin', 'main'));
    } 
    // Parameter
    $migrate = pnVarCleanFromInput('migrate');
    if (!isset($migrate)){ 
        return _EZCOMMENTS_MODSARGSERROR;
    }

    // Eintrag in Datenbank
    $migrated=unserialize(pnModGetVar('EZComments', 'migrated'));

    // call the migration function
    if (pnModAPIFunc('EZComments', 'migrate', $migrate)) {
        $migrated[$migrate] = true;
        pnModSetVar('EZComments', 'migrated', serialize($migrated));
    }
    return pnRedirect(pnModURL('EZComments', 'admin', 'migrate'));
}


/**
 * Cleanup functionality
 * 
 * This is the interface to the Cleanup functionality.
 * When a Module is deleted, EZComments doesn't know about
 * this. Thus, any comments for this module stay in the database.
 * With this functionality you can delete these comments.
 * 
 * @return output the cleanup interface
 */
function EZComments_admin_cleanup()
{
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }

    // build a simple array of all available modules
    $mods = pnModGetAllMods();
    $allmods = array();
    foreach ($mods as $mod) {
        $allmods[] = $mod['name'];
    } 

    $usedmods = pnModAPIFunc('EZComments', 'admin', 'getUsedModules');

    $orphanedmods = array_diff($usedmods, $allmods);

    if (!$orphanedmods) {
        LogUtil::registerStatus(_EZCOMMENTS_CLEANUP_NOTHINGTODO);
        return pnRedirect(pnModURL('EZComments', 'admin', 'main'));
    } 

    $selectitems = array();
    foreach ($orphanedmods as $mod) {
        $selectitems[$mod] = $mod;
    }

    $pnRender = pnRender::getInstance('EZComments', false);
    $pnRender->assign('selectitems', $selectitems);

    return $pnRender->fetch('ezcomments_admin_cleanup.htm');
} 


/**
 * Do the migration
 * 
 * This is the function that is called to do the actual
 * deletion of orphaned comments.
 * 
 * @param  $EZComments_module The Module to delete for
 */
function EZComments_admin_cleanup_go()
{ 
    // Permissions
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }

    // Authentication key
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('EZComments', 'admin', 'main'));
    } 

    $module = pnVarCleanFromInput('EZComments_module');
    if (!isset($module)) {
        return _EZCOMMENTS_MODSARGSERROR;
    } 

    if (!pnModAPIFunc('EZComments', 'admin', 'deleteall', compact('module'))) {
        return _EZCOMMENTS_GENERALFAILIURE;
    } 

    return pnRedirect(pnModURL('EZComments', 'admin', 'cleanup'));
} 

/**
 * purge comments
 *
 * @author       The PostNuke Development Team
 * @param        confirmation  confirmation that this item can be deleted
 * @param        redirect      the location to redirect to after the deletion attempt
 * @return       bool          true on sucess, false on failure
 */
function EZComments_admin_purge($args)
{
    // Get parameters from whatever input we need. 
    list($purgepending,
         $purgerejected,
         $confirmation) = pnVarCleanFromInput('purgepending',
                                              'purgerejected',
                                              'confirmation');

    // extract any input passed directly to the function
    extract($args);

    // Security check
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError('index.php');
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet - display a suitable form to obtain confirmation
        // of this action from the user

        // Create output object - this object will store all of our output so that
        // we can return it easily when required
        $pnRender = pnRender::getInstance('EZComments', false);

        // Return the output that has been generated by this function
        return $pnRender->fetch('ezcomments_admin_purge.htm');
    }

    // If we get here it means that the user has confirmed the action
    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('EZComments', 'admin', 'main'));
    }

    // The API function is called. 
    if (pnModAPIFunc('EZComments', 'admin', 'purge', 
        array('purgepending' => $purgepending, 'purgerejected' => $purgerejected))) {
        // Success
        LogUtil::registerStatus(_DELETESUCCEDED);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('EZComments', 'admin', 'main'));
}

/**
 * display commenting stats
 *
 * @author Mark West
 * @return string html output
 */
function EZComments_admin_stats($args)
{
	// security check
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }

    // Create output object
    $pnRender = pnRender::getInstance('EZComments', false);

    // assign the module vars
    $pnRender->assign(pnModGetVar('EZComments'));

	// get a list of the hooked modules
	$hookedmodules = pnModAPIFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname'=> 'EZComments'));

	// get a list of comment stats by module
	$commentstats = array();
	foreach ($hookedmodules as $mod => $hooktype) {
		$commentstat = array();
		$modinfo = pnModGetInfo(pnModGetIDFromName($mod));
		$commentstat = $modinfo;
		$commentstat['modid'] = pnModGetIDFromName($mod);
		$commentstat['approvedcomments'] = pnModAPIFunc('EZComments', 'user', 'countitems', array('status' => 0, 'mod' => $modinfo['name']));
		$commentstat['pendingcomments'] = pnModAPIFunc('EZComments', 'user', 'countitems', array('status' => 1, 'mod' => $modinfo['name']));
		$commentstat['rejectedcomments'] = pnModAPIFunc('EZComments', 'user', 'countitems', array('status' => 2, 'mod' => $modinfo['name']));
		$commentstat['totalcomments'] = $commentstat['approvedcomments'] + $commentstat['pendingcomments'] + $commentstat['rejectedcomments'];
		$commentstats[] = $commentstat;
	}
	$pnRender->assign('commentstats', $commentstats);

    // Return the output
    return $pnRender->fetch('ezcomments_admin_stats.htm');

}

/**
 * display all comments for a module
 *
 * @author Mark West
 * @return string html output
 */
function EZComments_admin_modulestats()
{
	// security check
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }

	// get our input
	$mod = pnVarCleanFromInput('mod');

    // Create output object
    $pnRender = pnRender::getInstance('EZComments', false);

    // assign the module vars
    $pnRender->assign(pnModGetVar('EZComments'));

	// get a list of comments
	$modulecomments = pnModAPIFunc('EZComments', 'user', 'getallbymodule', array('mod' => $mod));

	// assign the module info
	$modid = pnModGetIDFromName($mod);
	$pnRender->assign('modid', $modid);
	$pnRender->assign(pnModGetInfo($modid));

	// get a list of comment stats by module
	$commentstats = array();
	foreach ($modulecomments as $modulecomment) {
		$commentstat = $modulecomment;
		$commentstat['approvedcomments'] = pnModAPIFunc('EZComments', 'user', 'countitems', array('status' => 0, 'mod' => $mod, 'objectid' => $modulecomment['objectid']));
		$commentstat['pendingcomments'] = pnModAPIFunc('EZComments', 'user', 'countitems', array('status' => 1, 'mod' => $mod, 'objectid' => $modulecomment['objectid']));
		$commentstat['rejectedcomments'] = pnModAPIFunc('EZComments', 'user', 'countitems', array('status' => 2, 'mod' => $mod, 'objectid' => $modulecomment['objectid']));
		$commentstat['totalcomments'] = $modulecomment['count'];
		$commentstats[] = $commentstat;
	}
	$pnRender->assign('commentstats', $commentstats);

    // Return the output
    return $pnRender->fetch('ezcomments_admin_modulestats.htm');
}

/**
 * delete all comments attached to a module
 *
 * @author       Mark West
 * @param        modname       the name of the module to delete all comments for
 * @param        confirmation  confirmation that this item can be deleted
 * @return       bool          true on sucess, false on failure
 */
function EZComments_admin_deletemodule($args)
{
    // Get parameters from whatever input we need. 
    list($modid,
         $confirmation) = pnVarCleanFromInput('modid',
                                              'confirmation');

    // extract any input passed directly to the function
    extract($args);

    // Security check
    if(!SecurityUtil::checkPermission('EZComments::', $mod . '::', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError('index.php');
    }

	// get our module info
	if (!empty($modid)) {
		$modinfo =  pnModGetInfo($modid);
	}

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet

        // Create output object
        $pnRender = pnRender::getInstance('EZComments', false);

        // Add a hidden field for the item ID to the output
        $pnRender->assign('modid', $modid);
		$pnRender->assign($modinfo);

        // Return the output that has been generated by this function
        return $pnRender->fetch('ezcomments_admin_deletemodule.htm');
    }

    // If we get here it means that the user has confirmed the action
    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('EZComments', 'admin', 'main'));
    }

    // The API function is called. 
	// note: the api call is a little different here since we'll really calling a hook function that will 
	// normally be executed when a module is deleted. The extra nesting of the modname inside an extrainfo
	// array reflects this
    if (pnModAPIFunc('EZComments', 'admin', 'deletemodule', array('extrainfo' => array('module' => $modinfo['name'])))) {
        // Success
        LogUtil::registerStatus(_DELETESUCCEDED);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('EZComments', 'admin', 'main'));
}

/**
 * delete all comments attached to a module
 *
 * @author       Mark West
 * @param        modname       the name of the module to delete all comments for
 * @param        confirmation  confirmation that this item can be deleted
 * @return       bool          true on sucess, false on failure
 */
function EZComments_admin_deleteitem($args)
{
    // Get parameters from whatever input we need. 
    list($mod,
		 $objectid,
         $confirmation) = pnVarCleanFromInput('mod',
											  'objectid',
                                              'confirmation');

    // extract any input passed directly to the function
    extract($args);

	// input check
	if (!isset($mod) || !is_string($mod) || !isset($objectid) || !is_numeric($objectid)) {
		return LogUtil::registerError(_MODARGSERROR);
		return pnRedirect(pnModURL('EZComments', 'admin', 'main'));
	}

    // Security check
    if(!SecurityUtil::checkPermission('EZComments::', $mod . ':' . $objectid . ':', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError('index.php');
    }

	// get our module info
	if (!empty($mod)) {
		$modinfo =  pnModGetInfo(pnModGetIDFromName($mod));
	}

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet

        // Create output object
        $pnRender = pnRender::getInstance('EZComments', false);

        // Add a hidden field for the item ID to the output
        $pnRender->assign('objectid', $objectid);
		$pnRender->assign($modinfo);

        // Return the output that has been generated by this function
        return $pnRender->fetch('ezcomments_admin_deleteitem.htm');
    }

    // If we get here it means that the user has confirmed the action
    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('EZComments', 'admin', 'main'));
    }

    // The API function is called. 
	// note: the api call is a little different here since we'll really calling a hook function that will 
	// normally be executed when a module is deleted. The extra nesting of the modname inside an extrainfo
	// array reflects this
    if (pnModAPIFunc('EZComments', 'admin', 'deletebyitem', array('mod' => $modinfo['name'], 'objectid' => $objectid))) {
        // Success
        LogUtil::registerStatus(_DELETESUCCEDED);
    }

    return pnRedirect(pnModURL('EZComments', 'admin', 'main'));
}

/**
 * delete all comments attached to a module
 *
 * @author       Mark West
 * @param        modname       the name of the module to delete all comments for
 * @param        confirmation  confirmation that this item can be deleted
 * @return       bool          true on sucess, false on failure
 */
function EZComments_admin_applyrules($args)
{
    // Get parameters from whatever input we need. 
    $confirmation = pnVarCleanFromInput('confirmation');
    $allcomments = pnVarCleanFromInput('allcomments');
    $status = pnVarCleanFromInput('status');

    // extract any input passed directly to the function
    extract($args);

    // Security check
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError('index.php');
    }

	// get our module info
	if (!empty($mod)) {
		$modinfo =  pnModGetInfo(pnModGetIDFromName($mod));
	}

    // Create output object
    $pnRender = pnRender::getInstance('EZComments', false);

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet

        // assign the status flags
        $pnRender->assign('statuslevels', array('1' => _EZCOMMENTS_PENDING, '2' => _EZCOMMENTS_REJECTED,'0' => _EZCOMMENTS_APPROVED));

        // Return the output that has been generated by this function
        return $pnRender->fetch('ezcomments_admin_applyrules_form.htm');
    }

    // If we get here it means that the user has confirmed the action
    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('EZComments', 'admin', 'main'));
    }

    // get the matching comments
    $args = array();
    if (!$allcomments) {
        $args['status'] = $status;
    }
    $comments = pnModAPIFunc('EZComments', 'user', 'getall', $args);

    // these processes could take some time
    set_time_limit(0);

    // apply the moderation filter to each comment
    $moderatedcomments = array();
    $blacklistedcomments = array();
    foreach ($comments as $comment) {
        $subjectstatus = _EZComments_userapi_checkcomment($comment['subject']);
        $commentstatus = _EZComments_userapi_checkcomment($comment['comment']);
        // akismet
        if (pnModAvailable('akismet') && pnModGetVar('EZComments', 'akismet')
		    && pnModAPIFunc('akismet', 'user', 'isspam', 
                              array('author' => ($comment['uid'] > 0) ?  pnUserGetVar('uname', $comment['uid']) : $comment['anonname'],
                                    'authoremail' => ($comment['uid'] > 0) ? pnUserGetVar('email', $comment['uid']) : $comment['anonmail'],
                                    'authorurl' => ($comment['uid'] > 0) ? pnUserGetVar('url', $comment['uid']) : $comment['anonwebsite'],
                                    'content' => $comment['comment'],
                                    'permalink' => $comment['url']))) {
            $akismetstatus = pnModGetVar('EZComments', 'akismetstatus');
        } else {
            $akismetstatus = $commentstatus;
        }
        if (($subjectstatus == 0 && $commentstatus == 0 && $akismetstatus == 0) && $comment['status'] != 0) {
            continue;
        }
        $options = array(array('url' => $comment['url'] . '#comments',
                               'title' => _VIEW));
        if (SecurityUtil::checkPermission('EZComments::', "$comment[mod]:$comment[objectid]:$comment[id]", ACCESS_EDIT)) {
            $options[] = array('url'   => pnModURL('EZComments', 'admin', 'modify', array('id' => $comment['id'])),
                               'title' => _EDIT);
        }
        $comment['options'] = $options;
        if (($subjectstatus == 1 || $commentstatus == 1 || $akismetstatus == 1) && $comment['status'] != 1) {
            $moderatedcomments[] = $comment;
        }
        if (($subjectstatus == 2 || $commentstatus == 2 || $akismetstatus == 2) && $comment['status'] != 2) {
            $blacklistedcomments[] = $comment;
        }
    }

    // for the first confirmation display a results page to the user
    if (!empty($confirmation) && $confirmation == 1) {
        $pnRender->assign('moderatedcomments', $moderatedcomments);
        $pnRender->assign('blacklistedcomments', $blacklistedcomments);
        $pnRender->assign('status', $status);
        $pnRender->assign('allcomments', $allcomments);

        // Return the output that has been generated by this function
        return $pnRender->fetch('ezcomments_admin_applyrules_results.htm');
    }

    if (!empty($confirmation) && $confirmation == 2) {
        foreach ($moderatedcomments as $comment) {
            $comment['status'] = 1;
            pnModAPIFunc('EZComments', 'admin', 'update', $comment);
        }
        foreach ($blacklistedcomments as $comment) {
            $comment['status'] = 2;
            pnModAPIFunc('EZComments', 'admin', 'update', $comment);
        }
        LogUtil::registerStatus('New comment rules applied');
        return pnRedirect(pnModURL('EZComments', 'admin'));
    }
}

