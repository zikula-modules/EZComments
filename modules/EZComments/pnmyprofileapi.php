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
 * This function returns the name of the tab
 *
 * @return string
 */
function EZComments_myprofileapi_getTitle($args)
{
  	pnModLangLoad('EZComments');
  	return _EZCOMMENTS_TABTITLE;
}

/**
 * This function returns additional options that should be added to the plugin url
 *
 * @return array
 */
function EZComments_myprofileapi_getURLAddOn($args)
{
  	return array('order'=>1);
}

/**
 * This function shows the content of the main MyProfile tab
 *
 * @return output
 */
function EZComments_myprofileapi_tab($args)
{
  	// is ezcomment hook activated for myprofile module?
  	
  	$result = pnModIsHooked('EZComments','MyProfile');
  	if(!$result) {
	  	if (!pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'MyProfile','hookmodname' => 'EZComments'))) return LogUtil::registerError(_EZCOMMENTS_HOOKREGFAILED);
    }
	
  	// generate output
 	$render = pnRender::getInstance('EZComments');
 	$render->assign('uid',			(int)$args['uid']);
 	$render->assign('viewer_uid',	pnUserGetVar('uid'));
 	$render->assign('uname',		pnUserGetVar('uname',(int)$args['uid']));
 	$render->assign('settings',		pnModAPIFunc('MyProfile','user','getSettings',array('uid'=>$args['uid'])));
	$output = $render->fetch('ezcomments_myprofile_tab.htm');
	return $output;
}

/**
 * This function returns 1 if Ajax should not be used loading the plugin
 *
 * @return string
 */

function EZComments_myprofileapi_noAjax($args)
{
  	return true;
}
