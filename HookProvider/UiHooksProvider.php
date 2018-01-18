<?php

namespace Zikula\EZCommentsModule\HookProvider;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Bundle\HookBundle\FormAwareHook\FormAwareHook;
use Zikula\Bundle\HookBundle\Hook\DisplayHook;
use Zikula\Bundle\HookBundle\Hook\DisplayHookResponse;
use Zikula\Bundle\HookBundle\Hook\ProcessHook;
use Zikula\Bundle\HookBundle\HookProviderInterface;
use Zikula\Bundle\HookBundle\ServiceIdTrait;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use Symfony\Component\Templating\EngineInterface;
use Zikula\Core\UrlInterface;
use Zikula\UsersModule\Api\CurrentUserApi;


/**
 * Copyright 2017 Timothy Paustian
 *
 * @license MIT
 *
 */


class UiHooksProvider  implements HookProviderInterface
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
     * @var RequestStack
     */
    private $requestStack;



    /**
     * ProviderHandler constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(TranslatorInterface $translator,
                                PermissionApiInterface $permissionApi,
                                EngineInterface $templating,
                                EntityManager $entityManager,
                                RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->permissionApi = $permissionApi;
        $this->templating = $templating;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function getOwner()
    {
        return 'ZikulaEZCommentsModule';
    }

    public function getTitle()
    {
        return $this->translator->__('EZComments Display Provider');
    }

    public function getCategory()
    {
        return UiHooksCategory::NAME;
    }

    public function getProviderTypes()
    {
        return [
            UiHooksCategory::TYPE_DISPLAY_VIEW => 'uiView',
            UiHooksCategory::TYPE_FORM_EDIT => 'commentEdit',
            UiHooksCategory::TYPE_FORM_DELETE => 'commentDelete',
            UiHooksCategory::TYPE_PROCESS_DELETE => 'processDelete',
            UiHooksCategory::TYPE_PROCESS_EDIT => 'processEdit'
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
        if (!$this->permissionApi->hasPermission('EZComments::', "$mod:$id:", ACCESS_COMMENT)) {
            return;
        }
        $is_admin = $this->permissionApi->hasPermission('EZComments::', '::', ACCESS_ADMIN);
        $url = $hook->getUrl()->getRoute();

        $repo = $this->entityManager->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
        //get the comments that correspond to this object, but only the parent ones (replyTo set to 0)
        //child comments will be retrieved when the users opens the arrow
        $items = $repo->findBy(['modname' => $mod, 'objectid' => $id, 'replyto'=> 0]);
        //$items = $repo->getComments($mod, $id);

        $content = $this->templating->render('ZikulaEZCommentsModule:Hook:ezcomments_hook_uiview.html.twig',
            ['items' => $items,
              'isAdmin' =>  $is_admin,
                'artId' => $id,
                'module' => $mod,
                'areaId' => $areaID,
                'retUrl' => $url,
                ]);

        $response = new DisplayHookResponse($this->getServiceId(), $content);
        $hook->setResponse($response);
    }

    public function commentEdit(FormAwareHook $hook)
    {

        $this->requestStack->getMasterRequest()->getSession()->getFlashBag()->add('success', 'Ui hook comment processed!');
    }

    public function processDelete(ProcessHook $hook)
    {

        $this->requestStack->getMasterRequest()->getSession()->getFlashBag()->add('success', 'Ui hook delete properly processed!');
    }

    public function commentDelete(ProcessHook $hook)
    {

        $this->requestStack->getMasterRequest()->getSession()->getFlashBag()->add('success', 'Ui hook delete properly processed!');
    }

    public function processEdit(ProcessHook $hook)
    {
        $this->requestStack->getMasterRequest()->getSession()->getFlashBag()->add('success', 'Ui hook edit properly processed!');
    }
}
