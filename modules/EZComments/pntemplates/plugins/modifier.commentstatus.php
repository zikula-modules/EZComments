<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

/**
 * Smarty modifier to return the status of comment based on the 
 * numeric status value
 * 
 * Example
 * 
 *   <!--[$MyVar|commentstatus]-->
 * 
 * 
 * @author  Mark West
 * @since        14 July 2006
 * @param array    $string     the contents to transform
 * @return string   the modified output
 */
function smarty_modifier_commentstatus($string)
{
    switch ($string) {
        case '0':
            return strtolower(_EZCOMMENTS_APPROVED);
        case '1':
            return strtolower(_EZCOMMENTS_PENDING);
        case '2':
            return strtolower(_EZCOMMENTS_REJECTED);
        case '-1':
        default:
            return;
    }
}

