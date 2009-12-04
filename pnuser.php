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
 * Return to index page
 *
 * This is the default function called when EZComments is called
 * as a module. As we do not intend to output anything, we just
 * redirect to the start page.
 *
 * @since 0.2
 */
function EZComments_user_main($args)
{
    $dom = ZLanguage::getModuleDomain('EZComments');
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnGetBaseUrl());
    }
    // the following code was taken from the admin interface first and modified
    // that only own comments are shown on the overview page.

    // get the status filter
    $status = FormUtil::getPassedValue('status', -1, 'GETPOST');
    if (!isset($status) || !is_numeric($status) || $status < -1 || $status > 2) {
        $status = -1;
    }

    // presentation values
    $itemsperpage = pnModGetVar('EZComments', 'itemsperpage');
    $startnum = FormUtil::getPassedValue('startnum', null, 'GETPOST');

    // Create output object
    $renderer = & pnRender::getInstance('EZComments', false);

    // assign the module vars
    $renderer->assign(pnModGetVar('EZComments'));

    // call the api to get all current comments that are from the user or the user's content
    $items = pnModAPIFunc('EZComments', 'user', 'getall',
                          array('startnum' => $showall == true ? true : $startnum,
                                'numitems' => $itemsperpage,
                                'status'   => $status,
                                'owneruid' => pnUserGetVar('uid'),
                                'uid'      => pnUserGetVar('uid')));

    if ($items === false) {
        return LogUtil::registerError(__('Internal Error', $dom));
    }

    // loop through each item adding the relevant links
    foreach ($items as $k => $item)
    {
        $options   = array();
        $options[] = array('url'   => $item['url'] . '#comments',
                           'image' => 'demo.gif',
                           'title' => __('View', $dom));
        $options[] = array('url'   => pnModURL('EZComments', 'user', 'modify', array('id' => $item['id'])),
                           'image' => 'xedit.gif',
                           'title' => __('Edit', $dom));

        $items[$k]['options'] = $options;
    }

    // assign the items to the template, values for the filters, values for the smarty plugin to produce a pager
    $renderer->assign('items',  $items);
    $renderer->assign('status', $status);
    $renderer->assign('pager',  array('numitems'     => pnModAPIFunc('EZComments', 'user', 'countitems',
                                                                     array('status' => $status,
                                                                           'owneruid' => pnUserGetVar('uid'),
                                                                           'uid' => pnUserGetVar('uid'))),
                                      'itemsperpage' => $itemsperpage));

    // Return the output
    return $renderer->fetch('ezcomments_user_main.htm');

}

/**
 * Display comments for a specific item
 *
 * This function provides the main user interface to the comments
 * module.
 *
 * @param $args['objectid']      ID of the item to display comments for
 * @param $args['extrainfo']     URL to return to if user chooses to comment
 * @param $args['owneruid']      User ID of the content owner
 * @param $args['useurl']        Url used for storing in db and in email instead of redirect url
 * @param $args['template']      Template file to use (with extension)
 * @return output the comments
 * @since 0.1
 */
function EZComments_user_view($args)
{
    // work out the input from the hook
    $mod      = pnModGetName();
    $objectid = $args['objectid'];

    // security check
    if (!SecurityUtil::checkPermission('EZComments::', "$mod:$objectid:", ACCESS_OVERVIEW)) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('EZComments');

    $owneruid = (int)$args['extrainfo']['owneruid'];
    $useurl   = isset($args['extrainfo']['useurl']) ? $args['extrainfo']['useurl'] : null;

    // we may get some input in from the navigation bar
    $order = FormUtil::getpassedValue('order');
    if ($order == 1) {
        $sortorder = 'DESC';
    } else {
        $sortorder = 'ASC';
    }

    $status = 0;

    // check if we're using the pager
    $enablepager = pnModGetVar('EZComments', 'enablepager');
    if ($enablepager) {
        $numitems = pnModGetVar('EZComments', 'commentsperpage');
        $startnum = FormUtil::getPassedValue('comments_startnum');
        if (!isset($startnum) && !is_numeric($startnum)) {
            $startnum = -1;
        }
    } else {
        $startnum = -1;
        $numitems = -1;
    }

    $items = pnModAPIFunc('EZComments', 'user', 'getall',
                           compact('mod', 'objectid', 'sortorder', 'status', 'numitems', 'startnum'));

    if ($items === false) {
        return LogUtil::registerError(__('Internal Error', $dom), null, 'index.php');
    }

    $comments = EZComments_prepareCommentsForDisplay($items);
    if ($enablepager) {
        $commentcount = pnModAPIFunc('EZComments', 'user', 'countitems', array('mod' => $mod, 'objectid' => $objectid));
    } else {
        $commentcount = count($comments);
    }

    // create the pnRender object
    $renderer = & pnRender::getInstance('EZComments', false, null, true);

    $renderer->assign('avatarpath', pnModGetVar('Users', 'avatarpath'));
    $renderer->assign('msgmodule', pnConfigGetVar('messagemodule', ''));
    $renderer->assign('comments',   $comments);
    $renderer->assign('commentcount', $commentcount);
    $renderer->assign('modinfo',    pnModGetInfo(pnModGetIDFromName($mod)));
    $renderer->assign('order',      $sortorder);
    $renderer->assign('allowadd',   SecurityUtil::checkPermission('EZComments::', "$mod:$objectid:", ACCESS_COMMENT));
    $renderer->assign('loggedin',   pnUserLoggedIn());

    if (!is_array($args['extrainfo'])) {
        $redirect = $args['extrainfo'];
    } else {
        $redirect = $args['extrainfo']['returnurl'];
    }
    // encode the url - otherwise we can get some problems out there....
    $redirect = base64_encode($redirect);
    $renderer->assign('redirect',    $redirect);
    $renderer->assign('objectid',   $objectid);

    // assign the user is of the content owner
    $renderer->assign('owneruid',    $owneruid);

    // assign url that should be stored in db and sent in email if it
    // differs from the redirect url
    $renderer->assign('useurl',        $useurl);

    // assign all module vars (they may be useful...)
    $renderer->assign(pnModGetVar('EZComments'));

    // assign the values for the pager
    $renderer->assign('pager', array('numitems'     => $commentcount,
                                     'itemsperpage' => $numitems));

    // find out which template to use
    $template = isset($args['template']) ? $args['template'] : FormUtil::getPassedValue('template');
    if (!$renderer->template_exists(DataUtil::formatForOS($template . '/ezcomments_user_view.htm'))) {
        $template = pnModGetVar('EZComments', 'template', 'Standard');
    }
    $renderer->assign('template', $template);

    // include stylesheet if there is a style sheet
    $css = "modules/EZComments/pntemplates/$template/style.css";
    if (file_exists($css)) {
        PageUtil::addVar('stylesheet',$css);
    }

    return $renderer->fetch(DataUtil::formatForOS($template) . '/ezcomments_user_view.htm');
}

/**
 * Display a comment form
 *
 * This function displays a comment form, if you do not want users to
 * comment on the same page as the item is.
 *
 * @param $comment the comment (taken from HTTP put)
 * @param $mod the name of the module the comment is for (taken from HTTP put)
 * @param $objectid ID of the item the comment is for (taken from HTTP put)
 * @param $redirect URL to return to (taken from HTTP put)
 * @param $subject The subject of the comment (if any) (taken from HTTP put)
 * @param $replyto The ID of the comment for which this an anser to (taken from HTTP put)
 * @param $template The name of the template file to use (with extension)
 * @todo Check out it this function can be merged with _view!
 * @since 0.2
 */
function EZComments_user_comment($args)
{
    $dom = ZLanguage::getModuleDomain('EZComments');

    $mod         = FormUtil::getPassedValue('mod',      isset($args['mod'])      ? $args['mod']      : null, 'POST');
    $objectid    = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'POST');
    $redirect    = FormUtil::getPassedValue('redirect', isset($args['redirect']) ? $args['redirect'] : null, 'POST');
    $useurl      = FormUtil::getPassedValue('useurl',   isset($args['useurl'])   ? $args['useurl']   : null, 'POST');
    $comment     = FormUtil::getPassedValue('comment',  isset($args['comment'])  ? $args['comment']  : null, 'POST');
    $subject     = FormUtil::getPassedValue('subject',  isset($args['subject'])  ? $args['subject']  : null, 'POST');
    $replyto     = FormUtil::getPassedValue('replyto',  isset($args['replyto'])  ? $args['replyto']  : null, 'POST');
    $template    = FormUtil::getPassedValue('template', isset($args['template']) ? $args['template'] : null, 'POST');
    $order       = FormUtil::getPassedValue('order',    isset($args['order'])    ? $args['order']    : null, 'POST');

    if ($order == 1) {
        $sortorder = 'DESC';
    } else {
        $sortorder = 'ASC';
    }
    $status = 0;

    // check if commenting is setup for the input module
    if (!pnModAvailable($mod) || !pnModIsHooked('EZComments', $mod)) {
        return LogUtil::registerPermissionError();
    }

    // check if we're using the pager
    $enablepager = pnModGetVar('EZComments', 'enablepager');
    if ($enablepager) {
        $numitems = pnModGetVar('EZComments', 'commentsperpage');
        $startnum = FormUtil::getPassedValue('comments_startnum');
        if (!isset($startnum) && !is_numeric($startnum)) {
            $startnum = -1;
        }
    } else {
        $startnum = -1;
        $numitems = -1;
    }
    $items = pnModAPIFunc('EZComments', 'user', 'getall',
                           compact('mod', 'objectid','sortorder','status','numitems','startnum'));

    if ($items === false) {
        return LogUtil::registerError(__('Internal Error', $dom), null, 'index.php');;
    }

    $comments = EZComments_prepareCommentsForDisplay($items);
    if ($enablepager) {
        $commentcount = pnModAPIFunc('EZComments', 'user', 'countitems', array('mod' => $mod, 'objectid' => $objectid));
    }
    else {
        $commentcount = count($comments);
    }

    // don't use caching (for now...)
    $renderer = & pnRender::getInstance('EZComments', false);

    $renderer->assign('comments',     $comments);
    $renderer->assign('commentcount', $commentcount);
    $renderer->assign('order',        $sortorder);
    $renderer->assign('allowadd',     SecurityUtil::checkPermission('EZComments::', "$mod:$objectid: ", ACCESS_COMMENT));
    $renderer->assign('addurl',       pnModURL('EZComments', 'user', 'create'));
    $renderer->assign('loggedin',     pnUserLoggedIn());
    $renderer->assign('redirect',     $redirect);
    $renderer->assign('mod',          DataUtil::formatForDisplay($mod));
    $renderer->assign('objectid',     DataUtil::formatForDisplay($objectid));
    $renderer->assign('subject',      DataUtil::formatForDisplay($subject));
    $renderer->assign('replyto',      DataUtil::formatForDisplay($replyto));

    // assign all module vars (they may be useful...)
    $renderer->assign(pnModGetVar('EZComments'));

    // assign the values for the pager
    $renderer->assign('pager', array('numitems'     => $commentcount,
                                     'itemsperpage' => $numitems));

    // assign the user is of the content owner
    $renderer->assign('owneruid',    (int)FormUtil::getPassedValue('owneruid'));

    // assign useurl if there was another url for email and storing submitted
    $renderer->assign('useurl',        $useurl);

    // find out which template to use
    $template = pnModGetVar('EZComments', 'template');
    if (!empty($template)) {
        $template = $template;
    }
    else if (isset($args['template'])) {
        $template = $args['template'];
    }

    if (!$renderer->template_exists(DataUtil::formatForOS($template . '/ezcomments_user_comment.htm'))) {
        $template = pnModGetVar('EZComments', 'template');
    }
    $renderer->assign('template', $template);


    if (!$renderer->template_exists(DataUtil::formatForOS($template . '/ezcomments_user_comment.htm'))) {
        return LogUtil::registerError(__('Internal Error', $dom), null, 'index.php');;
    }

    return $renderer->fetch(DataUtil::formatForOS($template) . '/ezcomments_user_comment.htm');
}

/**
 * Create a comment for a specific item
 *
 * This is a standard function that is called with the results of the
 * form supplied by EZComments_user_view to create a new item
 *
 * @param $comment the comment (taken from HTTP put)
 * @param $mod the name of the module the comment is for (taken from HTTP put)
 * @param $objectid ID of the item the comment is for (taken from HTTP put)
 * @param $redirect URL to return to (taken from HTTP put)
 * @param $subject The subject of the comment (if any) (taken from HTTP put)
 * @param $replyto The ID of the comment for which this an anser to (taken from HTTP put)
 * @since 0.1
 */
function EZComments_user_create($args)
{
    $dom = ZLanguage::getModuleDomain('EZComments');

    $mod         = FormUtil::getPassedValue('mod',       isset($args['mod'])       ? $args['mod']      : null, 'POST');
    $owneruid    = FormUtil::getPassedValue('owneruid',  isset($args['owneruid'])  ? $args['owneruid'] : null, 'POST');
    $objectid    = FormUtil::getPassedValue('objectid',  isset($args['objectid'])  ? $args['objectid'] : null, 'POST');
    $redirect    = FormUtil::getPassedValue('redirect',  isset($args['redirect'])  ? $args['redirect'] : null, 'POST');
    $useurl      = FormUtil::getPassedValue('useurl',    isset($args['useurl'])    ? $args['useurl']   : null, 'POST');
    $comment     = FormUtil::getPassedValue('comment',   isset($args['comment'])   ? $args['comment']  : null, 'POST');
    $subject     = FormUtil::getPassedValue('subject',   isset($args['subject'])   ? $args['subject']  : null, 'POST');
    $replyto     = FormUtil::getPassedValue('replyto',   isset($args['replyto'])   ? $args['replyto']  : null, 'POST');

    if (!isset($owneruid) || (!($owneruid > 1))) {
        $owner_uid = 0;
    }
    $redirect = base64_decode($redirect);
    $useurl   = base64_decode($useurl);

    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError($redirect);
    }

    // check we've actually got a comment....
    if (!isset($comment) || empty($comment)) {
        return LogUtil::registerError(__('Error! Sorry! The comment contains no text.', $dom), null, $redirect.'#comments');
    }

    // check if the user logged in and if we're allowing anon users to
    // set a name and e-mail address
    if (!pnUserLoggedIn()) {
        $anonname    = FormUtil::getPassedValue('anonname',    isset($args['anonname'])    ? $args['anonname']    : null, 'POST');
        $anonmail    = FormUtil::getPassedValue('anonmail',    isset($args['anonmail'])    ? $args['anonmail']    : null, 'POST');
        $anonwebsite = FormUtil::getPassedValue('anonwebsite', isset($args['anonwebsite']) ? $args['anonwebsite'] : null, 'POST');
    } else {
        $anonname = '';
        $anonmail = '';
        $anonwebsite = '';
    }

    $redirect = str_replace('&amp;', '&', $redirect);
    // now parse out the hostname from the url for storing in the DB
    $url = str_replace(pnGetBaseURL(), '', $url);

    $id = pnModAPIFunc('EZComments', 'user', 'create',
                       array('mod'         => $mod,
                             'objectid'    => $objectid,
                             'url'         => $url,
                             'comment'     => $comment,
                             'subject'     => $subject,
                             'replyto'     => $replyto,
                             'uid'         => pnUserGetVar('uid'),
                             'owneruid'    => $owneruid,
                             'useurl'      => $useurl,
                             'redirect'    => $redirect,
                             'anonname'    => $anonname,
                             'anonmail'    => $anonmail,
                             'anonwebsite' => $anonwebsite));

    return pnRedirect($redirect.'#comments');
}

/**
 * Prepare comments to be displayed
 *
 * We loop through the "raw data" returned from the API to prepare these data
 * to be displayed.
 * We check for necessary rights, and derive additional information (e.g. user
 * data) drom other modules.
 *
 * @param $items An array of comment items as returned from the API
 * @return array An array to display (augmented information / perm. check)
 * @since 0.2
 */
function EZComments_prepareCommentsForDisplay($items)
{
    foreach ($items as $k => $item)
    {
        if ($item['uid'] > 0) {
            // get the user vars and merge into the comment array
            $userinfo = pnUserGetVars($item['uid']);
            // the users url will clash with the comment url so lets move it out of the way
            $userinfo['website']   = isset($userinfo['__ATTRIBUTES__']['url']) ? $userinfo['__ATTRIBUTES__']['url'] : '';
            $items[$k]['anonname'] = '';

            // work out if the user is online
            $userinfo['online'] = false;
            if (pnModAvailable('Profile')) {
                if (pnModAPIFunc('Profile', 'memberslist', 'isonline', array('userid' => $userinfo['pn_uid']))) {
                    $userinfo['online'] = true;
                }
            }
            $items[$k] = array_merge($item, array('author' => $userinfo));
        } else {
            // if anonymous, uname is empty
            $items[$k]['uname'] = '';
            if ($items[$k]['anonname'] == '') {
                $items[$k]['anonname'] = pnConfigGetVar('anonymous');
            }
        }

        $items[$k]['del'] = pnModAPIFunc('EZComments', 'user', 'checkPermission',
                                         array('module'    => $items[$k]['mod'],
                                               'objectid'  => $items[$k]['objectid'],
                                               'commentid' => $items[$k]['id'],
                                               'uid'       => $items[$k]['uid'],
                                               'level'     => ACCESS_DELETE));
    }

    return $items;
}

/**
 * Sort comments by thread
 *
 * @param $comments An array of comments
 * @return array The sorted array
 * @since 0.2
 */
function EZComments_threadComments($comments)
{
    return EZComments_displayChildren($comments, -1, 0);
}

/**
 * Get all child comments
 *
 * This function returns all child comments to a given comment.
 * It is called recursively
 *
 * @param $comments An array of comments
 * @param $id The id of the parent comment
 * @param $level The indentation level
 * @return array The sorted array
 * @access private
 * @since 0.2
 */
function EZComments_displayChildren($comments, $id, $level)
{
    $childs = array();
    foreach ($comments as $comment)
    {
        if ($comment['replyto'] == $id) {
            $comment['level'] = $level;
            $childs[] = $comment;
            $childs = array_merge($childs, EZComments_displayChildren($comments, $comment['id'], $level+1));
        }
    }

    return $childs;
}

/**
 * return an rss/atom feed of the last x comments
 *
 * @author Mark west
*/
function EZComments_user_feed()
{
    $feedcount = FormUtil::getPassedValue('feedcount', isset($args['feedcount']) ? $args['feedcount'] : null, 'POST');
    $feedtype  = FormUtil::getPassedValue('feedtype',  isset($args['feedtype'])  ? $args['feedtype']  : null, 'POST');
    $mod       = FormUtil::getPassedValue('replyto',   isset($args['mod'])       ? $args['mod']       : null, 'POST');
    $objectid  = FormUtil::getPassedValue('objectid',  isset($args['objectid'])  ? $args['objectid']  : null, 'POST');

    // check our input
    if (!isset($feedcount) || !is_numeric($feedcount) || $feedcount < 1 || $feedcount > 999) {
        $feedcount = pnModGetVar('EZcomments', 'feedcount');
    }
    if (!isset($feedtype) || !is_string($feedtype) || ($feedtype !== 'rss' && $feedtype !== 'atom')) {
        $feedtype = pnModGetVar('EZComments', 'feedtype');
    }
    if (!isset($mod) || !is_string($mod) || !pnModAvailable($mod)) {
        $mod = null;
    }
    if (!isset($objectid) || !is_string($objectid)) {
        $objectid = null;
    }

    $comments = pnModAPIFunc('EZComments', 'user', 'getall',
                             array('numitems'  => $feedcount,
                                   'sortorder' => 'DESC',
                                   'mod'       => $mod,
                                   'objectid'  => $objectid,
                                   'status'    => 0));

    // create the pnRender object
    $renderer = & pnRender::getInstance('EZComments');

    // get the last x comments
    $renderer->assign('comments', $comments);

    // grab the item url from one of the comments
    if (isset($comments[0]['url'])) {
        $renderer->assign('itemurl', $comments[0]['url']);
    } else {
        // attempt to guess the url (api compliant mods only....)
        $renderer->assign('itemurl', pnModURL($mod, 'user', 'display', array('objectid' => $objectid)));
    }

    // display the feed and notify the core that we're done
    $renderer->display("ezcomments_user_$feedtype.htm");
    return true;
}

/**
 * process multiple comments
 *
 * This function process the comments selected in the admin view page.
 * Multiple comments may have thier state changed or be deleted
 *
 * @author The EZComments Development Team
 * @param Comments the ids of the items to be deleted
 * @param confirmation confirmation that this item can be deleted
 * @param redirect the location to redirect to after the deletion attempt
 * @return bool true on sucess, false on failure
 */
function EZComments_user_processselected($args)
{
    Loader::requireOnce('modules/EZComments/pnincludes/common.php');
    return ezc_processSelected($args);
}

/**
 * modify a comment
 *
 * This is a standard function that is called whenever an comment owner
 * wishes to modify a comment
 *
 * @author The EZComments Development Team
 * @param tid the id of the comment to be modified
 * @return string the modification page
 */
function EZComments_user_modify($args)
{
    Loader::requireOnce('modules/EZComments/pnincludes/common.php');
    return ezc_modify($args);
}
