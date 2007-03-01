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
 * @version     1.4
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

/**
 * return the table information
 * 
 * This function is called internally by the core whenever the module is
 * loaded.  
 * 
 * @return    array    an array with the table infomation
 */
function EZComments_pntables()
{
    $pntable = array();
    $EZComments = pnConfigGetVar('prefix') . '_ezcomments';
    $pntable['EZComments'] = $EZComments;
    $pntable['EZComments_column'] = array('id'          => $EZComments . '.id',
                                          'modname'     => $EZComments . '.modname',
                                          'objectid'    => $EZComments . '.objectid',
                                          'url'         => $EZComments . '.url',
                                          'date'        => $EZComments . '.date',
                                          'uid'         => $EZComments . '.uid',
                                          'comment'     => $EZComments . '.comment',
                                          'subject'     => $EZComments . '.subject',
                                          'replyto'     => $EZComments . '.replyto',
                                          'anonname'    => $EZComments . '.anonname',
                                          'anonmail'    => $EZComments . '.anonmail',
                                          'status'      => $EZComments . '.status',
										  'ipaddr'      => $EZComments . '.ipaddr',
										  'type'        => $EZComments . '.type',
										  'anonwebsite' => $EZComments . '.anonwebsite');
    return $pntable;
}

?>