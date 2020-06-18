<?php

namespace Zikula\EZCommentsModule\HookProvider;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Bundle\HookBundle\Hook\DisplayHook;
use Zikula\Bundle\HookBundle\Hook\DisplayHookResponse;
use Zikula\Bundle\HookBundle\HookProviderInterface;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use Symfony\Component\Routing\RouterInterface;
use Zikula\UsersModule\Api\CurrentUserApi;

/**
 * Copyright 2017 Timothy Paustian
 *
 * @license MIT
 *
 */
class UiHooksProvider  implements HookProviderInterface
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

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var VariableApiInterface
     */
    private $variableApi;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CurrentUserApi
     */
    private $currentUserApi;

    public function __construct(
        TranslatorInterface $translator,
        PermissionApiInterface $permissionApi,
        Environment $twig,
        EntityManager $entityManager,
        RequestStack $requestStack,
        RouterInterface $router,
        VariableApiInterface $variableApi,
        CurrentUserApi $currentUserApi
    ) {
        $this->translator = $translator;
        $this->permissionApi = $permissionApi;
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->variableApi = $variableApi;
        $this->router = $router;
        $this->currentUserApi = $currentUserApi;
    }

    public function getOwner(): string
    {
        return 'ZikulaEZCommentsModule';
    }

    public function getTitle(): string
    {
        return $this->translator->trans('EZComments Display Provider');
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
     * uiView - Display a view that is hook to the module.
     * @param DisplayHook $hook
     *
     */
    public function uiView(DisplayHook $hook)
    {
        $mod = $hook->getCaller();
        $id = $hook->getId();
        $areaID = $hook->getAreaId();
        // Security checks
        // first check if the user is allowed to do any comments for this module/objectid
        if (!$this->permissionApi->hasPermission('EZComments::', "$mod:$id:", ACCESS_READ)) {
            return;
        }
        $repo = $this->entityManager->getRepository(EZCommentsEntity::class);

        $is_admin = $this->permissionApi->hasPermission('EZComments::', '::', ACCESS_ADMIN);
        $url = $hook->getUrl();
        $urlString = $this->router->generate($url->getRoute(), $url->getArgs());
        //get the comments that correspond to this object, but only the parent ones (replyTo set to 0)
        //child comments will be retrieved when the users opens the arrow
        //also do not get banned comments
        $items = $repo->findBy(['modname' => $mod, 'objectid' => $id, 'replyto'=> 0, 'status' => 0]);

        //walk the items and see if they have replies
        foreach($items as $item){
            $replies = $repo->findOneBy(['modname' => $mod, 'objectid' => $id, 'replyto'=> $item->getId(), 'status' => 0]);
            if($replies){
                //this marks it as having replies.
                $item->setAreaid(1);
            }
        }
        $loggedin = $this->currentUserApi->isLoggedIn();
        //if we are logged in or allowanon is true then add the comment button
        $doAnon = $this->variableApi->get('ZikulaEZCommentsModule', 'allowanon') || $loggedin;

        $content = $this->twig->render('@ZikulaEZCommentsModule/Hook/ezcomments_hook_uiview.html.twig',
            ['items' => $items,
                'isAdmin' =>  $is_admin,
                'artId' => $id,
                'module' => $mod,
                'areaId' => $areaID,
                'retUrl' => $urlString,
                'doAnon' => $doAnon
            ]);

        $response = new DisplayHookResponse($this->getAreaName(), $content);
        $hook->setResponse($response);
    }

    public function getAreaName(): string
    {
        return 'provider.zikulaezcommentsmodule.ui_hooks.ezcomments';
    }

}
