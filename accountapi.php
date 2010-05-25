<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

class EZComments_accountapi extends AbstractApi
{
    /**
    * Return an array of items to show in the your account panel
    *
    * @return   array
    */
    public function getall($args)
    {
        $dom = ZLanguage::getModuleDomain('EZComments');

        $useAccountPage = pnModGetVar('EZComments', 'useaccountpage', '1');
        if ($useAccountPage) {
            // Create an array of links to return
            $items = array();
            $items['1'] = array('url'   => pnModURL('EZComments', 'user', 'main'),
                                'title' => __('Manage my comments', $dom),
                                'icon'  => 'mycommentsbutton.gif',
                                'set'   => null);
        }

        // return the items
        return $items;
    }
}
