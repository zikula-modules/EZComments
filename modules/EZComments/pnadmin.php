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
	// Hardcoded -- move this to an module variable?!?
	$itemsperpage = 10;
    $startnum = pnVarCleanFromInput('startnum');

	// call the api to get all current comments
	$items = pnModAPIFunc('EZComments',
            			  'admin',
            			  'getall',
                          array('startnum' => $startnum,
                                'numitems' => $itemsperpage));

	if ($items === false) {
		return _EZCOMMENTS_FAILED;
	} 

	// assign the items to the template
	$pnRender->assign('items', $items);

    // assign the values for the smarty plugin to produce a pager in case of there
    // being many items to display.
    //
    // Note that this function includes another user API function.  The
    // function returns a simple count of the total number of items in the item
    // table so that the pager function can do its job properly
    $pnRender->assign('pager', array('numitems'     => pnModAPIFunc('EZComments',
                                                                    'admin',
                                                                    'countitems'),
                                     'itemsperpage' => $itemsperpage));

	// Return the output
	return $pnRender->fetch('ezcomments_admin_view.htm');
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

    $MailToAdmin = pnVarCleanFromInput('MailToAdmin');
	extract($args);

    if (!isset($MailToAdmin)) {
        $MailToAdmin = 0;
    }

	pnModSetVar('EZComments', 'MailToAdmin', $MailToAdmin);
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
	$d = opendir('modules/EZComments/migrate');
    $selectitems = array();
	while($f = readdir($d)) {
    	if(substr($f, -3, 3) == 'php') {
			if (!isset($migrated[$f]) || !$migrated[$f]) {
		        $selectitems[$f] = substr($f, 0, strlen($f) -4);
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

	// don't issue a warning when the file does not exist!
	// TIPP: While testing new migration plugins, set this to require!
	@include "modules/EZComments/migrate/$migrate";
	if (function_exists('EZComments_migrate'))
	{
		if (EZComments_migrate()) {
			$migrated[$migrate] = true;
			pnModSetVar('EZComments', 'migrated', serialize($migrated));
		}
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

    $usermods = pnModGetUserMods();
    $adminmods = pnModGetAdminMods(); 
    // build a simple array of all available modules
    $allmods = array();
    foreach ($usermods as $mod) {
        $allmods[] = $mod['name'];
    } 
    foreach ($adminmods as $mod) {
        $allmods[] = $mod['name'];
    } 

    $usedmods = pnModAPIFunc('EZComments', 'admin', 'getUsedModules');

    $orphanedmods = array_diff($usedmods, $allmods);

    $output = new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (!$orphanedmods) {
        $output->Text(_EZCOMMENTS_CLEANUP_NOTHINGTODO);
        $output->Linebreak(2);
        $output->URL(pnModURL('EZComments', 'admin'), _EZCOMMENTS_CLEANUP_GOBACK);
        return $output->GetOutput();
    } 

    $selectitems = array();
    foreach ($orphanedmods as $mod) {
        $selectitems[] = array('id'       => $mod,
                               'name'     => $mod,
                               'selected' => false);
    } 

    $output->Text(_EZCOMMENTS_CLEANUP_EXPLAIN);
    $output->Linebreak(2);
    $output->FormStart(pnModURL('EZComments', 'admin', 'cleanup_go'));
    $output->FormHidden('authid', pnSecGenAuthKey());
    $output->Text(_EZCOMMENTS_CLEANUP_LABEL . ' ');
    $output->FormSelectMultiple('EZComments_module', $selectitems, false);
    $output->Text(' ');
    $output->FormSubmit(_EZCOMMENTS_CLEANUP_GO);
    $output->FormEnd();
    return $output->GetOutput();
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