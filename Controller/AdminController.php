<?php

namespace Zikula\EZCommentsModule\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Zikula\Core\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Response\Ajax\FatalResponse;
use Zikula\Core\Response\Ajax\ForbiddenResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;

/**
 * @Route("/admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("")
     * @param $request - the incoming request.
     * The main entry point. List a page of comments
     *
     * @return Response
     *
     * The rendered output consisting mainly of the admin menu and a table of comments
     *
     * @throws AccessDeniedException Thrown if the user does not have the appropriate access level for the function.
     */
    public function indexAction(Request $request)
    {
        if (!$this->hasPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException($this->__('You do not have pemission to access the EZComments admin interface.'));
        }
        $repo = $this->getDoctrine()->getManager()->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');

        $items = $repo->findAll();

        return $this->render('ZikulaEZCommentsModule:Admin:ezcomments_index.html.twig', [
            'items' => $items]);
    }

    /**
     * @Route("/edit", options={"expose"=true})
     * @Method("POST")
     * @param request
     * @return JsonResponse|FatalResponse|ForbiddenResponse bid or Ajax error
     *
     * Modify a comment
     * This is a standard function that is called whenever an administrator
     * wishes to modify a comment. This returns the comment data for editing
     * Should I provide this functionality? This could be abused.
     *
     */
    public function editAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$this->hasPermission($this->name . '::', $id . "::", ACCESS_EDIT)) {
            return new ForbiddenResponse($this->__('Access forbidden since you cannot delete comments.'));
        }
        $em = $this->getDoctrine()->getManager();
        $comment = $em->find('ZikulaEZCommentsModule:EZCommentsEntity', $id);
        if(null === $comment){
            return new FatalResponse($this->__('That comment for some reason does not exist.'));
        }
        $jsonReply = [
            'comment' => $comment->getComment(),
            'subject' => $comment->getSubject(),
            'id' => $id,
        ];

        return new JsonResponse($jsonReply);
    }

    /**
     * @Route("/delete", options={"expose"=true})
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse|FatalResponse|ForbiddenResponse bid or Ajax error
     *
     * Delete item
     * This function is called when an admin wants to delete a comment
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$this->hasPermission($this->name . '::', $id . "::", ACCESS_DELETE)) {
            return new ForbiddenResponse($this->__('Access forbidden since you cannot delete comments.'));
        }
        $em = $this->getDoctrine()->getManager();
        $comment = $em->find('ZikulaEZCommentsModule:EZCommentsEntity', $id);
        if(null === $comment){
            return new FatalResponse($this->__('That comment for some reason does not exist.'));
        }
        $success = true;
        $message = "Success";
        try {
            $em->remove($comment);
            $em->flush();
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        $jsonReply[] = ['success' => $success,
            'message' => $message];

        return new JsonResponse($jsonReply);
    }

    /**
     * @Route("/deleteall/{comment}")
     * @param Request $request
     * @param EZCommentsEntity $comment
     * block users that are being annoying.
     */

    public function deleteallAction(Request $request, EZCommentsEntity $comment)
    {

    }
    /**
     * @Route("/blockuser/{comment}")
     * @param Request $request
     * @param EZCommentsEntity $comment
     * @return RedirectResponse | ForbiddenResponse | FatalResponse
     * block users that are being annoying.
     */

    public function blockuserAction(Request $request, EZCommentsEntity $comment)
    {
        //I don't know if I have to have this error checking in here, but just in case
        if(null === $comment){
            return new FatalResponse($this->__('That comment for some reason does not exist.'));
        }
        $id = $comment->getId();
        if (!$this->hasPermission($this->name . '::', $id . "::", ACCESS_EDIT)) {
            return new AccessDeniedException($this->__('Access forbidden since you cannot block comments.'));
        }
        $em = $this->getDoctrine()->getManager();

        //right now this is a toggle. In the future it may have to be more sophisticated.
        $userToBlock = $comment->getOwnerid();
        if($userToBlock === 1){
            //This is the anonymous user (guest id), you cannot group ban all the comments
            $this->addFlash('status', $this->__("Banning the anonymous user will block all comments by any anonymous posters. If you want to block all anonymous comments, change the global setting. You will need to block each inappropriate comment individually"));
        } else {
            //Find all comments with this uid
            $repo = $em->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
            $userComments = $repo->findBy(['ownerid' => $userToBlock]);
            //determine the goal (to ban or unban, based upon the first comment)
            $blocked = !$userComments[0]->getStatus();
            //walk each comment and change it's block status.
            foreach($userComments as $comment){
                $comment->setStatus($blocked);
                $em->persist($comment);
            }
            $em->flush();
            if($blocked){
                $this->addFlash('status', $this->__("User " . $comment->getAnonname() . "'s comments are banned. You can unban them by clicking on the ban icon again."));
            } else {
                $this->addFlash('status', $this->__("User ". $comment->getAnonname() . "'s comments are unbanned."));
            }
        }
        return $this->redirect($this->generateUrl('zikulaezcommentsmodule_admin_index'));
    }

    /**
     * @Route("/blockcomment/{comment}")
     * @param Request $request
     * @param EZCommentsEntity $comment
     * @return RedirectResponse | ForbiddenResponse | FatalResponse
     * block comment that is annoying
     */

    public function blockCommentAction(Request $request, EZCommentsEntity $comment)
    {
        //I don't know if I have to have this error checking in here, but just in case
        if(null === $comment){
            return new FatalResponse($this->__('That comment for some reason does not exist.'));
        }
        $id = $comment->getId();
        if (!$this->hasPermission($this->name . '::', $id . "::", ACCESS_EDIT)) {
            return new AccessDeniedException($this->__('Access forbidden since you cannot block comments.'));
        }
        //right now this is a toggle. In the future it may have to be more sophisticated.
        $blocked = !$comment->getStatus();
        $comment->setStatus($blocked);
        $em = $this->getDoctrine()->getManager();
        //presist the comment and flush
        $em->persist($comment);
        $em->flush();
        if($blocked){
            $this->addFlash('status', $this->__('Comment is banned. You can unban the comment by clicking on the ban icon again.'));
        } else {
            $this->addFlash('status', $this->__('Comment is unbanned.'));
        }
        return $this->redirect($this->generateUrl('zikulaezcommentsmodule_admin_index'));
    }


    /**
     * @Route("/commentstats")
     * @param $request
     *
     * @author Mark West
     * @return string html output
     */
    public function commentStatsAction(Request $request)
    {


    }

    /**
     * @Route("/modulestats")
     * @param $request
     *
     * display all comments for a module
     *
     * @author Mark West
     * @return string html output
     */
    public function modulestatsAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
        //an array to store the data in
        $counts = [];
        $counts['modules'] = $repo->count('modname', '', true);
        $counts['totalComments'] = $repo->count('modname');
        $counts['users'] = $repo->count('ownerid', '', true);
        $counts['lastPost'] = $repo->getLatestPost();
        $counts['firstPost'] = $repo->getEarliestPost();
        $counts['mostActive'] = $repo->mostActivePosters();
        $counts['postRate'] = $repo->findPostRate();

        return $this->render('ZikulaEZCommentsModule:Admin:ezcomments_modulestats.html.twig', [
            'counts' => $counts]);
    }

    /**
     * @Route("/deletemodule")
     * @param $request
     * @param $moduleName
     *
     * delete all comments attached to a module
     *
     * @param  modname the name of the module to delete all comments for
     * @return bool true on sucess, false on failure
     */
    public function deletemoduleAciton(Request $request, $moduleName)
    {

    }
}
