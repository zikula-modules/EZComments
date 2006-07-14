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
 * @version     1.3
 * @link        http://noc.postnuke.com/projects/ezcomments/ Support and documentation
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Postnuke
 * @subpackage  EZComments
 */

/**
 * pass the comment to the akismet service to check if the comment is spam
 *
 * @param author string author
 * @param authoremail string author E-mail address
 * @param authorurl string author URL
 * @param content string comment content
 * @param permalink string permalink
 */
function ezcomments_akismetapi_check($args)
{
    // argument check
    if (!isset($args['author']) || 
        !isset($args['authoremail']) || 
        !isset($args['authorurl']) || 
        !isset($args['content']) || 
        !isset($args['permalink'])) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // load the class
    // TODO php4/5 selector
    require_once('modules/EZComments/pnincludes/Akismet.class.4.php');

    // create the new object
    $akismet = new Akismet(pnGetBaseURL(), pnModGetVar('EZComments', 'apikey'));

    // set the comment parameters
    $akismet->setCommentAuthor($args['author']);
    $akismet->setCommentAuthorEmail($args['authoremail']);
    $akismet->setCommentAuthorURL($args['authorurl']);
    $akismet->setCommentContent($args['content']);
    $akismet->setPermalink($args['permalink']);

    // is it spam?
    if ($akismet->isCommentSpam()) {
        return true;
    } else {
        return false;
    }
}

?>