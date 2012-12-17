<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

class EZComments_Version extends Zikula_AbstractVersion
{

    public function getMetaData()
    {
        $meta = array();
        // Information for the modules admin
        $meta['displayname'] = $this->__('Comments');
        $meta['description'] = $this->__('Attach comments to every kind of content using hooks');
        //! module url in lowercase and different to displayname
        $meta['url'] = $this->__('comments');
        $meta['version'] = '3.0.1';
        $meta['core_min'] = '1.3.0';
        $meta['core_max'] = '1.3.99'; // Fixed to 1.3.x range
        $meta['securityschema'] = array(
                'EZComments::' => 'Module:Item ID:Comment ID',
                'EZComments::trackback' => 'Module:Item ID:',
                'EZComments::pingback' => 'Module:Item ID:'
        );

        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::PROVIDER_CAPABLE] = array('enabled' => true);
        $meta['capabilities'][HookUtil::SUBSCRIBER_CAPABLE] = array('enabled' => true);

        // recommended and required modules
        $meta['dependencies'] = array(
                array('modname'    => 'Akismet',
                        'minversion' => '2.0', 'maxversion' => '',
                        'status' => ModUtil::DEPENDENCY_RECOMMENDED)
        );
        return $meta;
    }

    protected function setupHookBundles()
    {
        $bundle = new Zikula_HookManager_ProviderBundle($this->name, 'provider.ezcomments.ui_hooks.comments', 'ui_hooks', $this->__('EZComments Comment Hooks'));
        $bundle->addServiceHandler('display_view', 'EZComments_HookHandlers', 'uiView', 'ezcomments.hooks.comments');
        $bundle->addServiceHandler('process_edit', 'EZComments_HookHandlers', 'processEdit', 'ezcomments.hooks.comments');
        $bundle->addServiceHandler('process_delete', 'EZComments_HookHandlers', 'processDelete', 'ezcomments.hooks.comments');
        $this->registerHookProviderBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.ezcomments.ui_hooks.comments', 'ui_hooks', $this->__('EZComments Comment Hooks'));
        $bundle->addEvent('display_view', 'ezcomments.ui_hooks.comments.display_view');
        $bundle->addEvent('form_edit', 'ezcomments.ui_hooks.comments.form_edit');
        $bundle->addEvent('form_delete', 'ezcomments.ui_hooks.comments.form_delete');
        $bundle->addEvent('validate_edit', 'ezcomments.ui_hooks.comments.validate_edit');
        $bundle->addEvent('validate_delete', 'ezcomments.ui_hooks.comments.validate_delete');
        $bundle->addEvent('process_edit', 'ezcomments.ui_hooks.comments.process_edit');
        $bundle->addEvent('process_delete', 'ezcomments.ui_hooks.comments.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.ezcomments.filter_hooks.comments', 'filter_hooks', $this->__('EZComment Comments Filter'));
        $bundle->addEvent('filter', 'ezcomments.filter_hooks.comments.filter');
        $this->registerHookSubscriberBundle($bundle);
    }
}
