<?php

declare(strict_types=1);

namespace Zikula\EZCommentsModule\HookProvider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Bundle\HookBundle\Hook\DisplayHook;
use Zikula\Bundle\HookBundle\Hook\DisplayHookResponse;
use Zikula\Bundle\HookBundle\HookProviderInterface;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;

/**
 * Copyright 2017 Timothy Paustian
 *
 * @license MIT
 */

class CountCommentsUiHooksProvider implements HookProviderInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var PermissionApiInterface
     */
    private $permissionApi;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(
        TranslatorInterface $translator,
        PermissionApiInterface $permissionApi,
        Environment $twig,
        EntityManagerInterface $entityManager
    ) {
        $this->translator = $translator;
        $this->permissionApi = $permissionApi;
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    public function getOwner(): string
    {
        return 'ZikulaEZCommentsModule';
    }

    public function getTitle(): string
    {
        return $this->translator->trans('EZComments Count Provider');
    }

    public function getCategory(): string
    {
        return UiHooksCategory::NAME;
    }

    public function getProviderTypes(): array
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
    public function uiView(DisplayHook $hook): void
    {
        $mod = $hook->getCaller();
        $id = $hook->getId();
        // Security checks
        // first check if the user is allowed to do any comments for this module/objectid
        if (!$this->permissionApi->hasPermission('EZComments::', "${mod}:${id}:", ACCESS_READ)) {
            return;
        }

        $repo = $this->entityManager->getRepository(EZCommentsEntity::class);
        $commentCount = $repo->createQueryBuilder('a')
                ->select('count(a.objectid)')
                ->where('a.status = 0')
                ->andWhere('a.objectid = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleScalarResult();
        $content = $this->twig->render(
            '@ZikulaEZCommentsModule/Hook/ezcomments_hook_comment_counts.html.twig',
            ['count' => $commentCount
            ]
        );
        $response = new DisplayHookResponse($this->getAreaName(), $content);
        $hook->setResponse($response);
    }

    public function getAreaName(): string
    {
        return 'provider.zikulaezcommentsmodule.ui_hooks.countcomments';
    }
}
