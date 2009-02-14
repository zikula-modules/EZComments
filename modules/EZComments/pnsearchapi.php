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
 * @author Joerg Napp <jnapp@users.sourceforge.net>
 * @author Mark West <markwest at zikula dot org>
 * @author Jean-Michel Vedrine
 * @author Florian Schieﬂl <florian.schiessl at ifs-net.de>
 * @author Frank Schummertz
 * @version 1.6
 * @link http://code.zikula.org/ezcomments/ Support and documentation
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package Zikula_3rdParty_Modules
 * @subpackage EZComments
 */

/**
 * Search plugin info
 **/
function EZComments_searchapi_info()
{
	return array(	
   		'title' 	=> 'EZComments', 
        'functions' => array(
								'EZComments' => 'search'
						 	));
}

/**
 * search_ezcomments_options()
 *
 * This function will be called to display the search box.
 *
 * @return output the search field
 **/
function EZComments_searchapi_options($args)
{
    if (SecurityUtil::checkPermission( 'EZComments::', '::', ACCESS_READ)) {
        // Create output object - this object will store all of our output so that
        // we can return it easily when required
        $pnRender = pnRender::getInstance('EZComments');
        $pnRender->assign('active',(isset($args['active'])&&isset($args['active']['EZComments']))||(!isset($args['active'])));
        return $pnRender->fetch('ezcomments_search_form.htm');
    }
    return '';
}

/**
 * search_ezcomments()
 *
 * do the actual search and display the results
 *
 * @return output the search results
 **/
function EZComments_searchapi_search($args)
{
    if (!SecurityUtil::checkPermission( 'EZComments::', '::', ACCESS_READ)) {
        return true;
    }

    if (strlen($args['q']) < 3 || strlen($args['q']) > 30) {
        return LogUtil::registerStatus(pnML('_EZCOMMENTS_SEARCHLENGTHHINT', array('minlen' => $minlen, 'maxlen' => $maxlen)));
    }

    pnModDBInfoLoad('Search');
    $pntable 		= pnDBGetTables();
    // ezcomments tables
    $ezcommentstable 	= $pntable['EZComments'];
    $ezcommentscolumn = $pntable['EZComments_column'];
    // our own tables
    $searchTable 	= $pntable['search_result'];
    $searchColumn 	= $pntable['search_result_column'];
	// where
    $where = search_construct_where($args, 
                                    array($ezcommentscolumn['subject'], 
                                          $ezcommentscolumn['comment']));
	$where.=" AND ".$ezcommentscolumn['url']." != ''";
    $sessionId = session_id();

    $insertSql = 
		"INSERT INTO $searchTable
		  ($searchColumn[title],
		   $searchColumn[text],
		   $searchColumn[extra],
		   $searchColumn[module],
		   $searchColumn[created],
		   $searchColumn[session])
		VALUES 
		";

    $comments = DBUtil::selectObjectArray('EZComments', $where);

    foreach ($comments as $comment)
    {
          $sql = $insertSql . '(' 
                 . '\'' . DataUtil::formatForStore($comment['subject']) . '\', '
                 . '\'' . DataUtil::formatForStore($comment['comment']) . '\', '
                 . '\'' . DataUtil::formatForStore($comment['url']) . '\', '
                 . '\'' . 'EZComments' . '\', '
                 . '\'' . DataUtil::formatForStore($comment['date']) . '\', '
                 . '\'' . DataUtil::formatForStore($sessionId) . '\')';
          $insertResult = DBUtil::executeSQL($sql);
          if (!$insertResult) {
              return LogUtil::registerError (_GETFAILED);
          }
    }
    return true;
}

/**
 * Do last minute access checking and assign URL to items
 *
 * Access checking is ignored since access check has
 * already been done. But we do add a URL to the found comment
 */
function EZComments_searchapi_search_check(&$args)
{
    $datarow = &$args['datarow'];
    $url = $datarow['extra'];
    $datarow['url'] = $url;
    return true;
}

