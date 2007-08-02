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
 * @version     1.5
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */ 

/**
 * Smarty modifier format an issue date for an atom news feed
 * 
 * Example
 * 
 *   <!--[$MyVar|modified]-->
 * 
 * 
 * @author       Mark West
 * @author		 Franz Skaaning
 * @since        02 March 2004
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_modified($string)
{
    return strftime("%G-%m-%dT%H:%M:%S+01:00", strtotime($string));
}

