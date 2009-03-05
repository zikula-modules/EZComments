<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
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
