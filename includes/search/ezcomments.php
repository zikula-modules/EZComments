<?php 
// $Id$
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Search Module
// ===========================
//
// Copyright (c) 2001 by Patrick Kellum (webmaster@ctarl-ctarl.com)
// http://www.ctarl-ctarl.com
//
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
// Mark Wesy: ezcomments.php based on Patrick Kellum's reviews.php search plugin
//            purpose: search the ezcomments database.

$search_modules[] = array(
    'title' => 'EZComments',
    'func_search' => 'search_ezcomments',
    'func_opt' => 'search_ezcomments_opt'
);

function search_ezcomments_opt() 
{
    global $bgcolor2, $textcolor1;

	if (!pnModAvailable('EZComments')) {
		return;    
	}

	// load the language files for the ezcomments module
	pnModLangLoad('EZComments', 'user');

    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (pnSecAuthAction(0, 'EZComments::', '::', ACCESS_READ)) {
        $output->Text('<table border="0" width="100%">
		               <tr style="background-color:'.pnVarPrepForDisplay($bgcolor2).'">
		               <td>
					   <span style="text-color:'.pnVarPrepForDisplay($textcolor1).'">
					   <input type="checkbox" name="active_ezcomments" id="active_ezcomments" value="1" checked="checked" tabindex="0" />
					   &nbsp;<label for="active_ezcomments">'._EZCOMMENTS_SEARCH.'</label>
		               </span></td></tr></table>');
    }
    return $output->GetOutput();
}

function search_ezcomments() 
{
    list($q,
         $bool,
         $startnum,
         $total,
         $active_ezcomments) = pnVarCleanFromInput('q',
												   'bool',
												   'startnum',
												   'total',
												   'active_ezcomments');
    if (empty($active_ezcomments)) {
        return;
    }

	// get the correct db info
	pnModDBInfoLoad('EZComments');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
	$EZCommentstable = $pntable['EZComments'];
	$EZCommentscolumn = &$pntable['EZComments_column']; 

	// load the language files for the ezcomments module
	pnModLangLoad('EZComments', 'user');

    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (!isset($startnum) || !is_numeric($startnum)) {
        $startnum = 1;
    }
    if (isset($total) && !is_numeric($total)) {
    	unset($total);
    }

    $w = search_split_query($q);
    $flag = false;
    $query = "SELECT $EZCommentscolumn[id] as id,
    				 $EZCommentscolumn[modname] as modname,
    				 $EZCommentscolumn[objectid] as objectid,
    				 $EZCommentscolumn[url] as url,
    				 $EZCommentscolumn[subject] as subject,
    				 $EZCommentscolumn[comment] as comment
              FROM $EZCommentstable 
              WHERE $EZCommentscolumn[comment] != \"\" AND \n";
    foreach($w as $word) {
        if($flag) {
            switch($bool) {
                case 'AND' :
                    $query .= ' AND ';
                    break;
                case 'OR' :
                default :
                    $query .= ' OR ';
                    break;
            }
        }
		$word = pnVarPrepForStore($word);
        $query .= '(';
        $query .= "$EZCommentscolumn[subject] LIKE '$word' OR \n";
        $query .= "$EZCommentscolumn[comment] LIKE '$word'\n";
        $query .= ')';
        $flag = true;
    }
    $query .= " ORDER BY $EZCommentscolumn[id]";

	// get the total count with permissions!
    if (empty($total)) {
		$total = 0;
        $countres =& $dbconn->Execute($query);
		while(!$countres->EOF) {
			$row = $countres->GetRowAssoc(false);
            if (pnSecAuthAction(0,"EZComments::","$row[modname]:$row[objectid]:",ACCESS_READ)) {
				$total++;
			}
			$countres->MoveNext();
		}
    }

    $result = $dbconn->SelectLimit($query, 10, $startnum-1);

    if (!$result->EOF) {
        $output->Text(_EZCOMMENTS . ': ' . $total . ' ' . _SEARCHRESULTS);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text('<ul>');
        while(!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            if (pnSecAuthAction(0,"EZComments::","$row[modname]:$row[objectid]:",ACCESS_READ)) {
	            $output->Text('<li><a href="'.pnVarPrepForDisplay($row['url']).'">'.pnVarPrepForDisplay($row['subject']).'</a>');
				$output->Text('<br />'.pnVarPrepHTMLDisplay($row['comment']).'</li>');
			}
            $result->MoveNext();
        }
        $output->Text('</ul>');

        // Munge URL for template
        $urltemplate = $url . "&amp;startnum=%%&amp;total=$total";
        $output->Pager($startnum,
                       $total,
                       $urltemplate,
                       10);
    } else {
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(_EZCOMMENTS_NOCOMMENTSFOUND);
        $output->SetInputMode(_PNH_PARSEINPUT);
    }
    $output->Linebreak(3);

    return $output->GetOutput();
}
?>