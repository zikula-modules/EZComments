<?php
// $Id$
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
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
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
/**
 * Xanthia plugin
 * 
 * This file is a plugin for Xanthia, the PostNuke theme engine
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id$
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2004 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to add a stylesheet to the page header
 * 
 * available parameters:
 *  - xhtml       if set, the xhtml format of the stylesheet tag will be used
 *  - template    name of the template set
 *
 * Example: <!--[ezcommentsstylesheet template=plain]-->
 * Output:  <link rel="stylesheet" href="modules/EZComments/pntemplates/plain/style.css" type="text/css">
 *
 * Example: <!--[ezcommentsstylesheet template=plain xhtml=true]-->
 * Output:  <link rel="stylesheet" href="modules/EZComments/pntemplates/plain/style.css" type="text/css" />
 * 
 *
 * @author       Mark West
 * @author       Jörg Napp
 * @since        13. Feb. 2005
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      The tag
 */
function smarty_function_ezcommentsstylesheet($params, &$smarty)
{
    // get the parameters
    extract($params); 
	unset($params['xhtml']);

	// check for a template name
    if (!isset($template)) {
        $smarty->trigger_error('ezcommentsstylesheet: attribute template required');
        return false;
    }

    $ostemplate = pnVarPrepForOS($template);
	
	// theme directory
    $theme         = pnVarPrepForOS(pnUserGetTheme());
    $themepath     = "themes/$theme/templates/modules/EZComments/$ostemplate/style.css";
	// module directory
    $modpath       = "modules/EZComments/pntemplates/$ostemplate/style.css";

	// search for the style sheet
    $csssrc = '';
	foreach (array($themepath,
	               $modpath) as $path) {
        if (file_exists("$path") && is_readable("$path")) {
		    $csssrc = "$path";
			break;
		}
    }

	// if no module stylesheet is present then return no content
	if ($csssrc == '') {
        $tag='';
	} else {
    	// create xhtml specifier
	    if (isset($xhtml)) {
    		$xhtml = ' /';
	    } else {
    		$xhtml = '';
	    }

        $tag = '<link rel="stylesheet" href="' . $csssrc . '" type="text/css"' . $xhtml . '>';
    }
echo $tag;
    $GLOBALS['additional_header'][] = $tag;        
}
?>