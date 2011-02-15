<?php

/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */
class EZComments_Form_Handler_Admin_ModifyConfig extends Zikula_Form_Handler
{

    function initialize($view)
    {
        $view->caching = false;
        $view->add_core_data();

        $templates = array();
        $rawtemplates = ModUtil::apiFunc('EZComments', 'user', 'gettemplates');
        if (is_array($rawtemplates) && count($rawtemplates) <> 0) {
            foreach ($rawtemplates as $rawtemplate)
            {
                $templates[] = array('text' => $rawtemplate, 'value' => $rawtemplate);
            }
        }
        $view->assign('templates', $templates);

        // is the akismet module available
        $view->assign('akismetavailable', ModUtil::available('Akismet'));

        $statuslevels = array(array('text' => $this->__('Approved'), 'value' => 0),
            array('text' => $this->__('Pending'), 'value' => 1),
            array('text' => $this->__('Rejected'), 'value' => 2));

        $view->assign('statuslevels', $statuslevels);

        $feeds = array(array('text' => $this->__('Atom 0.3'), 'value' => 'atom'),
            array('text' => $this->__('RSS 2.0'), 'value' => 'rss'));

        $view->assign('feeds', $feeds);

        return true;
    }

    function handleCommand($view, &$args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        if ($args['commandName'] == 'submit') {
            $ok = $view->pnFormIsValid();
            $data = $view->pnFormGetValues();

            // TODO reduce this to a loop
            if (empty($data['ezcomments_itemsperpage'])) {
                $ifield = $view->pnFormGetPluginById('ezcomments_itemsperpage');
                $ifield->setError(DataUtil::formatForDisplay($this->__('missing value')));
                $ok = false;
            }
            if (empty($data['ezcomments_modlinkcount'])) {
                $ifield = $view->pnFormGetPluginById('ezcomments_modlinkcount');
                $ifield->setError(DataUtil::formatForDisplay($this->__('missing value')));
                $ok = false;
            }
            if (empty($data['ezcomments_blacklinkcount'])) {
                $ifield = $view->pnFormGetPluginById('ezcomments_blacklinkcount');
                $ifield->setError(DataUtil::formatForDisplay($this->__('missing value')));
                $ok = false;
            }
            if (empty($data['ezcomments_feedcount'])) {
                $ifield = $view->pnFormGetPluginById('ezcomments_feedcount');
                $ifield->setError(DataUtil::formatForDisplay($this->__('missing value')));
                $ok = false;
            }
            if (empty($data['ezcomments_commentsperpage'])) {
                $ifield = $view->pnFormGetPluginById('ezcomments_commentsperpage');
                $ifield->setError(DataUtil::formatForDisplay($this->__('missing value')));
                $ok = false;
            }
            if (!$ok) {
                return false;
            }

            ModUtil::setVar('EZComments', 'MailToAdmin', $data['ezcomments_MailToAdmin']);
            ModUtil::setVar('EZComments', 'moderationmail', $data['ezcomments_moderationmail']);
            ModUtil::setVar('EZComments', 'template', $data['ezcomments_template']);
            ModUtil::setVar('EZComments', 'css', $data['ezcomments_css']);
            ModUtil::setVar('EZComments', 'itemsperpage', $data['ezcomments_itemsperpage']);
            ModUtil::setVar('EZComments', 'anonusersinfo', $data['ezcomments_anonusersinfo']);
            ModUtil::setVar('EZComments', 'moderation', $data['ezcomments_moderation']);
            ModUtil::setVar('EZComments', 'enablepager', $data['ezcomments_enablepager']);
            ModUtil::setVar('EZComments', 'dontmoderateifcommented', $data['ezcomments_dontmoderateifcommented']);
            ModUtil::setVar('EZComments', 'modlinkcount', $data['ezcomments_modlinkcount']);
            ModUtil::setVar('EZComments', 'modlist', $data['ezcomments_modlist']);
            ModUtil::setVar('EZComments', 'blacklinkcount', $data['ezcomments_blacklinkcount']);
            ModUtil::setVar('EZComments', 'blacklist', $data['ezcomments_blacklist']);
            ModUtil::setVar('EZComments', 'alwaysmoderate', $data['ezcomments_alwaysmoderate']);
            ModUtil::setVar('EZComments', 'proxyblacklist', $data['ezcomments_proxyblacklist']);
            ModUtil::setVar('EZComments', 'logip', $data['ezcomments_logip']);
            ModUtil::setVar('EZComments', 'feedtype', $data['ezcomments_feedtype']);
            ModUtil::setVar('EZComments', 'feedcount', $data['ezcomments_feedcount']);
            ModUtil::setVar('EZComments', 'commentsperpage', $data['ezcomments_commentsperpage']);
            ModUtil::setVar('EZComments', 'enablepager', $data['ezcomments_enablepager']);
            ModUtil::setVar('EZComments', 'akismet', $data['ezcomments_akismet']);
            ModUtil::setVar('EZComments', 'akismetstatus', $data['ezcomments_akismetstatus']);
            ModUtil::setVar('EZComments', 'anonusersrequirename', $data['ezcomments_anonusersrequirename']);
            ModUtil::setVar('EZComments', 'modifyowntime', $data['ezcomments_modifyowntime']);
            ModUtil::setVar('EZComments', 'useaccountpage', $data['ezcomments_useaccountpage']);

            LogUtil::registerStatus($this->__('Done! Module configuration updated.'));
        }

        return true;
    }

}
