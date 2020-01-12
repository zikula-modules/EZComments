<?php

namespace Zikula\EZCommentsModule\HookProvider;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Bundle\HookBundle\Hook\DisplayHook;
use Zikula\Bundle\HookBundle\Hook\DisplayHookResponse;
use Zikula\Bundle\HookBundle\Hook\ProcessHook;
use Zikula\Bundle\HookBundle\HookProviderInterface;
use Zikula\Bundle\HookBundle\ServiceIdTrait;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Zikula\UsersModule\Api\CurrentUserApi;


/**
 * Copyright 2017 Timothy Paustian
 *
 * @license MIT
 *
 */


class CountCommentsUiHooksProvider  implements HookProviderInterface
{
    use ServiceIdTrait;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var PermissionApiInterface
     */
    private $permissionApi;
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * UiHooksProvider constructor.
     * @param TranslatorInterface $translator
     * @param PermissionApiInterface $permissionApi
     * @param EntityManager $entityManager
     */
    public function __construct(TranslatorInterface $translator,
                                PermissionApiInterface $permissionApi,
                                EngineInterface $templating,
                                EntityManager $entityManager)
    {
        $this->translator = $translator;
        $this->permissionApi = $permissionApi;
        $this->templating = $templating;
        $this->entityManager = $entityManager;
    }

    public function getOwner()
    {
        return 'ZikulaEZCommentsModule';
    }

    public function getTitle()
    {
        return $this->translator->__('EZComments Count Provider');
    }

    public function getCategory()
    {
        return UiHooksCategory::NAME;
    }

    public function getProviderTypes()
    {
        return [
            UiHooksCategory::TYPE_DISPLAY_VIEW => 'uiView'
        ];
    }

    /**
     * @param DisplayHook $hook
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */

    public function uiView(DisplayHook $hook)
    {

        $mod = $hook->getCaller();
        $id = $hook->getId();
        // Security checks
        // first check if the user is allowed to do any comments for this module/objectid
        if (!$this->permissionApi->hasPermission('EZComments::', "$mod:$id:", ACCESS_READ)) {
            return;
        }

        $repo = $this->entityManager->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
        $commentCount = $repo->createQueryBuilder('a')
                ->select('count(a.objectid)')
                ->where('a.status = 0')
                ->andWhere('a.objectid = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleScalarResult();
        $content = $this->templating->render('ZikulaEZCommentsModule:Hook:ezcomments_hook_comment_counts.html.twig',
            ['count' => $commentCount
            ]);
        $response = new DisplayHookResponse($this->getServiceId(), $content);
        $hook->setResponse($response);
    }
}
