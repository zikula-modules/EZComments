<?php
/**
 * Migration pnComments comments into the EZComments system
 * 
 * @author      Florian Schießl
 * @version     0.1
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
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
?>