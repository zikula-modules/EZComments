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

// Information for the modules admin
$modversion['name']        = 'EZComments';
$modversion['version']     = '1.6';
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
$modversion['contact']     = 'http://code.zikula.org/ezcomments/';

// This one adds the info to the DB, so that users can click on the 
// headings in the permission module
$modversion['securityschema'] = array('EZComments::'         => 'Module:Item ID:Comment ID',
                                      'EZComments:trackback' => 'Module:Item ID:',
                                      'EZComments:pingback'  => 'Module:Item ID:'
                                      );
// recommended and required modules
$modversion['dependencies'] = array(
	array(	'modname'    => 'ContactList',
			'minversion' => '1.0', 'maxversion' => '',
            'status'     => PNMODULE_DEPENDENCY_RECOMMENDED)
	array(	'modname'    => 'Akismet',
			'minversion' => '1.0', 'maxversion' => '',
            'status'     => PNMODULE_DEPENDENCY_RECOMMENDED)
	);
