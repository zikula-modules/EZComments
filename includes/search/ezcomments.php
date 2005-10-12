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
 * @author      Mark West <markwest at postnuke dot com>
 * @author      Jean-Michel Vedrine
 * @version     0.8
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

 
$search_modules[] = array(
    'title'       => 'EZComments',
    'func_search' => 'search_ezcomments',
    'func_opt'    => 'search_ezcomments_opt'
);


/**
 * search_ezcomments_opt()
 * 
 * This function will be called to display the search box.
 * 
 * @return output the search field
 **/
function search_ezcomments_opt()
{
    if (!pnModAvailable('EZComments') || !pnSecAuthAction(0, 'EZComments::', '::', ACCESS_READ)) {
        return;
    } 
    pnModLangLoad('EZComments', 'user');
    $pnRender = &new pnRender('EZComments');
    return $pnRender->fetch('ezcomments_search_form.htm');
} 


/**
 * search_ezcomments()
 * 
 * do the actual search and display the results
 * 
 * @return output the search results
 **/
function search_ezcomments()
{
    // First security check
    if (!pnModAvailable('EZComments') || !pnSecAuthAction(0, 'EZComments::', '::', ACCESS_READ)) {
        return;
    } 

    list($q,
         $bool,
         $active_ezcomments) = pnVarCleanFromInput('q',
                                                   'bool',
                                                   'active_ezcomments');

    if (empty($active_ezcomments)) {
        return;
    } 

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

    $pnRender = &new pnRender('EZComments');
    $pnRender->caching = false;
    $pnRender->assign('comments', $comments);
    return $pnRender->fetch('ezcomments_search_results.htm');
} 
?>