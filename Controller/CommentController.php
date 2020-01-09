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

    /**
     * @param $id
     * @return bool
     * Find out if this is a banned poster. If this is true, the number of posts by this user will be equal
     * to the number of banned posts. This saves having to keep track of this with another database.
     */

    private function _bannedPoster($id){
        $repo = $this->getDoctrine()->getManager()->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
        $postsByUser = $repo->findBy(['ownerid' => $id]);
        $postsBannedByUser = $repo->findBy(['ownerid' => $id, 'status' => 1]);
        $countPosts = count($postsByUser);
        //if they don't have any posts or just 1, they cannot be banned.
        if($countPosts <= 1){
            return false;
        }
        return ($countPosts === count($postsBannedByUser));

    }

    /**
     * @param $request
     * @param string $responseRet
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|FatalResponse
     *
     * Save a comment to the database. We check for banned posters and prevent them from adding comments.
     */
    private function _persistComment($request, $responseRet = 'JSON'){
        //check to see if commenter is banned. This will happen if the number of comments they have is
        //equal to the number of banned comments. (NOTE if they only posted one comment and it was banned, but you still
        //want them to be able to post, then just delete that comment and communicate with them.
        $ownerId = $this->get('zikula_users_module.current_user')->get('uid');
        if($this->_bannedPoster($ownerId) && ($responseRet === 'JSON')){
            //send back a different JSON reqeust.
            $jsonReply[] = ['id' => -1,
                'comment' => $this->__("You have been banned by the administrator of this web site to post comments")
                ];

            return  new JsonResponse($jsonReply);
        }
        $id = $request->request->get('id');
        $comment = $request->request->get('comment');
        $subject = $request->request->get('subject');
        $user= $request->request->get('user');

        $em = $this->getDoctrine()->getManager();
        $commentObj = null;
        $isEdit = false;
        if(isset($id) && ($id != 0)){
            $commentObj = $em->getRepository('ZikulaEZCommentsModule:EZCommentsEntity')->findOneBy(['id' => $id]);
            $isEdit = true;
        } else {
            $commentObj = new EZCommentsEntity();
            $artId = $request->request->get('artId');
            $module = $request->request->get('module');
            $areaId = $request->request->get('areaId');
            $parentID = $request->request->get('parentID');
            $retURL = $request->request->get('retUrl');
            $ipaddr = $request->getClientIp();
            if($ownerId == 1){ //This happens when not logged in.
                //this is not a logged in user.
                $anonEmail = $request->request->get('anonEmail');
                $anonWebsite = $request->request->get('anonWebsite');

            } else {
                $anonEmail = "";
                $anonWebsite = "";
            }
            $commentObj->setUrl($retURL);
            $commentObj->setObjectid($artId);
            $commentObj->setAreaid($areaId);
            $commentObj->setModname($module);
            $commentObj->setAreaid($areaId);
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
        }
        $commentObj->setComment($comment);
        $commentObj->setSubject($subject);
        //Now record the comment
        $em->persist($commentObj);
        $em->flush();
        //If this has come from a JSON response, then return the data to be inserted into the form
        //else this is coming from an HTML POST request. Then return a redirect response
        if($responseRet = 'JSON'){
            $jsonReply[] = ['author' => $user,
                'comment' => $comment,
                'subject' => $subject,
                'artId' => $commentObj->getObjectid(),
                'id' => $commentObj->getId(),
                'parentID' => $commentObj->getReplyto(),
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
        $allowAnon = $this->getVar('allowanon');
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_COMMENT) && !$allowAnon) {
            return new ForbiddenResponse($this->__('Access forbidden since you cannot add comments.'));
        }
        return $this->_persistComment($request, 'JSON');
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
     *
     * Grab all comments associated with this module and item ID and return them to the caller
     * The caller is a javascript, see the javascripts in Resources/public/js directory
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
        //Package this in a JSON object.
        $jsonReply = [];

        foreach($items as $item){
            //do not include banned comments
            if($item->getStatus() === 0){
                $jsonReply[] = ['author' => $item->getAnonName(),
                    'comment' => $item->getComment(),
                    'subject' => $item->getSubject(),
                    'id' => $item->getId(),
                    'uid' => $item->getOwnerid()];
            }
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
        //get the current user
        $currentUserApi = $this->get('zikula_users_module.current_user');
        $uid = $currentUserApi->get('uid');
        $userId = $request->request->get('uid');
        //if the user ID does not match or you do not have delete access, then you don't have permission.
        if(($uid != $userId) && (!$this->hasPermission($this->name . '::', '::', ACCESS_DELETE)) ){
            return new ForbiddenResponse($this->__('Access forbidden since you cannot delete comments.'));
        }
        $commentId = $request->request->get('commentId');
        if(!isset($commentId) || !isset($userId)){
            return new ForbiddenResponse($this->__('Access Denied'));
        }
        $repo = $this->getDoctrine()->getManager()->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
        //find the comment
        $comment = $repo->findOneBy(['id' => $commentId]);
        $isAdmin = $this->hasPermission('EZComments::', '::', ACCESS_ADMIN);
        $jsonReply = ['comdel' => false];
        if(null != $comment) {
            if( ($userId != $comment->getOwnerId()) && !$isAdmin){
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
