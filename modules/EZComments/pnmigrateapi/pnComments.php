<?php
/**
 * $Id: pnadmin.php 495 2008-07-01 08:52:22Z markwest $
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
 * Do the migration
 * 
 * With this function, the actual migration is done.
 * 
 * @return   boolean   true on sucessful migration, false else
 */
function EZComments_migrateapi_pnComments()
{
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }
	if (!pnModAvailable('pnComments')) {
	  	return LogUtil::RegisterError('pnComments not available');
	}
	pnModDBInfoLoad('pnComments');
	$comments = DBUtil::SelectObjectArray('pncomments');
	$counter=0;
	foreach ($comments as $c) {
	  	$obj = array (
	  		'modname'		=>	$c['module'],
	  		'objectid'	=>	$c['objectid'],
	  		'comment'	=>	$c['text'],
	  		'replyto'	=>	-1,
	  		'subject'	=>	$c['subject'],
	  		'uid'		=>	$c['uid'],
	  		'date'		=>	$c['date'].' 00:00:00'
		  	);
		if (!DBUtil::insertObject($obj,'EZComments')) return LogUtil::registerError('error inserting comments in ezcomments table');
		$counter++;
	}
	return LogUtil::registerStatus('migrated: '.$counter.' comments');
}
