<?php

declare(strict_types=1);

namespace Zikula\EZCommentsModule\Controller;

/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @see https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Zikula\ExtensionsModule\AbstractExtension;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;
use Zikula\EZCommentsModule\Entity\Repository\EZCommentsEntityRepository;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;

class CommentController extends AbstractController
{
    /**
     * @var EZCommentsEntityRepository
     */
    private $repository;

    public function __construct(
        AbstractExtension $extension,
        PermissionApiInterface $permissionApi,
        VariableApiInterface $variableApi,
        TranslatorInterface $translator,
        EZCommentsEntityRepository $repository
    ) {
        parent::__construct($extension, $permissionApi, $variableApi, $translator);
        $this->repository = $repository;
    }

    /**
     * @Route("/comment", options={"expose"=true, "i18n"=false})
     */
    public function commentAction(
        CurrentUserApiInterface $currentUserApi,
        Request $request
    ): Response {
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_COMMENT)) {
            return new JsonResponse($this->trans('Access forbidden since you cannot add comments.'), Response::HTTP_FORBIDDEN);
        }

        return $this->_persistComment($request, $currentUserApi->get('uid'), 'HTML');
    }

    /**
     * Find out if this is a banned poster. If this is true, the number of posts by this user will be equal
     * to the number of banned posts. This saves having to keep track of this with another database.
     */
    private function _bannedPoster(int $id): bool
    {
        $postsByUser = $this->repository->findBy(['ownerid' => $id]);
        $postsBannedByUser = $this->repository->findBy(['ownerid' => $id, 'status' => 1]);
        $countPosts = count($postsByUser);
        //if they don't have any posts or just 1, they cannot be banned.
        if ($countPosts <= 1) {
            return false;
        }

        return $countPosts === count($postsBannedByUser);
    }

    /**
     * Save a comment to the database. We check for banned posters and prevent them from adding comments.
     */
    private function _persistComment(Request $request, int $ownerId, $responseRet = 'JSON'): Response
    {
        //check to see if commenter is banned. This will happen if the number of comments they have is
        //equal to the number of banned comments. (NOTE if they only posted one comment and it was banned, but you still
        //want them to be able to post, then just delete that comment and communicate with them.
        $id = $request->request->getInt('id');
        $comment = $request->request->get('comment');
        $subject = $request->request->get('subject');
        $user= $request->request->get('user');

        $em = $this->getDoctrine()->getManager();
        $commentObj = null;
        $isEdit = false;
        if (isset($id) && (0 !== $id)) {
            $commentObj = $this->repository->findOneBy(['id' => $id]);
            $isEdit = true;
        } else {
            $commentObj = new EZCommentsEntity();
            $commentObj->setFromRequest($request, $ownerId);
        }
        $commentObj->setComment($comment);
        $commentObj->setSubject($subject);
        //Now record the comment
        $em->persist($commentObj);
        $em->flush();
        //If this has come from a JSON response, then return the data to be inserted into the form
        //else this is coming from an HTML POST request. Then return a redirect response
        if ('JSON' === $responseRet) {
            $jsonReply = ['author' => $user,
                'comment' => $comment,
                'subject' => $subject,
                'artId' => $commentObj->getObjectid(),
                'id' => $commentObj->getId(),
                'parentID' => $commentObj->getReplyto(),
                'uid' => $commentObj->getOwnerid(),
                'isEdit' => $isEdit
            ];

            return new JsonResponse($jsonReply);
        }

        return $this->redirect($commentObj->getUrl());
    }

    /**
     * @Route("/setcomment", options={"expose"=true, "i18n"=false}, methods={"POST"})
     */
    public function setcommentAction(
        CurrentUserApiInterface $currentUserApi,
        Request $request
    ): Response {
        $allowAnon = $this->getVar('allowanon');
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_COMMENT) && !$allowAnon) {
            return new JsonResponse($this->trans('Access forbidden since you cannot add comments.'), Response::HTTP_FORBIDDEN);
        }

        return $this->_persistComment($request, $currentUserApi->get('uid'), 'JSON');
    }

    /**
     * @Route("/verifycomment", options={"expose"=true, "i18n"=false}, methods={"POST"})
     */
    public function verifycommentAction(
        CurrentUserApiInterface $currentUserApi,
        Request $request
    ): JsonResponse {
        $allowAnon = $this->getVar('allowanon');
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_COMMENT) && !$allowAnon) {
            return new JsonResponse($this->trans('Access forbidden since you cannot add comments.'), Response::HTTP_FORBIDDEN);
        }
        $ownerId = $currentUserApi->get('uid');
        if ($this->_bannedPoster($ownerId)) {
            //send back a different JSON reqeust.
            return  new JsonResponse(['verified' => false,
                'reason' => 'ban',
                'message' => $this->trans("You have been banned by the administrator of this web site to post comments")
            ]);
        }
        $comment = $request->request->get('comment');
        $comment = nl2br($comment);
        $subject = $request->request->get('subject');
        $user= $request->request->get('user');
        $artId = $request->request->get('artId');
        $module = $request->request->get('module');
        $areaId = $request->request->get('areaId');
        $parentID = $request->request->get('parentID');
        $retURL = $request->request->get('retUrl');
        $anonEmail = "";
        $anonWebsite = "";
        if (1 === $ownerId) { //This happens when not logged in.
            //this is not a logged in user.
            $anonEmail = $request->request->get('anonEmail');
            $anonWebsite = $request->request->get('anonWebsite');
        } else {
            if ("" === $user) {
                return  new JsonResponse(['verified' => false,
                    'reason' => 'user',
                    'message' => $this->trans("Please provide a username for your comment.")
                ]);
            }
        }
        if (!isset($artId)) {
            //There is a problem with the comment. Hopefuly this never happens
            return  new JsonResponse(['verified' => false,
                'reason' => 'struct',
                'message' => $this->trans("There is a problem with how the reply structure was set. Please reload the page and try again.")
            ]);
        }
        if ("" === $comment) {
            return  new JsonResponse(['verified' => false,
                'reason' => 'comment',
                'message' => $this->trans("Your comment is empty, please enter some text before submitting your comment.")
            ]);
        }

        /*if($subject == ""){
            //There is a problem with the comment. Hopefuly this never happens
            return  new JsonResponse(['verified' => false,
                'reason' => 'subject',
                'message' => $this->trans("Please enter a subject for your comment.")
            ]);
        }*/
        $id = $request->request->get('id');

        return  new JsonResponse(['verified' => true,
            'comment' => $comment,
            'subject' => $subject,
            'user' => $user,
            'artId' => $artId,
            'module' => $module,
            'areaId' => $areaId,
            'parentID' => $parentID,
            'retUrl' => $retURL,
            'anonEmail' => $anonEmail,
            'anonWebsite' => $anonWebsite,
            'id' => $id]);
    }

    /**
     * @Route("/getuserid", options={"expose"=true, "i18n"=false}, methods={"GET"})
     */
    public function getuseridAction(
        CurrentUserApiInterface $currentUserApi
    ): JsonResponse {
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_READ)) {
            return new JsonResponse($this->trans('Access forbidden since you cannot read comments.'), Response::HTTP_FORBIDDEN);
        }
        $uid = $currentUserApi->get('uid');
        $jsonReply = ['uid' => $uid];

        return new JsonResponse($jsonReply);
    }

    /**
     * @Route("/getreplies", options={"expose"=true, "i18n"=false}, methods={"GET"})
     *
     * Grab all comments associated with this module and item ID and return them to the caller
     * The caller is a javascript, see the javascripts in Resources/public/js directory
     */
    public function getrepliesAction(Request $request): JsonResponse
    {
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_READ)) {
            return new JsonResponse($this->trans('Access forbidden since you cannot read comments.'), Response::HTTP_FORBIDDEN);
        }

        $mod = $request->query->get('module');
        $id = $request->query->get('id');
        $parentId = $request->query->get('parentId');
        //find the child items to the root parent comment (parentID). Order them in ASC order
        //todo: make the order user configurable.
        $items = $this->repository->findBy(['modname' => $mod, 'objectid' => $id, 'replyto'=> $parentId], ['date' => 'DESC']);
        //Package this in a JSON object.
        $jsonReply = [];
        foreach ($items as $item) {
            //do not include banned comments
            if (0 === $item->getStatus()) {
                $uid = $item->getOwnerid();
                $jsonReply[] = ['author' => $item->getAnonName(),
                    'comment' => $item->getComment(),
                    'subject' => $item->getSubject(),
                    'id' => $item->getId(),
                    'parentid' => $item->getReplyto(),
                    'uid' => $uid,
                    'avatar' => $this->render('@ZikulaEZCommentsModule/Comment/ezcomments_comment_avatar.html.twig', [
                        'uid' => $uid])->getContent()];
            }
        }

        return  new JsonResponse($jsonReply);
    }

    /**
     * @Route("/deletecomment", options={"expose"=true, "i18n"=false}, methods={"POST"})
     */
    public function deletecommentAction(
        CurrentUserApiInterface $currentUserApi,
        Request $request
    ): JsonResponse {
        //get the current user
        $uid = $currentUserApi->get('uid');
        $userId = $request->request->get('uid');
        //if the user ID does not match or you do not have delete access, then you don't have permission.
        if (($uid !== $userId) && (!$this->hasPermission($this->name . '::', '::', ACCESS_DELETE))) {
            return new JsonResponse($this->trans('Access forbidden since you cannot delete comments.'), Response::HTTP_FORBIDDEN);
        }
        $commentId = $request->request->get('commentId');
        if (!isset($commentId) || !isset($userId)) {
            return new JsonResponse($this->trans('Access Denied.'), Response::HTTP_FORBIDDEN);
        }
        //find the comment
        $comment = $this->repository->findOneBy(['id' => $commentId]);
        $isAdmin = $this->hasPermission('EZComments::', '::', ACCESS_ADMIN);
        $jsonReply = ['comdel' => false];
        if (null !== $comment) {
            if (($userId !== $comment->getOwnerId()) && !$isAdmin) {
                return new JsonResponse($this->trans('Access Denied.'), Response::HTTP_FORBIDDEN);
            }
            $em = $this->getDoctrine()->getManager();
            //determine if there are other comments to this comment
            $parentId = $comment->getReplyto();
            $em->remove($comment);
            //we may need to get rid of replies, do it
            $this->repository->deleteReplies($commentId);
            $em->flush();
            $items = $this->repository->findOneBy(['replyto' => $parentId]);
            if (null !== $items) {
                //there are other replies to this item, set parentId to -1
                $parentId = -1;
            }
            $jsonReply = ['comdel' => true,
                            'parentid' => $parentId,
                            'id' => $commentId];
        }

        return new JsonResponse($jsonReply);
    }
}
