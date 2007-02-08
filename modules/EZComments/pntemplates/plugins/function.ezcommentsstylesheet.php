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
 * @author      Joerg Napp <jnapp@users.sourceforge.net>
 * @author      Mark West <markwest at postnuke dot com>
 * @author      Jean-Michel Vedrine
 * @version     1.3
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
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

    // config directory
    $configpath    = "config/templates/EZComments/$ostemplate/style.css";
	// theme directory
    $theme         = pnVarPrepForOS(pnUserGetTheme());
    $themepath     = "themes/$theme/templates/modules/EZComments/$ostemplate/style.css";
	// module directory
    $modpath       = "modules/EZComments/pntemplates/$ostemplate/style.css";

	// search for the style sheet
    $csssrc = '';
	foreach (array($configpath,
                   $themepath,
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
    $GLOBALS['additional_header'][] = $tag;        
}
?>