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
 * Smarty function to display admin links for the example module
 * based on the user's permissions
 * 
 * Example
 * <!--[ezcommentsadminlinks start="[" end="]" seperator="|" class="pn-menuitem-title"]-->
 * 
 * @author       Mark West
 * @since        25/01/05
 * @see          function.ezcommentsadminlinks.php::smarty_function_ezcommentsadminlinks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $start       start string
 * @param        string      $end         end string
 * @param        string      $seperator   link seperator
 * @param        string      $class       CSS class
 * @return       string      the results of the module function
 */
function smarty_function_ezcommentsadminlinks($params, &$smarty) 
{
    extract($params); 
    unset($params);
    
    // set some defaults
    if (!isset($start)) {
        $start = '[';
    }
    if (!isset($end)) {
        $end = ']';
    }
    if (!isset($seperator)) {
        $seperator = '|';
    }
    if (!isset($class)) {
        $class = 'pn-menuitem-title';
    }

    $adminlinks = "<span class=\"$class\">$start ";
    
    if (pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        $adminlinks .= "<a href=\"" . pnVarPrepHTMLDisplay(pnModURL('EZComments', 'admin')) . "\">" . _EZCOMMENTS_ADMIN_MAIN . "</a> ";
    }
    if (pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADD)) {
        $adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('EZComments', 'admin', 'cleanup')) . "\">" . _EZCOMMENTS_CLEANUP . "</a> ";
    }
    if (pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        $adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('EZComments', 'admin', 'migrate')) . "\">" . _EZCOMMENTS_MIGRATE . "</a> ";
    }
    if (pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        $adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('EZComments', 'admin', 'purge')) . "\">" . _EZCOMMENTS_PURGE . "</a> ";
    }
    if (pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        $adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('EZComments', 'admin', 'stats')) . "\">" . _EZCOMMENTS_STATS . "</a> ";
    }
    if (pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        $adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('EZComments', 'admin', 'applyrules')) . "\">" . _EZCOMMENTS_APPLYMODRULES . "</a> ";
    }
    if (pnSecAuthAction(0, 'EZComments::', '::', ACCESS_ADMIN)) {
        $adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('EZComments', 'admin', 'modifyconfig')) . "\">" . _MODIFYCONFIG . "</a> ";
    }

    $adminlinks .= "$end</span>\n";

    return $adminlinks;
}

?>
