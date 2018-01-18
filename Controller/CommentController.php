<?php

namespace Zikula\EZCommentsModule\Controller;

/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

use Symfony\Component\HttpFoundation\JsonResponse;
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
use Zikula\EZCommentsModule\ZikulaEZCommentsModule;

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
     */
    public function commentAction(Request $request)
    {
        return $this->_persistComment($request, 'HTML');
    }

    private function _persistComment($request, $responseRet = 'JSON'){
        $artId = $request->request->get('artId');
        $module = $request->request->get('module');
        $areaId = $request->request->get('areaId');
        $comment = $request->request->get('comment');
        $subject = $request->request->get('subject');
        $user= $request->request->get('user');
        $parentID = $request->request->get('parentID');
        $retRoute = $request->request->get('retUrl');
        $id = $request->request->get('id');

        $ownerId = $this->get('zikula_users_module.current_user')->get('uid');
        $retURL = $this->generateUrl($retRoute) . "/$artId";
        $ipaddr = $request->getClientIp();
        if($ownerId == 1){ //This happens when not logged in.
            //this is not a logged in user.
            $anonEmail = $request->request->get('anonEmail');
            $anonWebsite = $request->request->get('anonWebsite');
        } else {
            $anonEmail = "";
            $anonWebsite = "";
        }

        $em = $this->getDoctrine()->getManager();
        $commentObj = null;
        if(isset($id)){
            $commentObj = $em->getRepository('ZikulaEZCommentsModule:EZCommentsEntity')->findOneBy(['id' => $id]);
        } else {
            $commentObj = new EZCommentsEntity();
        }

        $commentObj->setUrl($retURL);
        $commentObj->setObjectid($artId);
        $commentObj->setAreaid($areaId);
        $commentObj->setModname($module);
        $commentObj->setAreaid($areaId);
        $commentObj->setComment($comment);
        $commentObj->setSubject($subject);
        $commentObj->setOwnerid($ownerId);
        if(isset($parentID)){
            $commentObj->setReplyto($parentID);
        }
        //type is either trackback, pingback, or safe. Right now this is not implemented until Akismet is upated to 2.0
        $commentObj->setType("safe");
        $commentObj->setIpaddr($ipaddr);

        $commentObj->setAnonmail($anonEmail);
        $commentObj->setAnonwebsite($anonWebsite);
        $commentObj->setAnonname($user);

        //Now record the comment
        $em->persist($commentObj);
        $em->flush();
        //If this has come from a JSON response, then return the data to be inserted into the form
        //else this is coming from an HTML POST request. Then return a redirect response
        if($responseRet = 'JSON'){
            $jsonReply[] = ['author' => $user,
                'comment' => $comment,
                'subject' => $subject,
                'artId' => $artId,
                'id' => $commentObj->getId(),
                'parentID' => $parentID,
                'uid' => $commentObj->getOwnerid()];

            return  new JsonResponse($jsonReply);
        } else {
            return $this->redirect($retURL);
        }
    }
    /**
     * @Route("/setcomment", options={"expose"=true})
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse|FatalResponse|ForbiddenResponse bid or Ajax error
     */
    public function setcommentAction(Request $request){
        return $this->_persistComment($request);
    }

    /**
     * @Route("/getuserid", options={"expose"=true})
     * @Method("GET")
     * @param Request $request
     * @return JsonResponse|FatalResponse|ForbiddenResponse bid or Ajax error
     */
    public function getuseridAction(Request $request){
        $currentUserApi = $this->get('zikula_users_module.current_user');
        $uid = $currentUserApi->get('uid');
        $jsonReply = ['uid' => $uid];
        return new JsonResponse($jsonReply);
    }

    /**
     * @Route("/getreplies", options={"expose"=true})
     * @Method("GET")
     * @param Request $request
     * @return JsonResponse|FatalResponse|ForbiddenResponse bid or Ajax error
     */

    public function getrepliesAction(Request $request){
        $mod = $request->query->get('module');
        $id = $request->query->get('id');
        $parentId = $request->query->get('parentId');
        $repo = $this->getDoctrine()->getManager()->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
        //find the child items to the root parent comment (parentID). Order them in ASC order
        //todo: make the order user configurable.
        $items = $repo->findBy(['modname' => $mod, 'objectid' => $id, 'replyto'=> $parentId], ['date' => 'DESC']);
        //I need to package this in a JSON object.
        $jsonReply = [];

        foreach($items as $item){
            $jsonReply[] = ['author' => $item->getAnonName(),
                            'comment' => $item->getComment(),
                            'subject' => $item->getSubject(),
                            'id' => $item->getId(),
                            'uid' => $item->getOwnerid()];
        }
        return  new JsonResponse($jsonReply);
    }

    /**
     * @Route("/deletecomment", options={"expose"=true})
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse|FatalResponse|ForbiddenResponse bid or Ajax error
     */
    public function deletecommentAction(Request $request)
    {
        $commentId = $request->request->get('commentId');
        $userId = $request->request->get('uid');
        if(!isset($commentId) || !isset($userId)){
            return new ForbiddenResponse($this->__('Access Denied'));
        }
        $repo = $this->getDoctrine()->getManager()->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
        //find the comment
        $comment = $repo->findOneBy(['id' => $commentId]);

        $jsonReply = ['comdel' => false];
        if(null != $comment) {
            if($userId != $comment->getOwnerId()){
                return new ForbiddenResponse($this->__('Access Denied'));
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            //we may need to get rid of replies, do it
            $repo->deleteReplies($commentId);
            $em->flush();
            $jsonReply = ['comdel' => true,
                            'id' => $commentId];
        }
        return new JsonResponse($jsonReply);
    }

}
