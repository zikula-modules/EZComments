<?php
// $Id$
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2001 by the Post-Nuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
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

/**
 * @author      Max Power <MaxPower@flyingcars.net>
 * @version     0.2
 * @link        http://www.haduken.com Support and documentation
 * @package     Postnuke
 * @subpackage  EZComments
 * a block to pull the latest comments from EZComment sources. 
 * based on the latest blog module for v4bjournal
 * and the LatestComments block
 */

/**
 * initialise block
 */
function EZComments_EZCommentsblock_init()
{
    // Security
    pnSecAddSchema('EZComments:EZCommentsblock:', 'Block title::');
}

/**
 * get information on block
 */
function EZComments_EZCommentsblock_info()
{
    // Values
    return array('text_type' => 'EZComments',
                 'module' => 'EZComments',
                 'text_type_long' => 'Show latest comments',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

/**
 * display block
 */
function EZComments_EZCommentsblock_display($blockinfo)
{
    // Security check
    if (!pnSecAuthAction(0, 'EZComments:EZCommentsblock:', "$blockinfo[title]::", ACCESS_READ)) {
        return;
    }

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    
    extract($vars);
    if (!isset($numentries)) {$numentries = 5;}
    if (!isset($showdate)) {$showdate = 0;}
    if (!isset($showusername)) {$showusername = 0;}
    if (!isset($linkusername)) {$linkusername = 0;}
    

    //load up the db info
    pnModDBInfoLoad('EZComments');

	// Get datbase setup
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	$EZCommentstable = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column']; 
    $userstable = $pntable['users'];
    $userscolumn = &$pntable['users_column'];

	// query the database        
    $sql = "SELECT  $EZCommentscolumn[id],
	 				$EZCommentscolumn[url],
				    $EZCommentscolumn[date],
                    $EZCommentscolumn[uid],
                    $EZCommentscolumn[subject],
                    $EZCommentscolumn[comment],
                    $userscolumn[uid],
                    $userscolumn[uname]
            FROM $EZCommentstable , $userstable
            WHERE $EZCommentscolumn[uid] = $userscolumn[uid]
            ORDER BY $EZCommentscolumn[date] DESC
            LIMIT $numentries";
    $result = $dbconn->Execute($sql);
    
    //saftey checks.
    if ($dbconn->ErrorNo() != 0) {
        return;
    }
    if ($result->EOF) {
        return;
    }    

	// create the output object
	$pnRender =& new pnRender('EZComments');    

	// assign all the block vars
	$pnRender->assign($vars);

	$comments = array();
    for (; !$result->EOF; $result->MoveNext()) {
		if (pnSecAuthAction(0, 'EZComments::', "::$id", ACCESS_READ)) {
	
			list($id,
				 $url, 
				 $date,
				 $uid,
				 $subject,
				 $comment,
				 $pn_uid,
				 $uname) = $result->fields;
	
			$comments[] = array('id' => $id,
								'url' => $url,
								'date' => $date,
								'uid' => $uid,
								'subject' => $subject,
								'comment' => $comment,
								'pn_uid' => $pn_uid,
								'uname' => $uname);
	    } 
    }
	$result->Close(); 

	$pnRender->assign('comments', $comments);
    
    // Populate block info and pass to theme
    $blockinfo['content'] = $pnRender->fetch('ezcomments_block_ezcomments.htm');
    return themesideblock($blockinfo);    
}


/**
 * modify block settings
 */
function EZComments_EZCommentsblock_modify($blockinfo)
{
    if (!pnSecAuthAction(0, 'EZComments:EZCommentsblock:', "$blockinfo[title]::", ACCESS_ADMIN)) {
        return;
    }    

    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Create output object
	$pnRender =& new pnRender('EZComments');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // assign the block vars
	$pnRender->assign($vars);

    // Return the output that has been generated by this function
	return $pnRender->fetch('ezcomments_block_ezcomments_modify.htm');
}

/**
 * update block settings
 */
function EZComments_EZCommentsblock_update($blockinfo)
{
    list ($vars['numentries'], $vars['showusername'], $vars['linkusername'], $vars['showdate']) = 
		pnVarCleanFromInput('numentries', 'showusername', 'linkusername', 'showdate');

	// write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

	// clear the block cache
	$pnRender =& new pnRender('EZComments');
	$pnRender->clear_cache('ezcomments_block_ezcomments.htm');
	
    return $blockinfo;
}

?>