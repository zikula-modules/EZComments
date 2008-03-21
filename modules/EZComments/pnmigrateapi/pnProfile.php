<?php
/**
 * Migration pnComments (-> pnProfile) comments into the EZComments system
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
function EZComments_migrateapi_pnProfile()
{
    if(!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError('index.php');
    }
	if (!pnModAvailable('pnComments')) {
	  	return LogUtil::RegisterError('pnComments not available');
	}
	pnModDBInfoLoad('pnComments');
	$comments = DBUtil::SelectObjectArray('EZComments');
	$counter=0;
	foreach ($comments as $comment) {
	  	if ($comment['modname'] == 'pnProfile') {
		  	$comment['modname']	= 'MyProfile';
		  	$comment['url']		= 'index.php?module=MyProfile&func=display&uid='.$comment['objectid'];
		  	$comment['owneruid'] = $comment['objectid'];
		  	if (DBUtil::updateObject($comment,'EZComments')) $counter++;
		}	
	}
	return LogUtil::registerStatus('updated / migrated: '.$counter.' comments from pnProfile to MyProfile, the successor of pnProfile');
}
?>