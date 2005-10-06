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
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id$
 * @author       Mark West
 * @author		 Franz Skaaning
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2004 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

/**
 * Smarty modifier format an issue date for an atom news feed
 * 
 * Example
 * 
 *   <!--[$MyVar|issued]-->
 * 
 * 
 * @author       Mark West
 * @author		 Franz Skaaning
 * @since        02 March 2004
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_issued($string)
{
    return strftime("%G-%m-%dT%H:%M:%S", strtotime($string));
}

?>