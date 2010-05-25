<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

class EZComments_user extends AbstractController
{
    /**
     * Return to index page
     *
     * This is the default function called when EZComments is called
     * as a module. As we do not intend to output anything, we just
     * redirect to the start page.
     *
     * @since 0.2
     */
    public function main($args = array())
    {
        if (!UserUtil::isLoggedIn()) {
            return System::redirect(System::getHomepageUrl);
        }

        // the following code was taken from the admin interface first and modified
        // that only own comments are shown on the overview page.

        // get the status filter
        $status = isset($args['status']) ? $args['status'] : FormUtil::getPassedValue('status', -1, 'GETPOST');
        if (!isset($status) || !is_numeric($status) || $status < -1 || $status > 2) {
            $status = -1;
        }

        // presentation values
        $startnum = isset($args['startnum']) ? $args['startnum'] : FormUtil::getPassedValue('startnum', null, 'GETPOST');
        $itemsperpage = ModUtil::getVar('EZComments', 'itemsperpage');

        // call the api to get all current comments that are from the user or the user's content
        $params = array('startnum' => $startnum,
                        'numitems' => $itemsperpage,
                        'status'   => $status,
                        'owneruid' => UserUtil::getVar('uid'),
                        'uid'      => UserUtil::getVar('uid'));

        $items = ModUtil::apiFunc('EZComments', 'user', 'getall', $params);

        if ($items === false) {
            return LogUtil::registerError($this->__('Internal Error.'));
        }

        // loop through each item adding the relevant links
        foreach ($items as $k => $item)
        {
            $options   = array();
            $options[] = array('url'   => $item['url'] . '#comment' . $item['id'],
                               'image' => 'demo.gif',
                               'title' => $this->__('View'));

            // Security check
            $securityCheck = ModUtil::apiFunc('EZComments', 'user', 'checkPermission',
                                          array('module'    => '',
                                                'objectid'  => '',
                                                'commentid' => $item['id'],
                                                'uid'       => $item['uid'],
                                                'level'     => ACCESS_EDIT));

            if ($securityCheck) {
                $options[] = array('url'   => ModUtil::url('EZComments', 'user', 'modify', array('id' => $item['id'])),
                                   'image' => 'xedit.gif',
                                   'title' => $this->__('Edit'));
            }

            $items[$k]['options'] = $options;
        }

        // Create output object
        $renderer = Renderer::getInstance('EZComments', false);

        // assign the module vars
        $renderer->assign(ModUtil::getVar('EZComments'));

        // assign the items to the template, values for the filters
        $renderer->assign('items',  $items);
        $renderer->assign('status', $status);

        // values for the smarty plugin to produce a pager
        $renderer->assign('ezc_pager', array('numitems'     => ModUtil::apiFunc('EZComments', 'user', 'countitems', $params),
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
     * @param $args['mod']           Module that the item belongs to
     * @param $args['objectid']      ID of the item to display comments for
     * @param $args['extrainfo']     URL to return to if user chooses to comment
     * @param $args['owneruid']      User ID of the content owner
     * @param $args['useurl']        Url used for storing in db and in email instead of redirect url
     * @param $args['template']      Template file to use (with extension)
     * @return output the comments
     * @since 0.1
     */
    public function view($args)
    {
        // work out the input from the hook
        $mod      = isset($args['mod']) ? $args['mod'] : ModUtil::getName();
        $objectid = isset($args['objectid']) ? $args['objectid'] : '';

        // security check
        if (!SecurityUtil::checkPermission('EZComments::', "$mod:$objectid:", ACCESS_OVERVIEW)) {
            return LogUtil::registerPermissionError();
        }

        $owneruid = (int)$args['extrainfo']['owneruid'];
        $useurl   = isset($args['extrainfo']['useurl']) ? $args['extrainfo']['useurl'] : null;

        // we may have a comment incoming
        $ezcomment = unserialize(SessionUtil::getVar('ezcomment', 'a:0:{}'));
        $ezcomment = isset($ezcomment[$mod][$objectid]) ? $ezcomment[$mod][$objectid] : null;

        // we may get some input in from the navigation bar
        $order = FormUtil::getPassedValue('order');
        $sortorder = ($order == 1) ? 'DESC' : 'ASC';

        $status = 0;

        // check if we're using the pager
        $enablepager = ModUtil::getVar('EZComments', 'enablepager');
        if ($enablepager) {
            $numitems = ModUtil::getVar('EZComments', 'commentsperpage');
            $startnum = FormUtil::getPassedValue('comments_startnum');
            if (!isset($startnum) && !is_numeric($startnum)) {
                $startnum = -1;
            }
        } else {
            $startnum = -1;
            $numitems = -1;
        }

        $items = ModUtil::apiFunc('EZComments', 'user', 'getall',
        compact('mod', 'objectid', 'sortorder', 'status', 'numitems', 'startnum'));

        if ($items === false) {
            return LogUtil::registerError($this->__('Internal Error.'), null, 'index.php');
        }

        $items = ModUtil::apiFunc('EZComments', 'user', 'prepareCommentsForDisplay', $items);

        if ($enablepager) {
            $commentcount = ModUtil::apiFunc('EZComments', 'user', 'countitems', compact('mod', 'objectid', 'status'));
        } else {
            $commentcount = count($items);
        }

        // create the output object
        $renderer = Renderer::getInstance('EZComments', false, null, true);

        $renderer->assign('comments',     $items);
        $renderer->assign('commentcount', $commentcount);
        $renderer->assign('ezcomment',    $ezcomment);
        $renderer->assign('ezc_info',     compact('mod', 'objectid', 'sortorder', 'status'));
        $renderer->assign('modinfo',      ModUtil::getInfo(ModUtil::getIdFromName($mod)));
        $renderer->assign('msgmodule',    System::getVar('messagemodule', ''));
        $renderer->assign('prfmodule',    System::getVar('profilemodule', ''));
        $renderer->assign('allowadd',     SecurityUtil::checkPermission('EZComments::', "$mod:$objectid:", ACCESS_COMMENT));
        $renderer->assign('loggedin',     UserUtil::isLoggedIn());

        if (!is_array($args['extrainfo'])) {
            $redirect = $args['extrainfo'];
        } else {
            $redirect = $args['extrainfo']['returnurl'];
        }
        // encode the url - otherwise we can get some problems out there....
        $redirect = base64_encode($redirect);
        $renderer->assign('redirect',     $redirect);
        $renderer->assign('objectid',     $objectid);

        // assign the user is of the content owner
        $renderer->assign('owneruid',     $owneruid);

        // assign url that should be stored in db and sent in email if it
        // differs from the redirect url
        $renderer->assign('useurl',       $useurl);

        // assign all module vars (they may be useful...)
        $renderer->assign('modvars', ModUtil::getVar('EZComments'));

        // just for backward compatibility - TODO: delete in 2.x
        $renderer->assign('anonusersinfo', ModUtil::getVar('EZComments', 'anonusersinfo'));

        // flag to recognize the main call
        static $mainScreen = true;
        $renderer->assign('mainscreen',   $mainScreen);
        $mainScreen = false;

        // assign the values for the pager
        $renderer->assign('ezc_pager', array('numitems'     => $commentcount,
                                             'itemsperpage' => $numitems));

        // find out which template and stylesheet to use
        $templateset = isset($args['template']) ? $args['template'] : FormUtil::getPassedValue('eztpl');
        $css         = isset($args['ezccss'])   ? $args['ezccss']   : FormUtil::getPassedValue('ezccss');
        $defaultcss  = ModUtil::getVar('EZComments', 'css', 'style.css');

        if (!$renderer->template_exists(DataUtil::formatForOS($templateset) . '/ezcomments_user_view.htm')) {
            $templateset = ModUtil::getVar('EZComments', 'template', 'Standard');
        }
        $renderer->assign('template', $templateset);

        // include stylesheet if there is a style sheet
        $css = $css ? "$css.css" : $defaultcss;
        if ($css = ModUtil::apiFunc('EZComments', 'user', 'getStylesheet', array('path' => "$templateset/$css"))) {
            PageUtil::addVar('stylesheet', $css);
        }

        return $renderer->fetch(DataUtil::formatForOS($templateset) . '/ezcomments_user_view.htm');
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
    public function comment($args)
    {
        $mod         = isset($args['mod'])      ? $args['mod']      : FormUtil::getPassedValue('mod',      null, 'POST');
        $objectid    = isset($args['objectid']) ? $args['objectid'] : FormUtil::getPassedValue('objectid', null, 'POST');
        $redirect    = isset($args['redirect']) ? $args['redirect'] : FormUtil::getPassedValue('redirect', null, 'POST');
        $useurl      = isset($args['useurl'])   ? $args['useurl']   : FormUtil::getPassedValue('useurl',   null, 'POST');
        $comment     = isset($args['comment'])  ? $args['comment']  : FormUtil::getPassedValue('comment',  null, 'POST');
        $subject     = isset($args['subject'])  ? $args['subject']  : FormUtil::getPassedValue('subject',  null, 'POST');
        $replyto     = isset($args['replyto'])  ? $args['replyto']  : FormUtil::getPassedValue('replyto',  null, 'POST');
        $order       = isset($args['order'])    ? $args['order']    : FormUtil::getPassedValue('order',    null, 'POST');
        $owneruid    = isset($args['owneruid']) ? $args['owneruid'] : FormUtil::getPassedValue('owneruid', null, 'POST');
        $template    = isset($args['template']) ? $args['template'] : FormUtil::getPassedValue('template', null, 'POST');
        $stylesheet  = isset($args['ezccss'])   ? $args['ezccss']   : FormUtil::getPassedValue('ezccss',   null, 'POST');

        if ($order == 1) {
            $sortorder = 'DESC';
        } else {
            $sortorder = 'ASC';
        }

        $status = 0;

        // check if commenting is setup for the input module
        if (!ModUtil::available($mod) || !ModUtil::isHooked('EZComments', $mod)) {
            return LogUtil::registerPermissionError();
        }

        // check if we're using the pager
        $enablepager = ModUtil::getVar('EZComments', 'enablepager');
        if ($enablepager) {
            $numitems = ModUtil::getVar('EZComments', 'commentsperpage');
            $startnum = FormUtil::getPassedValue('comments_startnum');
            if (!isset($startnum) && !is_numeric($startnum)) {
                $startnum = -1;
            }
        } else {
            $startnum = -1;
            $numitems = -1;
        }

        $items = ModUtil::apiFunc('EZComments', 'user', 'getall',
        compact('mod', 'objectid', 'sortorder', 'status', 'numitems', 'startnum'));

        if ($items === false) {
            return LogUtil::registerError($this->__('Internal Error.'), null, 'index.php');;
        }

        $items = ModUtil::apiFunc('EZComments', 'user', 'prepareCommentsForDisplay', $items);

        if ($enablepager) {
            $commentcount = ModUtil::apiFunc('EZComments', 'user', 'countitems', compact('mod', 'objectid'));
        } else {
            $commentcount = count($items);
        }

        // don't use caching (for now...)
        $renderer = Renderer::getInstance('EZComments', false, null, true);

        $renderer->assign('comments',     $items);
        $renderer->assign('commentcount', $commentcount);
        $renderer->assign('order',        $sortorder);
        $renderer->assign('redirect',     $redirect);
        $renderer->assign('allowadd',     SecurityUtil::checkPermission('EZComments::', "$mod:$objectid: ", ACCESS_COMMENT));
        $renderer->assign('mod',          DataUtil::formatForDisplay($mod));
        $renderer->assign('objectid',     DataUtil::formatForDisplay($objectid));
        $renderer->assign('subject',      DataUtil::formatForDisplay($subject));
        $renderer->assign('replyto',      DataUtil::formatForDisplay($replyto));

        // assign the user is of the content owner
        $renderer->assign('owneruid',     $owneruid);

        // assign useurl if there was another url for email and storing submitted
        $renderer->assign('useurl',       $useurl);

        // assign all module vars (they may be useful...)
        $renderer->assign(ModUtil::getVar('EZComments'));

        // assign the values for the pager
        $renderer->assign('ezc_pager', array('numitems'     => $commentcount,
                                             'itemsperpage' => $numitems));

        // find out which template to use
        $templateset = isset($args['template']) ? $args['template'] : $template;
        $defaultcss  = ModUtil::getVar('EZComments', 'css', 'style.css');

        if (!$renderer->template_exists(DataUtil::formatForOS($templateset) . '/ezcomments_user_comment.htm')) {
            $templateset = ModUtil::getVar('EZComments', 'template', 'Standard');
        }
        $renderer->assign('template', $templateset);

        // include stylesheet if there is a style sheet
        $css = $stylesheet ? "$stylesheet.css" : $defaultcss;
        if ($css = ModUtil::apiFunc('EZComments', 'user', 'getStylesheet', array('path' => "$templateset/$css"))) {
            PageUtil::addVar('stylesheet', $css);
        }

        // FIXME comment template missing
        return $renderer->fetch(DataUtil::formatForOS($templateset) . '/ezcomments_user_view.htm');
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
    public function create($args)
    {
        $mod      = isset($args['mod'])      ? $args['mod']      : FormUtil::getPassedValue('mod',      null, 'POST');
        $objectid = isset($args['objectid']) ? $args['objectid'] : FormUtil::getPassedValue('objectid', null, 'POST');
        $comment  = isset($args['comment'])  ? $args['comment']  : FormUtil::getPassedValue('comment',  null, 'POST');
        $subject  = isset($args['subject'])  ? $args['subject']  : FormUtil::getPassedValue('subject',  null, 'POST');
        $replyto  = isset($args['replyto'])  ? $args['replyto']  : FormUtil::getPassedValue('replyto',  null, 'POST');
        $owneruid = isset($args['owneruid']) ? $args['owneruid'] : FormUtil::getPassedValue('owneruid',  null, 'POST');

        $redirect = isset($args['redirect']) ? $args['redirect'] : FormUtil::getPassedValue('redirect', null, 'POST');
        $useurl   = isset($args['useurl'])   ? $args['useurl']   : FormUtil::getPassedValue('useurl',   null, 'POST');

        // check if the user logged in and if we're allowing anon users to
        // set a name and e-mail address
        if (!UserUtil::isLoggedIn()) {
            $anonname    = isset($args['anonname'])    ? $args['anonname']    : FormUtil::getPassedValue('anonname',    null, 'POST');
            $anonmail    = isset($args['anonmail'])    ? $args['anonmail']    : FormUtil::getPassedValue('anonmail',    null, 'POST');
            $anonwebsite = isset($args['anonwebsite']) ? $args['anonwebsite'] : FormUtil::getPassedValue('anonwebsite', null, 'POST');
        } else {
            $anonname = '';
            $anonmail = '';
            $anonwebsite = '';
        }

        if (!isset($owneruid) || (!($owneruid > 1))) {
            $owneruid = 0;
        }

        $redirect = str_replace('&amp;', '&', base64_decode($redirect));
        $redirect = !empty($redirect) ? $redirect : System::serverGetVar('HTTP_REFERER');
        $useurl   = base64_decode($useurl);

        // save the submitted data if any error occurs
        $ezcomment = unserialize(SessionUtil::getVar('ezcomment', 'a:0:{}'));
        if (isset($ezcomment[$mod][$objectid])) {
            unset($ezcomment[$mod][$objectid]);
        }
        if (!empty($subject)) {
            $ezcomment[$mod][$objectid]['subject'] = $subject;
        }
        if (!empty($comment)) {
            $ezcomment[$mod][$objectid]['comment'] = $comment;
        }
        if (!empty($anonname)) {
            $ezcomment[$mod][$objectid]['anonname'] = $anonname;
        }
        if (!empty($anonmail)) {
            $ezcomment[$mod][$objectid]['anonmail'] = $anonmail;
        }
        if (!empty($anonwebsite)) {
            $ezcomment[$mod][$objectid]['anonwebsite'] = $anonwebsite;
        }

        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            SessionUtil::setVar('ezcomment', serialize($ezcomment));
            return LogUtil::registerAuthidError($redirect."#commentform_{$mod}_{$objectid}");
        }
        // and check we've actually got a comment....
        if (empty($comment)) {
            SessionUtil::setVar('ezcomment', serialize($ezcomment));
            return LogUtil::registerError($this->__('Error! The comment contains no text.'), null,
                                          $redirect."#commentform_{$mod}_{$objectid}");
        }

        // now parse out the hostname+subfolder from the url for storing in the DB
        $url = str_replace(System::getBaseUri(), '', $useurl);

        $id = ModUtil::apiFunc('EZComments', 'user', 'create',
                           array('mod'         => $mod,
                                 'objectid'    => $objectid,
                                 'url'         => $url,
                                 'comment'     => $comment,
                                 'subject'     => $subject,
                                 'replyto'     => $replyto,
                                 'uid'         => UserUtil::getVar('uid'),
                                 'owneruid'    => $owneruid,
                                 'useurl'      => $useurl,
                                 'redirect'    => $redirect,
                                 'anonname'    => $anonname,
                                 'anonmail'    => $anonmail,
                                 'anonwebsite' => $anonwebsite));

        // redirect if it was not successful
        if (!$id) {
            SessionUtil::setVar('ezcomment', $ezcomment);
            System::redirect($redirect."#commentform_{$mod}_{$objectid}");
        }

        // clean/set the session data 
        if (isset($ezcomment[$mod][$objectid])) {
            unset($ezcomment[$mod][$objectid]);
            if (empty($ezcomment[$mod])) {
                unset($ezcomment[$mod]);
            }
        }
        if (empty($ezcomment)) {
            SessionUtil::delVar('ezcomment');
        } else {
            SessionUtil::setVar('ezcomment', serialize($ezcomment));
        }

        return System::redirect($redirect.'#comment'.$id);
    }

    /**
     * Sort comments by thread
     *
     * @param $comments An array of comments
     * @return array The sorted array
     * @since 0.2
     */
    private function threadComments($comments)
    {
        return $this->displayChildren($comments, -1, 0);
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
    private function displayChildren($comments, $id, $level)
    {
        $childs = array();
        foreach ($comments as $comment)
        {
            if ($comment['replyto'] == $id) {
                $comment['level'] = $level;
                $childs[] = $comment;
                $childs = array_merge($childs, $this->displayChildren($comments, $comment['id'], $level+1));
            }
        }

        return $childs;
    }

    /**
     * Return an rss/atom feed of the last x comments
     *
     * @author Mark west
     */
    public function feed($args)
    {
        $mod       = isset($args['mod'])       ? $args['mod']       : FormUtil::getPassedValue('mod',   null, 'POST');
        $objectid  = isset($args['objectid'])  ? $args['objectid']  : FormUtil::getPassedValue('objectid',  null, 'POST');
        $feedtype  = isset($args['feedtype'])  ? $args['feedtype']  : FormUtil::getPassedValue('feedtype',  null, 'POST');
        $feedcount = isset($args['feedcount']) ? $args['feedcount'] : FormUtil::getPassedValue('feedcount', null, 'POST');

        // check our input
        if (!isset($feedcount) || !is_numeric($feedcount) || $feedcount < 1 || $feedcount > 999) {
            $feedcount = ModUtil::getVar('EZcomments', 'feedcount');
        }
        if (!isset($feedtype) || !is_string($feedtype) || ($feedtype !== 'rss' && $feedtype !== 'atom')) {
            $feedtype = ModUtil::getVar('EZComments', 'feedtype');
        }
        if (!isset($mod) || !is_string($mod) || !ModUtil::available($mod)) {
            $mod = null;
        }
        if (!isset($objectid) || !is_string($objectid)) {
            $objectid = null;
        }

        $comments = ModUtil::apiFunc('EZComments', 'user', 'getall',
                                 array('mod'       => $mod,
                                       'objectid'  => $objectid,
                                       'numitems'  => $feedcount,
                                       'sortorder' => 'DESC',
                                       'status'    => 0));

        // create the output object
        $renderer = Renderer::getInstance('EZComments');

        // get the last x comments
        $renderer->assign('comments'    , $comments);
        $renderer->assign('language'    , ZLanguage::getLocale());
        $renderer->assign('sitename'    , System::getVar('sitename'));
        $renderer->assign('slogan'      , System::getVar('slogan'));
        $renderer->assign('adminmail'   , System::getVar('adminmail'));
        $renderer->assign('current_date', date(DATE_RSS));

        // grab the item url from one of the comments
        if (isset($comments[0]['url'])) {
            $renderer->assign('itemurl', $comments[0]['url']);
        } else {
            // attempt to guess the url (api compliant mods only....)
            $renderer->assign('itemurl', ModUtil::url($mod, 'user', 'display', array('objectid' => $objectid)));
        }

        // display the feed and notify the core that we're done
        $renderer->display("ezcomments_user_$feedtype.htm");
        return true;
    }

    /**
     * Modify a comment
     *
     * This is a standard function that is called whenever an comment owner
     * wishes to modify a comment
     *
     * @param  tid the id of the comment to be modified
     * @return string the modification page
     */
    public function modify($args)
    {
        Loader::requireOnce('modules/EZComments/includes/common.php');
        return ezc_modify($args);
    }
}
