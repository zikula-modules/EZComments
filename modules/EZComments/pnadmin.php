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
function EZComments_admin_main() {
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
		return _EZCOMMENTS_NOAUTH;
	} 

	$output = new pnHTML();
	$output->SetInputMode(_PNH_VERBATIMINPUT);

	$output->Text(EZComments_adminmenu());

	$output->FormStart(pnModURL('EZComments', 'admin', 'update'));
	$output->FormHidden('authid', pnSecGenAuthKey());
	$output->Linebreak();
	$output->Text(_EZCOMMENTS_SENDINFOMAIL . ' ');
    $output->FormCheckbox('MailToAdmin', pnVarPrepForDisplay(pnModGetVar('EZComments', 'MailToAdmin')));	
	$output->Linebreak();
	$output->FormSubmit(_EZCOMMENTS_OK);
	$output->FormEnd();
	
	$output->Linebreak(3);

	

	// presentation values
	// Hardcoded -- move this to an module variable?!?
	$itemsperpage = 10;
    $startnum = pnVarCleanFromInput('startnum');

	if (!pnModAPILoad('EZComments', 'admin')) {
		return _EZCOMMENTS_LOADFAILED;
	}
	$items = pnModAPIFunc('EZComments',
            			  'admin',
            			  'getall',
                          array('startnum' => $startnum,
                                'numitems' => $itemsperpage));

	if ($items === false) {
		return _EZCOMMENTS_FAILED;
	} 
	
	$output->Title(_EZCOMMENTS_LASTCOMMENTS);

	$output->Text("<table width=\"99%\" border=\"1\" cellpadding=\"5\" cellspacing=\"2\">\n");
	$output->Text("<tr>");
	$output->Text("<th width=\"20%\">");
	$output->Text(_EZCOMMENTS_USERNAME);
	$output->Text("</th>");
	$output->Text("<th width=\"20%\">");
	$output->Text(_EZCOMMENTS_MODULE);
	$output->Text("</th>");
	$output->Text("<th width=\"60%\">");
	$output->Text(_EZCOMMENTS_COMMENT);
	$output->Text("</th>");
	$output->Text("</tr>");
	
	
	// Loop through each item and display it.
	foreach ($items as $item) {
		$datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime(strtotime($item['date'])));
		if ($item['uid'] > 0) {
			$userinfo = pnUserGetVars($item['uid']);
			$username = $userinfo['uname'];
		} else {
			$username = pnConfigGetVar('Anonymous');
		} 
		$output->Text("<tr>");

		$output->Text("<td width=\"20%\" valign=\"top\">");
		$output->Text($username);
		$output->Linebreak();
		$output->Text($datetime);
		$output->Text("</td>");

		$output->Text("<td width=\"20%\" valign=\"top\">");
		$output->URL($item['url'], $item['modname']);
		$output->Text("</td>");

		$output->Text("<td width=\"60%\" valign=\"top\">");
		$output->Text(pnVarPrepHTMLDisplay($item['comment']));
		$output->Text("</td>");

		$output->Text("</tr>");
	} 
	$output->Text("</table>"); 	

	// add a pager to the page
	$output->Linebreak(2);
	$output->Text('<center>');
    $output->Pager($startnum,
                   pnModAPIFunc('EZComments', 'admin', 'countitems'),
                   pnModURL('EZComments',
                            'admin',
                            'main',
                            array('startnum' => '%%')),
	               $itemsperpage);
	$output->Text('</center>');
	
	// Return the output
	return $output->GetOutput();
}

/**
 * Update the settings
 * 
 * This is the function that is called with the results of the
 * form supplied by EZComments_admin_main to alter the admin settings
 * 
 * @param $MailToAdmin full pathname of Smarty class
 */
function EZComments_admin_update($args)
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
		        $selectitems[] = array('id'       => $f,
                                       'name'     => substr($f, 0, strlen($f) -4),
                                       'selected' => false);
			}
	    }
	}
	closedir($d);


	$output = new pnHTML();
	$output->SetInputMode(_PNH_VERBATIMINPUT);
	$output->Text(EZComments_adminmenu());
    $output->Text(_EZCOMMENTS_MIGRATE_EXPLAIN);
    $output->Linebreak(2);

    if (!$selectitems) {
        $output->Text(_EZCOMMENTS_MIGRATE_NOTHINGTODO);
        $output->Linebreak(2);
        $output->URL(pnModURL('EZComments', 'admin'), _EZCOMMENTS_MIGRATE_GOBACK);
        return $output->GetOutput();
    } 

	$output->FormStart(pnModURL('EZComments', 'admin', 'migrate_go'));
    $output->FormHidden('authid', pnSecGenAuthKey());
    $output->Text(_EZCOMMENTS_MIGRATE_LABEL . ' ');
    $output->FormSelectMultiple('EZComments_migrate', $selectitems, false);
    $output->Text(' ');
    $output->FormSubmit(_EZCOMMENTS_MIGRATE_GO);
    $output->FormEnd();
    $output->Text(' ');
	return $output->GetOutput();
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
	$output->Text(EZComments_adminmenu());

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

/**
 * EZComments_adminmenu()
 * 
 * Create a common header menu for all admin panels
 * 
 * @return output The header menu
 */
function EZComments_adminmenu()
{
    $output = new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->Text(pnGetStatusMsg());
	$output->Linebreak();
    $output->Title(_EZCOMMENTS_ADMIN);
	$output->Linebreak();
    $output->Text('<div style="text-align:center;">');
    $output->Text('[ ');
    $output->URL(pnModURL('EZComments', 'admin'), _EZCOMMENTS_ADMIN_MAIN);
    $output->Text(' | ');
    $output->URL(pnModURL('EZComments', 'admin', 'cleanup'), _EZCOMMENTS_CLEANUP);
    $output->Text(' | ');
    $output->URL(pnModURL('EZComments', 'admin', 'migrate'), _EZCOMMENTS_MIGRATE);
    $output->Text(' ]');
    $output->Text('</div>');
    $output->Linebreak(2);

    return $output->GetOutput();
} 


?>
