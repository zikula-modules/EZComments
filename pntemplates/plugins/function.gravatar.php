<?php
/**
 * Smarty function to return the url of a users gravatar
 *
 * This function takes a identifier and returns the corresponding language constant.
 *
 * For information on the usage of this plugin witin PostNuke please see
 * http://www.markwest.me.uk/Article37.phtml
 *
 * based on gravatar plugin for Wordpress
 *	Author: Tom Werner
 *	Author URI: http://www.mojombo.com/
 *
 * Available parameters:
 *   - email:    E-mail address of the user to get the gravatar for
 *   - html:     Treat the language define as HTML
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *
 * Examples
 *	<!--[pnusergetvar name=email uid=$info.aid assign=email]-->
 *	<img src="<!--[gravatar email=$email]-->" alt="" />
 *  <!--[gravatar email=$email]-->
 *  <!--[gravatar email=$email size="75"]-->
 *  <!--[gravatar email=$email default="http://www.example.com/defaultAvatar.jpg"]-->
 *  <!--[gravatar email=$email size="75" default="http://www.example.com/defaultAvatar.jpg"]-->
 *
 * @author       Mark West
 * @since        30/08/2005
 * @link         http://www.gravatar.com/
 * @link         http://www.markwest.me.uk/Article37.phtml
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the url to display the users gravatar
 */
function smarty_function_gravatar($params, &$smarty)
{
    extract($params);
    unset($params);

    if (!isset($email)) {
        $smarty->trigger_error("Error! Missing 'email' attribute for gravatar.");
        return false;
    }

	if (!isset($rating)) $rating = false;
    //if (!isset($size)) $size = false;
    if (!isset($size)) $size = 80;
	if (!isset($default)) $default = false;

	$gravatarURL = 'http://www.gravatar.com/avatar.php?gravatar_id=' . md5($email);

	if ($rating && $rating != '') $gravatarURL .= "&rating=".$rating;
	if ($size && $size != '') $gravatarURL .="&size=".$size;
	if ($default && $default != '') $gravatarURL .= "&default=".urlencode($default);

	return DataUtil::formatForDisplay($gravatarURL);
}

