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
 * @version     1.5
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
    // Initialise table array
    $pntable = array();

    // Full table definition
    $pntable['EZComments'] = DBUtil::getLimitedTablename('ezcomments');
    $pntable['EZComments_column'] = array('id'          => 'id',
                                          'modname'     => 'modname',
                                          'objectid'    => 'objectid',
                                          'url'         => 'url',
                                          'date'        => 'date',
                                          'uid'         => 'uid',
                                          'owneruid'	=> 'owneruid',
                                          'comment'     => 'comment',
                                          'subject'     => 'subject',
                                          'replyto'     => 'replyto',
                                          'anonname'    => 'anonname',
                                          'anonmail'    => 'anonmail',
                                          'status'      => 'status',
										  'ipaddr'      => 'ipaddr',
										  'type'        => 'type',
										  'anonwebsite' => 'anonwebsite');
    $pntable['EZComments_column_def'] = array('id'          => 'I AUTOINCREMENT PRIMARY',
                                              'modname'     => "C(64) NOTNULL DEFAULT ''",
                                              'objectid'    => "X NOTNULL DEFAULT ''",
                                              'url'         => "X NOTNULL DEFAULT ''",
                                              'date'		=> "T NOTNULL DEFAULT '1970-01-01 00:00:00'",
                                              'uid'			=> "I NOTNULL DEFAULT '0'",
                                              'owneruid'	=> "I NOTNULL DEFAULT '0'",
                                              'comment'     => "X NOTNULL DEFAULT ''",
                                              'subject'     => "X NOTNULL DEFAULT ''",
                                              'replyto'     => "I NOTNULL DEFAULT '0'",
                                              'anonname'    => "C(255) NOTNULL DEFAULT ''",
                                              'anonmail'    => "C(255) NOTNULL DEFAULT ''",
                                              'status'      => "I1 NOTNULL DEFAULT '0'",
                                              'ipaddr'      => "C(85) NOTNULL DEFAULT ''",
                                              'type'        => "C(64) NOTNULL DEFAULT ''",
                                              'anonwebsite' => "C(255) NOTNULL DEFAULT ''");

    return $pntable;
}

