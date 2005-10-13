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
 * @author      Joerg Napp <jnapp@users.sourceforge.net>
 * @author      Mark West <markwest at postnuke dot com>
 * @author      Jean-Michel Vedrine
 * @version     1.1
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

// Information for the modules admin
$modversion['name']        = 'EZComments';
$modversion['version']     = '1.2';
$modversion['description'] = 'Attach comments to pages using hooks';

// I suspect these are not respected as the should
$modversion['admin']       = 1;
$modversion['user']        = 0;

// Information for the credits module
$modversion['changelog']   = 'pndocs/changelog.txt';
$modversion['credits']     = 'pndocs/credits.txt';
$modversion['help']        = 'pndocs/install.txt';
$modversion['license']     = 'pndocs/license.txt';
$modversion['official']    = 0;
$modversion['author']      = 'The EZComments development team';
$modversion['contact']     = 'http://noc.postnuke.com/projects/ezcomments/';

// This one adds the info to the DB, so that users can click on the 
// headings in the permission module
$modversion['securityschema'] = array('EZComments::' => 'Module:Item ID:Comment ID');
?>