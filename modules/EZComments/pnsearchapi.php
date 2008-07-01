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
 * @author      Joerg Napp <jnapp@users.sourceforge.net>
 * @author      Mark West <markwest at zikula dot org>
 * @author      Jean-Michel Vedrine
 * @version     0.8
 * @link        http://code.zikula.org/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Zikula
 * @subpackage  EZComments
 */


/**
 * Search plugin info
 **/
function ezcomments_searchapi_info()
{
    $search_module = array('title' =>'ezcomments', 'type' => 'API');
    return $search_module;
}


/**
 * search_ezcomments_opt()
 *
 * This function will be called to display the search box.
 *
 * @return output the search field
 **/
function ezcomments_searchapi_options()
{
    if (!pnModAvailable('EZComments') || !SecurityUtil::checkPermission('EZComments::', '::', ACCESS_READ)) {
        return;
    }
    pnModLangLoad('EZComments', 'user');
    $renderer = pnRender::getInstance('EZComments');
    return $renderer->fetch('ezcomments_search_form.htm');
}


/**
 * search_ezcomments()
 *
 * do the actual search and display the results
 *
 * @return output the search results
 **/
function ezcomments_searchapi_search($args)
{
    // First security check
    if (!pnModAvailable('EZComments') || !SecurityUtil::checkPermission('EZComments::', '::', ACCESS_READ)) {
        return;
    }
    $q    = $args['q'];
    $bool = $args['bool'];

    // an array with words to search for.
    $words = explode(' ', $q);

    // Let the API search
    $comments = pnModAPIFunc('EZComments',
                             'user',
                             'getall',
                             array('search' => compact('words', 'bool')));

    if (empty($comments)) {
        return _EZCOMMENTS_NOCOMMENTSFOUND . '<br /><br /><br />';
    }

    $renderer = pnRender::getInstance('EZComments', false);
    $renderer->assign('comments', $comments);
    return $renderer->fetch('ezcomments_search_results.htm');
}
