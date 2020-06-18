<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\EZCommentsModule\Menu;

use Knp\Menu\ItemInterface;
use Zikula\MenuModule\ExtensionMenu\AbstractExtensionMenu;

class ExtensionMenu extends AbstractExtensionMenu
{
    protected function getAdmin(): ?ItemInterface
    {
        $menu = $this->factory->createItem('ezCommentsAdminMenu');
        if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
            $menu->addChild('Module Stats', [
                'route' => 'zikulaezcommentsmodule_admin_modulestats',
            ])->setAttribute('icon', 'fas fa-chart-bar');
            $menu->addChild('Settings', [
                'route' => 'zikulaezcommentsmodule_config_config',
            ])->setAttribute('icon', 'fas fa-wrench');
        }

        return 0 === $menu->count() ? null : $menu;
    }

    public function getBundleName(): string
    {
        return 'ZikulaEZCommentsModule';
    }
}
