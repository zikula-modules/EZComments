<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

class EZComments_myprofileapi extends AbstractApi
{
    /**
     * This function returns the name of the tab
     *
     * @return string
     */
    public function getTitle()
    {
        $dom = ZLanguage::getModuleDomain('EZComments');
        $uid = (int) FormUtil::getPassedValue('uid');

        $settings = pnModAPIFunc('MyProfile', 'user', 'getSettings', array('uid' => $uid));
        if ($settings['nocomments'] == 1) {
            // Show no tab header
            return false;
        } else {
            return __("User's pinboard", $dom);
        }
    }

    /**
     * This function returns additional options that should be added to the plugin url
     *
     * @return array
     */
    public function getURLAddOn()
    {
        return array('order' => 1);
    }

    /**
     * This function shows the content of the main MyProfile tab
     *
     * @return output
     */
    public function tab($args)
    {
        // is ezcomment hook activated for myprofile module?
        $dom = ZLanguage::getModuleDomain('EZComments');

        $result = pnModIsHooked('EZComments', 'MyProfile');
        if (!$result) {
            if (!pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'MyProfile', 'hookmodname' => 'EZComments'))) {
                return LogUtil::registerError(__('Registering EZComments hook for MyProfile module failed', $dom));
            }
        }

        // generate output
        $render = & pnRender::getInstance('EZComments');

        $render->assign('uid', (int) $args['uid']);
        $render->assign('viewer_uid', pnUserGetVar('uid'));
        $render->assign('uname', pnUserGetVar('uname', (int) $args['uid']));
        $render->assign('settings', pnModAPIFunc('MyProfile', 'user', 'getSettings', array('uid' => $args['uid'])));

        return $render->fetch('ezcomments_myprofile_tab.htm');
    }

    /**
     * This function returns 1 if Ajax should not be used loading the plugin
     *
     * @return string
     */
    public function noAjax($args)
    {
        return true;
    }
}
