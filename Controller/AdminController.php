<?php

declare(strict_types=1);

namespace Zikula\EZCommentsModule\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Zikula\ExtensionsModule\AbstractExtension;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;
use Zikula\EZCommentsModule\Entity\Repository\EZCommentsEntityRepository;
use Zikula\PermissionsModule\Annotation\PermissionCheck;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
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
     * @Route("")
     * @Theme("admin")
     * @PermissionCheck("admin")
     *
     * @return Response
     *
     * The rendered output consisting mainly of the admin menu and a table of comments
     *
     * @throws AccessDeniedException thrown if the user does not have the appropriate access level for the function
     */
    public function indexAction(Request $request)
    {
        $items = $this->repository->findAll();

        return $this->render('@ZikulaEZCommentsModule/Admin/ezcomments_index.html.twig', [
            'items' => $items]);
    }

    /**
     * @Route("/edit", options={"expose"=true}, methods={"POST"})
     * @Theme("admin")
     * @param request
     * @return JsonResponse bid or Ajax error
     *
     * Modify a comment
     * This is a standard function that is called whenever an administrator
     * wishes to modify a comment. This returns the comment data for editing
     * Should I provide this functionality? This could be abused.
     */
    public function editAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$this->hasPermission($this->name . '::', $id . "::", ACCESS_EDIT)) {
            return new JsonResponse($this->trans('Access forbidden since you cannot delete comments.'), Response::HTTP_FORBIDDEN);
        }
        $comment = $this->repository->find($id);
        if (null === $comment) {
            return new JsonResponse($this->trans('That comment for some reason does not exist.'), Response::HTTP_NOT_FOUND);
        }
        $jsonReply = [
            'comment' => $comment->getComment(),
            'subject' => $comment->getSubject(),
            'id' => $id,
        ];

        return new JsonResponse($jsonReply);
    }

    /**
     * @Route("/delete", options={"expose"=true}, methods={"POST"})
     * @Theme("admin")
     * @param Request $request
     * @return JsonResponse bid or Ajax error
     *
     * Delete item
     * This function is called when an admin wants to delete a comment
     */
    public function deleteAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$this->hasPermission($this->name . '::', $id . "::", ACCESS_DELETE)) {
            return new JsonResponse($this->trans('Access forbidden since you cannot delete comments.'), Response::HTTP_FORBIDDEN);
        }
        $comment = $this->repository->find($id);
        if (null === $comment) {
            return new JsonResponse($this->trans('That comment for some reason does not exist.'), Response::HTTP_NOT_FOUND);
        }
        $success = true;
        $message = "Success";
        try {
            $em = $this->getDoctrine()->getManager();
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
     * @Theme("admin")
     * @param Request $request
     * @param EZCommentsEntity $comment
     * block users that are being annoying
     */
    public function deleteallAction(Request $request, EZCommentsEntity $comment)
    {
    }

    /**
     * @Route("/blockuser/{comment}")
     * @Theme("admin")
     * @param Request $request
     * @param EZCommentsEntity $comment
     * @return redirectResponse|JsonResponse
     * block users that are being annoying
     */
    public function blockuserAction(Request $request, EZCommentsEntity $comment)
    {
        //I don't know if I have to have this error checking in here, but just in case
        if (null === $comment) {
            return new JsonResponse($this->trans('That comment for some reason does not exist.'), Response::HTTP_NOT_FOUND);
        }
        $id = $comment->getId();
        if (!$this->hasPermission($this->name . '::', $id . "::", ACCESS_EDIT)) {
            throw new AccessDeniedException($this->trans('Access forbidden since you cannot block comments.'));
        }
        $em = $this->getDoctrine()->getManager();

        //right now this is a toggle. In the future it may have to be more sophisticated.
        $userToBlock = $comment->getOwnerid();
        if (1 === $userToBlock) {
            //This is the anonymous user (guest id), you cannot group ban all the comments
            $this->addFlash('status', $this->trans("Banning the anonymous user will block all comments by any anonymous posters. If you want to block all anonymous comments, change the global setting. You will need to block each inappropriate comment individually"));
        } else {
            //Find all comments with this uid
            $userComments = $this->repository->findBy(['ownerid' => $userToBlock]);
            //determine the goal (to ban or unban, based upon the first comment)
            $blocked = !$userComments[0]->getStatus();
            //walk each comment and change it's block status.
            foreach ($userComments as $comment) {
                $comment->setStatus($blocked);
                $em->persist($comment);
            }
            $em->flush();
            if ($blocked) {
                $this->addFlash(
                    'status',
                    $this->trans(
                        'User %username%\'s comments are banned. You can un-ban them by clicking on the ban icon again.',
                        ['%username%' => $comment->getAnonname()]
                    )
                );
            } else {
                $this->addFlash(
                    'status',
                    $this->trans(
                        'User %username%\'s comments are unbanned.',
                        ['%username%' => $comment->getAnonname()]
                    )
                );
            }
        }

        return $this->redirect($this->generateUrl('zikulaezcommentsmodule_admin_index'));
    }

    /**
     * @Route("/blockcomment/{comment}")
     * @Theme("admin")
     * @param Request $request
     * @param EZCommentsEntity $comment
     * @return RedirectResponse|JsonResponse
     * block comment that is annoying
     */
    public function blockCommentAction(Request $request, EZCommentsEntity $comment)
    {
        //I don't know if I have to have this error checking in here, but just in case
        if (null === $comment) {
            return new JsonResponse($this->trans('That comment for some reason does not exist.'), Response::HTTP_NOT_FOUND);
        }
        $id = $comment->getId();
        if (!$this->hasPermission($this->name . '::', $id . "::", ACCESS_EDIT)) {
            throw new AccessDeniedException($this->trans('Access forbidden since you cannot block comments.'));
        }
        //right now this is a toggle. In the future it may have to be more sophisticated.
        $blocked = !$comment->getStatus();
        $comment->setStatus($blocked);
        $em = $this->getDoctrine()->getManager();
        //presist the comment and flush
        $em->persist($comment);
        $em->flush();
        if ($blocked) {
            $this->addFlash('status', 'Comment is banned. You can un-ban the comment by clicking on the ban icon again.');
        } else {
            $this->addFlash('status', 'Comment is unbanned.');
        }

        return $this->redirect($this->generateUrl('zikulaezcommentsmodule_admin_index'));
    }

    /**
     * @Route("/commentstats")
     * @Theme("admin")
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
     * @Theme("admin")
     * @PermissionCheck("admin")
     *
     * display all comments for a module
     *
     * @author Mark West
     * @return string html output
     */
    public function modulestatsAction(Request $request)
    {
        $counts = [];
        $counts['modules'] = $this->repository->countComments('modname', '', true);
        $counts['totalComments'] = $this->repository->countComments('modname');
        $counts['users'] = $this->repository->countComments('ownerid', '', true);
        $counts['lastPost'] = $this->repository->getLatestPost();
        $counts['firstPost'] = $this->repository->getEarliestPost();
        $counts['mostActive'] = $this->repository->mostActivePosters();
        $counts['postRate'] = $this->repository->findPostRate();

        return $this->render('@ZikulaEZCommentsModule/Admin/ezcomments_modulestats.html.twig', [
            'counts' => $counts]);
    }

    /**
     * @Route("/deletemodule")
     * @Theme("admin")
     * @PermissionCheck("admin")
     *
     * delete all comments attached to a module
     *
     * @param  modname the name of the module to delete all comments for
     * @return bool true on sucess, false on failure
     */
    public function deletemoduleAction(Request $request, $moduleName)
    {
    }
}
