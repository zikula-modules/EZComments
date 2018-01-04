<?php

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula Foundation - http://zikula.org/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\EZCommentsModule\Container;

use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Core\LinkContainer\LinkContainerInterface;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;

class LinkContainer implements LinkContainerInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PermissionApiInterface
     */
    private $permissionApi;

    /**
     * constructor.
     *
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param PermissionApiInterface $permissionApi
     **/
    public function __construct(
        TranslatorInterface $translator,
        RouterInterface $router,
        PermissionApiInterface $permissionApi
    )
    {
        $this->translator = $translator;
        $this->router = $router;
        $this->permissionApi = $permissionApi;
    }

    /**
     * get Links of any type for this extension
     * required by the interface
     *
     * @param string $type
     * @return array
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN)
    {
        if (LinkContainerInterface::TYPE_ADMIN == $type) {
            return $this->getAdmin();
        }
        if (LinkContainerInterface::TYPE_ACCOUNT == $type) {
            return $this->getAccount();
        }
        if (LinkContainerInterface::TYPE_USER == $type) {
            return $this->getUser();
        }

        return [];
    }

    /**
     * get the Admin links for this extension
     *
     * @return array
     */
    private function getAdmin()
    {
        $links = [];
        if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::',  ACCESS_ADMIN)) {
            $links[] = ['url' =>  $this->router->generate('zikulaezcommentsmodule_admin_modulestats'),
                        'text' => $this->translator->__('Module Stats'),
                        'icon' => 'barchart'];
            $links[] = ['url' =>  $this->router->generate('zikulaezcommentsmodule_comment_getreplies'),
                'text' => $this->translator->__('Module Stats'),
                'icon' => 'barchart'];
            /*$links[] = ['url' => $this->router->generate('ezcomments_admin_modifyconfig'),
                'text' => $this->translator->__('Settings'),
                'icon' => 'wrench',
                'links' => [
                    ['url' => $this->router->generate('ezcomments_admin_modifyconfig'),
                        'text' => $this->translator->__('Settings')],
                    ['url' => $this->router->generate('ezcomments_admin_cleanup'),
                        'text' => $this->translator->__('Delete orphaned comments')],
                    ['url' => $this->router->generate('ezcomments_admin_migrate'),
                        'text' => $this->translator->__('Migrate comments')],
                    ['url' => $this->router->generate('ezcomments_admin_purge'),
                        'text' => $this->translator->__('Purge comments')],
                    ['url' => $this->router->generate('ezcomments_admin_applyrules'),
                        'text' => $this->translator->__('Re-apply moderation rules')]
                ]];*/
        }
        return $links;
    }

    private function getUser()
    {
        $links = [];

        return $links;
    }

    private function getAccount()
    {
        $links = [];

        return $links;
    }

    /**
     * set the BundleName as required by the interface
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'ZikulaEZCommentsModule';
    }
}
