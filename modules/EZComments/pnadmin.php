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
	$output->Title(_EZCOMMENTS_ADMIN);

	$output->FormStart(pnModURL('EZComments', 'admin', 'update'));
	$output->FormHidden('authid', pnSecGenAuthKey());
	$output->Text(_EZCOMMENTS_SMARTYPATH . ': ');
	$output->FormText('smartypath', pnVarPrepForDisplay(pnModGetVar('EZComments', 'Smartypath')), 80, 256);
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
 * @param $smartypath full pathname of Smarty class
 */
function EZComments_admin_update($args)
{
	if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
		pnRedirect(pnModURL('EZComments', 'admin', 'main'));
		return true;
	} 

	list($smartypath, 
	     $MailToAdmin) = pnVarCleanFromInput('smartypath', 
		                                    'MailToAdmin');
	extract($args);

	
    if (empty($smartypath)) {
		$smartypath = dirname(__FILE__) 
					  . DIRECTORY_SEPARATOR . 'pnclass'
					  . DIRECTORY_SEPARATOR . 'Smarty'
					  . DIRECTORY_SEPARATOR;
	}
						
    if (!isset($MailToAdmin)) {
        $MailToAdmin = 0;
    }

	pnModSetVar('EZComments', 'MailToAdmin', $MailToAdmin);
	pnModSetVar('EZComments', 'smartypath',  $smartypath);
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
 * This is experimantal at the moment and hidden from the main admin
 * menu!
 * 
 * @return output the migration interface
 */
function EZComments_admin_migrate()
{
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
		return _EZCOMMENTS_NOAUTH;
	} 

	$output = new pnHTML();
	$output->SetInputMode(_PNH_VERBATIMINPUT);
	$output->Title(_EZCOMMENTS_ADMIN);

	$output->FormStart(pnModURL('EZComments', 'admin', 'domigrate'));
	$output->FormHidden('authid', pnSecGenAuthKey());
	$output->Text('<select name="migrate">');

	$d = opendir('modules/EZComments/migrate');
	while($f = readdir($d)) {
    	if(substr($f, -3, 3) == 'php') {
// TODO: add a meaningful check if the migration has already been run.		
			if (false) {
				$disabled=' disabled';
			} else {
				$disabled='';}
				$output->Text("<option$disabled>$f</option>\n");
	    }
	}
	closedir($d);
	$output->Text('</select>');
	$output->FormSubmit(_EZCOMMENTS_MIGRATE);
	$output->FormEnd();
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
function EZComments_admin_domigrate()
{
	if (!pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
		return _EZCOMMENTS_NOAUTH;
	} 

	$migrate = pnVarCleanFromInput('migrate');
	if (!isset($migrate))
	{ 
		return false;
	}
	
	// don't issue a warning when the file does not exist!
	@include "modules/EZComments/migrate/$migrate";
	if (function_exists('EZComments_migrate'))
	{
		if (EZComments_migrate()) {
			// Eintrag in Datenbank
		}
	}
	pnRedirect(pnModURL('EZComments', 'admin', 'migrate'));
	return true;
}
?>
