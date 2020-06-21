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
     * @Route("")
     * @Theme("admin")
     * @PermissionCheck("admin")
     */
    public function indexAction(Request $request): Response
    {
        $items = $this->repository->findAll();

        return $this->render('@ZikulaEZCommentsModule/Admin/ezcomments_index.html.twig', [
            'items' => $items]);
    }

    /**
     * @Route("/edit", options={"expose"=true}, methods={"POST"})
     * @Theme("admin")
     * @param request
     */
    public function editAction(Request $request): JsonResponse
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
     */
    public function deleteAction(Request $request): JsonResponse
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
     */
    public function deleteallAction(Request $request, EZCommentsEntity $comment)
    {
    }

    /**
     * @Route("/blockuser/{comment}")
     * @Theme("admin")
     * block users that are being annoying
     */
    public function blockuserAction(EZCommentsEntity $comment): Response
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
     * block comment that is annoying
     */
    public function blockCommentAction(EZCommentsEntity $comment): Response
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
     */
    public function modulestatsAction(): Response
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
     */
    public function deletemoduleAction(Request $request, $moduleName)
    {
    }
}
