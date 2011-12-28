<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

/**
 * Smarty modifier format an issue date for an atom news feed
 * 
 * Example
 * 
 *   {$MyVar|modified}
 * 
 * 
 * @author  Mark West
 * @author         Franz Skaaning
 * @since        02 March 2004
 * @param array    $string     the contents to transform
 * @return string   the modified output
 */
function smarty_modifier_modified($string)
{
    return strftime("%G-%m-%dT%H:%M:%S+01:00", strtotime($string));
}

