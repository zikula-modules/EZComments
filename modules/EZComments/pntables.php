<?php
// $Id$
// LICENSE
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Jörg Napp, http://postnuke.lottasophie.de
// ----------------------------------------------------------------------

/**
 * This function is called internally by the core whenever the module is
 * loaded.  
 */
function EZComments_pntables()
{
    $pntable = array();
    $EZComments = pnConfigGetVar('prefix') . '_EZComments';
    $pntable['EZComments'] = $EZComments;
    $pntable['EZComments_column'] = array('id'       => $EZComments . '.id',
                                          'modname'  => $EZComments . '.modname',
                                          'objectid' => $EZComments . '.objectid',
                                          'url'      => $EZComments . '.url',
                                          'date'     => $EZComments . '.date',
                                          'uid'	     => $EZComments . '.uid',
                                          'comment'  => $EZComments . '.comment');
    return $pntable;
}
?>