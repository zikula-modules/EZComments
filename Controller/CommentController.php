<?php

namespace Zikula\EZCommentsModule\Controller;

/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

use Zikula\Core\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Component\SortableColumns\Column;
use Zikula\Core\Response\Ajax\ForbiddenResponse;
use Zikula\Bundle\HookBundle\Hook\ProcessHook;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;

class CommentController extends AbstractController
{
    /**
     * @Route("")
     * Return to index page
     *
     * This is the default function called when EZComments is called
     * as a module. As we do not intend to output anything, we just
     * redirect to the start page.
     *
     * @since 0.2
     */
    public function indexAction()
    {
        /*if (!UserUtil::isLoggedIn()) {
            return System::redirect(System::getHomepageUrl());
        }

        // the following code was taken from the admin interface first and modified
        // that only own comments are shown on the overview page.

        // get user id
        $uid = isset($args['uid']) ? $args['uid'] : FormUtil::getPassedValue('uid', UserUtil::getVar('uid'), 'GETPOST');
        // get the status filter
        $status = isset($args['status']) ? $args['status'] : FormUtil::getPassedValue('status', -1, 'GETPOST');
        if (!isset($status) || !is_numeric($status) || $status < -1 || $status > 2) {
            $status = -1;
        }

        // presentation values
        $startnum = isset($args['startnum']) ? $args['startnum'] : FormUtil::getPassedValue('startnum', null, 'GETPOST');
        $itemsperpage = $this->getVar('itemsperpage');

        // call the api to get all current comments that are from the user or the user's content
        $params = array('startnum' => $startnum,
                        'numitems' => $itemsperpage,
                        'status'   => $status,
                        'owneruid' => $uid,
                        'uid'      => $uid);

        $items = ModUtil::apiFunc('EZComments', 'user', 'getall', $params);

        if ($items === false) {
            return LogUtil::registerError($this->__('Internal Error.'));
        }

        // loop through each item adding the relevant links
        foreach ($items as $k => $item)
        {
            // strip domain (if mobile/desktop differ)
            $urlparts = parse_url($item['url']);
            $item['url'] = $urlparts['path'].'?'.$urlparts['query'];

            $options   = array();
            $options[] = array('url'   => $item['url'] . '#comment' . $item['id'],
                               'image' => 'kview.png',
                               'title' => $this->__('View'));

            // Security check
            $securityCheck = ModUtil::apiFunc('EZComments', 'user', 'checkPermission',
                                          array('module'    => '',
                                                'objectid'  => '',
                                                'commentid' => $item['id'],
                                                'uid'       => $item['uid'],
                                                'owneruid'  => $item['owneruid'],
                                                'level'     => ACCESS_EDIT));

            if ($securityCheck) {
                $options[] = array('url'   => ModUtil::url('EZComments', 'user', 'modify', array('id' => $item['id'])),
                                   'image' => 'xedit.png',
                                   'title' => $this->__('Edit'));
            }

            $items[$k]['options'] = $options;
        }

        $numberOfItems = ModUtil::apiFunc('EZComments', 'user', 'countitems', $params);

        $this->view->setCaching(false); // don't use caching, not so important as only registered users can see this page

        // assign collected data to the template
        $this->view->assign(ModUtil::getVar('EZComments'))
                   ->assign('items',  $items)
                   ->assign('status', $status)
                   ->assign('ezc_pager', array('numitems'     => $numberOfItems,
                                               'itemsperpage' => $itemsperpage));

        // Return the output
        return $this->view->fetch('ezcomments_user_main.tpl');*/
    }


    /**
     * @Route("/comment")
     * @param $request
     *
     * todo:This is probably something that could be part of the hook interface. I will have to check out how to get that in.
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
    public function commentAction(Request $request)
    {
        $id = $request->request->get('artId');
        $module = $request->request->get('module');
        $areaId = $request->request->get('areaId');
        $comment = $request->request->get('comment');
        $title = $request->request->get('title');
        $user= $request->request->get('user');
        $ownerId = $this->get('zikula_users_module.current_user')->get('uid');

        $returnRoute = $request->get('retUrl');
        $retURL = $this->generateUrl($returnRoute);
        $retURL .= "/$id";
        $response = $this->redirect($retURL);
        $commentObj = new EZCommentsEntity();
        $commentObj->setUrl($retURL);
        $commentObj->setModname($module);
        $commentObj->setAreaid($areaId);
        $commentObj->setComment($comment);
        $commentObj->setTitle($title);
        if(empty($ownerId)){
            //this is not a logged in user.
            $commentObj->setAnonname($user);
        } else {

        }
        stopped here. I need to figure out what ipaddr is (my guess it is the referer?)
        //Now record the comment
        $em = $this->getDoctrine();
        return $response;
        /*        $mod      = isset($args['mod'])      ? $args['mod']      : FormUtil::getPassedValue('mod',      null, 'POST');
                $objectid = isset($args['objectid']) ? $args['objectid'] : FormUtil::getPassedValue('objectid', null, 'POST');
                $areaid   = isset($args['areaid'])   ? $args['areaid']   : FormUtil::getPassedValue('areaid',   null, 'POST');
                $comment  = isset($args['comment'])  ? $args['comment']  : FormUtil::getPassedValue('comment',  null, 'POST');
                $subject  = isset($args['subject'])  ? $args['subject']  : FormUtil::getPassedValue('subject',  null, 'POST');
                $replyto  = isset($args['replyto'])  ? $args['replyto']  : FormUtil::getPassedValue('replyto',  null, 'POST');
                $owneruid = isset($args['owneruid']) ? $args['owneruid'] : FormUtil::getPassedValue('owneruid',  null, 'POST');

                $redirect = isset($args['redirect']) ? $args['redirect'] : FormUtil::getPassedValue('redirect', null, 'POST');
                $useurl   = isset($args['useurl'])   ? $args['useurl']   : FormUtil::getPassedValue('useurl',   null, 'POST');

                // check if the user logged in and if we're allowing anon users to
                // set a name and email address
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
                // check csrf token
                SessionUtil::setVar('ezcomment', serialize($ezcomment));
                $this->checkCsrfToken();
                SessionUtil::delVar('ezcomment');

                // and check we've actually got a comment....
                if (empty($comment)) {
                    SessionUtil::setVar('ezcomment', serialize($ezcomment));
                    return LogUtil::registerError($this->__('Error! The comment contains no text.'), null,
                                                  $redirect."#commentform_{$mod}_{$objectid}");
                }

                // Check hooked modules for validation
                $hookvalidators = $this->notifyHooks(new Zikula_ValidationHook('ezcomments.ui_hooks.comments.validate_edit', new Zikula_Hook_ValidationProviders()))->getValidators();
                if ($hookvalidators->hasErrors()) {
                    SessionUtil::setVar('ezcomment', serialize($ezcomment));
                    return LogUtil::registerError($this->__('Error! The hooked content does not validate. Could it possibly be that a captcha code was entered incorrectly?'), null,
                                                  $redirect."#commentform_{$mod}_{$objectid}");
                }

                // now parse out the hostname+subfolder from the url for storing in the DB
                $url = str_replace(System::getBaseUri(), '', $useurl);

                $id = ModUtil::apiFunc('EZComments', 'user', 'create',
                                   array('mod'         => $mod,
                                         'objectid'    => $objectid,
                                         'areaid'      => $areaid,
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

                if ($id) {
                    // clear respective cache
                    ModUtil::apiFunc('EZComments', 'user', 'clearItemCache', array('id' => $id, 'modname' => $mod, 'objectid' => $objectid, 'url' => $url));
                } else {
                    // redirect if it was not successful
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

                return System::redirect($redirect.'#comment'.$id);*/
    }

    /**
     * @Route("/threadcomments/{comments}")
     * @param $request
     * @param  $comments
     *
     * Sort comments by thread
     *
     * @param $comments An array of comments
     * @return array The sorted array
     * @since 0.2
     */
    private function threadCommentsAction(Request $request, EZCommentsEntity $comments)
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
    private function displayChildren(EZCommentsEntity $comments, $id, $level)
    {
        /*$childs = array();
        foreach ($comments as $comment)
        {
            if ($comment['replyto'] == $id) {
                $comment['level'] = $level;
                $childs[] = $comment;
                $childs = array_merge($childs, $this->displayChildren($comments, $comment['id'], $level+1));
            }
        }

        return $childs;*/
    }


    /**
     * @Route("/modify/{comment}")
     *
     * Modify a comment
     *
     * This is a standard function that is called whenever an comment owner
     * wishes to modify a comment
     *
     * @param  tid the id of the comment to be modified
     * @return string the modification page
     */
    public function modifyAction(Request $request, EZCommentsEntity $comment)
    {
        /*// get our input
        $id = isset($args['id']) ? $args['id'] : FormUtil::getPassedValue('id', null, 'GETPOST');

        // Security check
        $securityCheck = ModUtil::apiFunc('EZComments', 'user', 'checkPermission',
                                      array('module'    => '',
                                            'objectid'  => '',
                                            'commentid' => $id,
                                            'level'     => ACCESS_EDIT));
        if (!$securityCheck) {
            $redirect = base64_decode(FormUtil::getPassedValue('redirect'));
            if (!isset($redirect)) {
                $redirect = System::getHomepageUrl();
            }
            return LogUtil::registerPermissionError($redirect);
        }

        // Create Form output object
        $render = FormUtil::newForm('EZComments', $this);

        // Return the output that has been generated by this function
        return $render->execute("ezcomments_user_modify.tpl", new EZComments_Form_Handler_User_Modify());*/
    }
}
