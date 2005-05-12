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
 * @author      Joerg Napp <jnapp@users.sourceforge.net>
 * @author      Mark West <markwest at postnuke dot com>
 * @author      Jean-Michel Vedrine
 * @version     0.8
 * @link        http://lottasophie.sourceforge.net Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */ 

/**
 * Smarty modifier format the output of a comment
 * 
 * The plugin compares the comment against the comment with all tags stipped 
 * to determine of there is html content. If no html content is found then 
 * each newline (\n) is converted to an close/open parapgraph pair </p><p>
 * lastly the output is wrapped in a paragraph (<p>content</p>) - this should
 * form valid html for a non formatted comment
 *
 * Example
 * 
 *   <!--[$comment|formatezcomment]-->
 * 
 * 
 * @author       Mark West
 * @since        12/5/2005
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_formatezcomment($string)
{
	// compare the stipped version with original (identical means an unformated comment)
	if ($string == strip_tags($string)) {
		// strip all carriage returns (we're only interested in newlines)
		$string = str_replace("\r", '', $string);
		// replace newlines with a paragraph delimiter
		$string = str_replace("\n", '</p><p>', $string);
		// wrap string in a parapraph
		$string = '<p>' . $string . '</p>';
		// drop any empty parapgraphs
		$string = str_replace('<p></p>', '', $string);
	}
    return $string;
}

?>