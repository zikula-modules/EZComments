<?php
// LICENSE
// 
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU Lesser General Public
// License along with this library; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// For questions, help, comments, discussion, etc., please visit
// the EZComments homepage http://lottasophie.sourceforge.net, 
// the PostNuke homepage http://www.postnuke.com, or the German 
// PostNuke Support page http://www.post-nuke.net
//
// @link http://lottasophie.sourceforge.net
// @copyright 2001,2002 ispi of Lincoln, Inc.
// @author Joerg Napp <jnapp@users.sourceforge.net>
// @package EZComments
// @version 0.2


/**
 * Main administration function
 * 
 * This function provides the main administration interface to the comments
 * module. 
 * 
 * @returns output
 * @return output the admin interface
 */
function EZComments_admin_main() 
{
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
		return _EZCOMMENTS_NOAUTH;
	} 

    // Create output object
    $pnRender =& new pnRender('EZComments');

    // As admin output changes often, we do not want caching.
    $pnRender->caching = false;

	// assign the module vars
	$pnRender->assign(pnModGetVar('EZComments'));
	// presentation values
	$itemsperpage = pnModGetVar('EZComments', 'itemsperpage');
    $startnum = pnVarCleanFromInput('startnum');

	// call the api to get all current comments
	$items = pnModAPIFunc('EZComments',
            			  'user',
            			  'getall',
                          array('startnum' => $startnum,
                                'numitems' => $itemsperpage));

	if ($items === false) {
		return _EZCOMMENTS_FAILED;
	} 

	// loop through each item adding the relevant links
	$comments = array();
	foreach ($items as $item) {
		$options = array();
		if (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$id", ACCESS_EDIT)) {
			$options[] = array('url'   => pnModURL('EZComments', 'admin', 'modify', array('id' => $item['id'])),
							   'title' => _EDIT);
			if (pnSecAuthAction(0, 'EZComments::', "$modname:$objectid:$id", ACCESS_DELETE)) {
                $options[] = array('url'   => pnModURL('EZComments', 'admin', 'delete', array('id' => $item['id'])),
                                   'title' => _DELETE);
			}
		}
		$item['options'] = $options;
		$comments[] = $item;
	}

	// assign the items to the template
	$pnRender->assign('items', $comments);

    // assign the values for the smarty plugin to produce a pager in case of there
    // being many items to display.
    //
    // Note that this function includes another user API function.  The
    // function returns a simple count of the total number of items in the item
    // table so that the pager function can do its job properly
    $pnRender->assign('pager', array('numitems'     => pnModAPIFunc('EZComments',
                                                                    'user',
                                                                    'countitems'),
                                     'itemsperpage' => $itemsperpage));

	// Return the output
	return $pnRender->fetch('ezcomments_admin_view.htm');
}

/**
 * modify an item
 *
 * This is a standard function that is called whenever an administrator
 * wishes to modify a current module item
 *
 * @author       The PostNuke Development Team
 * @param        tid          the id of the item to be modified
 * @return       output       the modification page
 */
function EZComments_admin_modify($args)
{
	// get our input
    list($id,
         $objectid)= pnVarCleanFromInput('id',
                                         'objectid');

	// extract any input passed directly to the function
    extract($args);

	// check for a generic object id
    if (!empty($objectid)) {
        $id = $objectid;
    }

    // The user API function is called.  This takes the item ID which we
    // obtained from the input and gets us the information on the appropriate
    // item.  If the item does not exist we post an appropriate message and
    // return
    $item = pnModAPIFunc('EZComments', 'user', 'get', array('id' => $id));

    if (!$item) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Security check 
    if (!pnSecAuthAction(0, 'EZComments::', "::$id", ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('EZComments');

    // As Admin output changes often, we do not want caching.
    $pnRender->caching = false;

    // Add a hidden variable for the item id.  This needs to be passed on to
    // the update function so that it knows which item for which item to carry
    // out the update
    $pnRender->assign('id', $id);

    // For the assignment of name and number we can just assign the associative
    // array $item.
    $pnRender->assign($item);

    // Return the output that has been generated by this function
    return $pnRender->fetch('ezcomments_admin_modify.htm');
}

/**
 * Update the item
 *
 * This is a standard function that is called with the results of the
 * form supplied by Example_admin_modify() to update a current item
 *
 * @author       The PostNuke Development Team
 * @param        id              the id of the item to be modified
 * @param        subject         the subject of the item to be updated
 * @param        comment         the main text of the item to be updated
 */
function EZComments_admin_update($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    list($id,
         $objectid,
         $subject,
         $comment) = pnVarCleanFromInput('id',
                                         'objectid',
                                         'subject',
                                         'comment');

	// extract any input passed directly to the function
    extract($args);

	// check for a generic object id
    if (!empty($objectid)) {
        $id = $objectid;
    }

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        pnRedirect(pnModURL('EZComments', 'admin', 'main'));
        return true;
    }

    // Notable by its absence there is no security check here.  This is because
    // the security check is carried out within the API function and as such we
    // do not duplicate the work here

    // The API function is called.
    if(pnModAPIFunc('EZComments', 'admin', 'update',
                    array('id' => $id, 'subject' => $subject, 'comment' => $comment))) {
        // Success
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_UPDATESUCCEDED));
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    pnRedirect(pnModURL('EZComments', 'admin', 'main'));

    // Return
    return true;
}

/**
 * delete item
 *
 * This is a standard function that is called whenever an administrator
 * wishes to delete a current module item.  Note that this function is
 * the equivalent of both of the modify() and update() functions above as
 * it both creates a form and processes its output.  This is fine for
 * simpler functions, but for more complex operations such as creation and
 * modification it is generally easier to separate them into separate
 * functions.  There is no requirement in the PostNuke MDG to do one or the
 * other, so either or both can be used as seen appropriate by the module
 * developer
 *
 * @author       The PostNuke Development Team
 * @param        id            the id of the item to be deleted
 * @param        confirmation   confirmation that this item can be deleted
 */
function EZComments_admin_delete($args)
{
    // Get parameters from whatever input we need. 
    list($id,
         $objectid,
         $confirmation) = pnVarCleanFromInput('id',
                                              'objectid',
                                              'confirmation');

	// extract any input passed directly to the function
    extract($args);

	// check for a generic object id
    if (!empty($objectid)) {
        $id = $objectid;
    }

    // The user API function is called.
    $item = pnModAPIFunc('EZComments', 'user', 'get', array('id' => $id));

    if (!$item) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Security check
    if (!pnSecAuthAction(0, 'EZComments::', "::$id", ACCESS_DELETE)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet - display a suitable form to obtain confirmation
        // of this action from the user

        // Create output object - this object will store all of our output so that
        // we can return it easily when required
        $pnRender =& new pnRender('EZComments');

        // As Admin output changes often, we do not want caching.
        $pnRender->caching = false;

        // Add a hidden field for the item ID to the output
        $pnRender->assign('id', $id);

        // Return the output that has been generated by this function
        return $pnRender->fetch('ezcomments_admin_delete.htm');
    }

    // If we get here it means that the user has confirmed the action
    // Confirm authorisation code.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        pnRedirect(pnModURL('EZComments', 'admin', 'main'));
        return true;
    }

    // The API function is called. 
    if (pnModAPIFunc('EZComments', 'admin', 'delete', array('id' => $id))) {
        // Success
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_DELETESUCCEDED));
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    pnRedirect(pnModURL('EZComments', 'admin', 'main'));

    // Return
    return true;
}

function EZComments_admin_modifyconfig() 
{
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
		return _EZCOMMENTS_NOAUTH;
	} 

    // Create output object
    $pnRender =& new pnRender('EZComments');

    // As admin output changes often, we do not want caching.
    $pnRender->caching = false;

	// assign the module vars
	$pnRender->assign(pnModGetVar('EZComments'));

	// assign all available template sets
	$pnRender->assign('templates', pnModAPIFunc('EZComments', 'user', 'gettemplates'));

	// Return the output
	return $pnRender->fetch('ezcomments_admin_modifyconfig.htm');
}

/**
 * Update the settings
 * 
 * This is the function that is called with the results of the
 * form supplied by EZComments_admin_main to alter the admin settings
 * 
 * @param $MailToAdmin full pathname of Smarty class
 */
function EZComments_admin_updateconfig($args)
{
	if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
		pnRedirect(pnModURL('EZComments', 'admin', 'main'));
		return true;
	} 

    list($MailToAdmin, $template, $itemsperpage) = 
	    pnVarCleanFromInput('MailToAdmin', 'template', 'itemsperpage');
	extract($args);

    if (!isset($MailToAdmin)) {
        $MailToAdmin = 0;
    }
	pnModSetVar('EZComments', 'MailToAdmin', $MailToAdmin);
	
	if (!isset($template)) {
		$template = 'AllOnOnePage';
	}
	pnModSetVar('EZComments', 'template', $template);

	if (!isset($itemsperpage)) {
		$itemsperpage = 25;
	}
	pnModSetVar('EZComments', 'itemsperpage', $itemsperpage);

	pnRedirect(pnModURL('EZComments', 'admin', 'main'));
	return true;
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
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
		return _EZCOMMENTS_NOAUTH;
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
        pnSessionSetVar('statusmsg', _EZCOMMENTS_MIGRATE_NOTHINGTODO);
        pnRedirect(pnModURL('EZComments', 'admin'));
        return true;
    } 

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('EZComments');

    // As Admin output changes often, we do not want caching.
    $pnRender->caching = false;

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
    if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        return _EZCOMMENTS_NOAUTH;
    } 
    // Authentication key
    if (!pnSecConfirmAuthKey()) {
        // return _EZCOMMENTS_NOAUTH;
    } 
	// Parameter
	$migrate = pnVarCleanFromInput('EZComments_migrate');
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
	pnRedirect(pnModURL('EZComments', 'admin', 'migrate'));
	return true;
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
    if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        return _EZCOMMENTS_NOAUTH;
    } 
    if (!pnModAPILoad('EZComments', 'admin')) {
        return _EZCOMMENTS_LOADFAILED;
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
    	pnSessionSetVar('statusmsg', _EZCOMMENTS_CLEANUP_NOTHINGTODO);
    	pnRedirect(pnModURL('EZComments', 'admin', 'main'));
        return true;
    } 

    $selectitems = array();
    foreach ($orphanedmods as $mod) {
        $selectitems[$mod] = $mod;
    }

	$pnRender =& new pnRender('EZComments');
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
    if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        return _EZCOMMENTS_NOAUTH;
    } 
    // Authentication key
    if (!pnSecConfirmAuthKey()) {
        // return _EZCOMMENTS_NOAUTH;
    } 
    // API
    if (!pnModAPILoad('EZComments', 'admin')) {
        return _EZCOMMENTS_LOADFAILED;
    } 

    $module = pnVarCleanFromInput('EZComments_module');
    if (!isset($module)) {
        return _EZCOMMENTS_MODSARGSERROR;
    } 

    if (!pnModAPIFunc('EZComments', 'admin', 'deleteall', compact('module'))) {
        return _EZCOMMENTS_GENERALFAILIURE;
    } 

    pnRedirect(pnModURL('EZComments', 'admin', 'cleanup'));
    return true;
} 

?>