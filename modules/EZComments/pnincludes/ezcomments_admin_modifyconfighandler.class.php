<?php
// $Id: mh_admin_modifyconfighandler.class.php 166 2007-02-18 19:18:21Z landseer $
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
// Original Author of file: Frank Schummertz
// Purpose of file:  MultiHook administration display functions
// ----------------------------------------------------------------------

class EZComments_admin_modifyconfighandler
{
    function initialize(&$renderer)
    {
        $renderer->caching = false;
        $renderer->add_core_data();
        
        $templates = array();
        $rawtemplates = pnModAPIFunc('EZComments', 'user', 'gettemplates');
        if(is_array($rawtemplates) && count($rawtemplates) <>0) {
            foreach($rawtemplates as $rawtemplate) {
                $templates[] = array('text' => $rawtemplate, 'value' => $rawtemplate);
            }
        }
        $renderer->assign('templates', $templates);

        // is the akismet module available
        $renderer->assign('akismetavailable', pnModAvailable('akismet'));

        $statuslevels = array( array('text' => _EZCOMMENTS_APPROVED, 'value' => 0),
                               array('text' => _EZCOMMENTS_PENDING,  'value' => 1),
                               array('text' => _EZCOMMENTS_REJECTED, 'value' => 2));
        $renderer->assign('statuslevels', $statuslevels); 

        $feeds = array( array('text' => _EZCOMMENTS_ATOM, 'value' => 'atom'),
                        array('text' => _EZCOMMENTS_RSS,  'value' => 'rss'));
        $renderer->assign('feeds', $feeds); 
        
        return true;
    }


    function handleCommand(&$renderer, &$args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError('index.php');
        }  
        if ($args['commandName'] == 'submit') {
            $ok = $renderer->pnFormIsValid(); 
            $data = $renderer->pnFormGetValues();

            if(empty($data['ezcomments_itemsperpage'])) {
                $ifield = & $renderer->pnFormGetPluginById('ezcomments_itemsperpage');
                $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_MISSINGVALUE));
                $ok = false;
            }
            if(empty($data['ezcomments_modlinkcount'])) {
                $ifield = & $renderer->pnFormGetPluginById('ezcomments_modlinkcount');
                $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_MISSINGVALUE));
                $ok = false;
            }
            if(empty($data['ezcomments_blacklinkcount'])) {
                $ifield = & $renderer->pnFormGetPluginById('ezcomments_blacklinkcount');
                $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_MISSINGVALUE));
                $ok = false;
            }
            if(empty($data['ezcomments_feedcount'])) {
                $ifield = & $renderer->pnFormGetPluginById('ezcomments_feedcount');
                $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_MISSINGVALUE));
                $ok = false;
            }
            if(empty($data['ezcomments_commentsperpage'])) {
                $ifield = & $renderer->pnFormGetPluginById('ezcomments_commentsperpage');
                $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_MISSINGVALUE));
                $ok = false;
            }
            
            if (!$ok) {
                return false;
            }

            pnModSetVar('EZComments', 'MailToAdmin',             $data['ezcomments_MailToAdmin']);
            pnModSetVar('EZComments', 'moderationmail',          $data['ezcomments_moderationmail']);
            pnModSetVar('EZComments', 'template',                $data['ezcomments_template']);
            pnModSetVar('EZComments', 'itemsperpage',            $data['ezcomments_itemsperpage']);
            pnModSetVar('EZComments', 'anonusersinfo',           $data['ezcomments_anonusersinfo']);
            pnModSetVar('EZComments', 'moderation',              $data['ezcomments_moderation']);
            pnModSetVar('EZComments', 'enablepager',             $data['ezcomments_enablepager']);
            pnModSetVar('EZComments', 'dontmoderateifcommented', $data['ezcomments_dontmoderateifcommented']);
            pnModSetVar('EZComments', 'modlinkcount',            $data['ezcomments_modlinkcount']);
            pnModSetVar('EZComments', 'modlist',                 $data['ezcomments_modlist']);
            pnModSetVar('EZComments', 'blacklinkcount',          $data['ezcomments_blacklinkcount']);
            pnModSetVar('EZComments', 'blacklist',               $data['ezcomments_blacklist']);
            pnModSetVar('EZComments', 'alwaysmoderate',          $data['ezcomments_alwaysmoderate']);
            pnModSetVar('EZComments', 'proxyblacklist',          $data['ezcomments_proxyblacklist']);
            pnModSetVar('EZComments', 'logip',                   $data['ezcomments_logip']);
            pnModSetVar('EZComments', 'feedtype',                $data['ezcomments_feedtype']);
            pnModSetVar('EZComments', 'feedcount',               $data['ezcomments_feedcount']);
            pnModSetVar('EZComments', 'commentsperpage',         $data['ezcomments_commentsperpage']);
            pnModSetVar('EZComments', 'enablepager',             $data['ezcomments_enablepager']);
            pnModSetVar('EZComments', 'akismet',                 $data['ezcomments_akismet']);
            pnModSetVar('EZComments', 'akismetstatus',           $data['ezcomments_akismetstatus']);
            pnModSetVar('EZComments', 'anonusersrequirename',    $data['ezcomments_anonusersrequirename']);

            LogUtil::registerStatus(_CONFIGUPDATED);
        }
        return true;
    }

}
