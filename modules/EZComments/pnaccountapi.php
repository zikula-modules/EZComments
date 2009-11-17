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
* Return an array of items to show in the your account panel
*
* @return   array
*/
function EZComments_accountapi_getall($args)
{
    // Create an array of links to return
    pnModLangLoad('EZComments');
    $items = array();
    $items['1'] = array('url'   => pnModURL('EZComments', 'user', 'main'),
                        'title' => _EZCOMMENTS_MANAGEMYCOMMENTS,
                        'icon'  => 'mycommentsbutton.gif',
                        'set'   => null);

    // Return the items
    return $items;
}
