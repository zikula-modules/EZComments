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
 * @author  Mark West
 * @author  Jörg Napp
 * @since        13. Feb. 2005
 * @param array       $params      All attributes passed to this function from the template
 * @param object      &$smarty     Reference to the Smarty object
 * @return string      The tag
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
