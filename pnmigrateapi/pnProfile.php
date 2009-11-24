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
 * Do the migration
 * 
 * With this function, the actual migration is done.
 * 
 * @return   boolean   true on sucessful migration, false else
 */
function EZComments_migrateapi_pnProfile()
{
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError('pnProfile comments migration: Not Admin');
    }

    if (!pnModAvailable('pnComments')) {
          return LogUtil::RegisterError('pnComments not available');
    }
    pnModDBInfoLoad('pnComments');

    $comments = DBUtil::SelectObjectArray('EZComments');
    $counter  = 0;
    foreach ($comments as $comment) {
          if ($comment['modname'] == 'pnProfile') {
              $comment['modname']  = 'MyProfile';
              $comment['url']      = 'index.php?module=MyProfile&func=display&uid='.$comment['objectid'];
              $comment['owneruid'] = $comment['objectid'];
              if (DBUtil::updateObject($comment,'EZComments')) {
                  $counter++;
              }
        }    
    }

    return LogUtil::registerStatus('updated / migrated: '.$counter.' comments from pnProfile to MyProfile, the successor of pnProfile');
}
