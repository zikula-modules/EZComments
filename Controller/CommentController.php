<?php

namespace Zikula\EZCommentsModule\Controller;

/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

use Zikula\Core\Response\Ajax\ForbiddenResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Zikula\Core\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;


class CommentController extends AbstractController
{
    /**
     * @Route("")
     * Return to index page
     *
     * This is the default function called when EZComments is called
     * as a module. As we do not intend to output anything for users, we just
     * redirect to the admin page.
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('zikulaezcomments_admin_index'));
    }

    /**
     * @Route("/comment")
     * @param $request
     */
    public function commentAction(Request $request)
    {
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_COMMENT)) {
            return new ForbiddenResponse($this->__('Access forbidden since you cannot add comments.'));
        }
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
        $retURL = $request->request->get('retUrl');
        $id = $request->request->get('id');

        $ownerId = $this->get('zikula_users_module.current_user')->get('uid');
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
        $isEdit = false;
        if(isset($id)){
            $commentObj = $em->getRepository('ZikulaEZCommentsModule:EZCommentsEntity')->findOneBy(['id' => $id]);
            $isEdit = true;
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
                'uid' => $commentObj->getOwnerid(),
                'isEdit' => $isEdit];

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
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_COMMENT)) {
            return new ForbiddenResponse($this->__('Access forbidden since you cannot add comments.'));
        }
        return $this->_persistComment($request);
    }

    /**
     * @Route("/getuserid", options={"expose"=true})
     * @Method("GET")
     * @param Request $request
     * @return JsonResponse|FatalResponse|ForbiddenResponse bid or Ajax error
     */
    public function getuseridAction(Request $request){
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_READ)) {
            return new ForbiddenResponse($this->__('Access forbidden since you cannot read comments.'));
        }
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
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_READ)) {
            return new ForbiddenResponse($this->__('Access forbidden since you cannot read comments.'));
        }
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
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_DELETE)) {
            return new ForbiddenResponse($this->__('Access forbidden since you cannot delete comments.'));
        }
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
