<?php
// $Id$
// ----------------------------------------------------------------------
// EZComments
// Attach comments to any module calling hooks
// ----------------------------------------------------------------------
// Author: Jörg Napp, http://postnuke.lottasophie.de
// ----------------------------------------------------------------------
// LICENSE
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

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
	$output->FormText('smartypath', pnVarPrepForDisplay(pnModGetVar('EZComments', 'Smartypath')), 80);
	$output->Linebreak();
	$output->Text(_EZCOMMENTS_SENDINFOMAIL . ' ');
    $output->FormCheckbox('MailToAdmin', pnVarPrepForDisplay(pnModGetVar('EZComments', 'MailToAdmin')));	
	$output->Linebreak();
	$output->FormSubmit(_EZCOMMENTS_OK);
	$output->FormEnd();
	
	$output->Linebreak(3);


	if (!pnModAPILoad('EZComments', 'admin')) {
		return _EZCOMMENTS_LOADFAILED;
	}
	$items = pnModAPIFunc('EZComments',
			  'admin',
			  'getall',
			  array('startnum' => 1,
				'numitems' => -1));

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
?>
