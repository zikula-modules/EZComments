<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
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
                        'minversion' => '1.0', 'maxversion' => '',
                        'status' => ModUtil::DEPENDENCY_RECOMMENDED)
        );
        return $meta;
    }

    protected function setupHookBundles()
    {
        $bundle = new Zikula_HookManager_ProviderBundle($this->name, 'provider_area.ui.ezcomments.comments', 'ui', __('EZComments Comment Hooks'));
        $bundle->addHook('hookhandler.ezcomments.ui.view', 'ui.view', 'EZComments_HookHandlers', 'ui_view', 'ezcomments.hooks');
        $bundle->addHook('hookhandler.ezcomments.process.delete', 'process.delete', 'EZComments_HookHandlers', 'process_delete', 'ezcomments.hooks');
        $this->registerHookProviderBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber_area.ui.ezcomments.comments', 'ui', __('EZComments Comment Hooks'));
        $bundle->addType('ui.view', 'ezcomments.hook.comments.ui.view');
        $bundle->addType('ui.edit', 'ezcomments.hook.comments.ui.edit');
        $bundle->addType('validate.edit', 'ezcomments.hook.comments.validate.edit');
        $bundle->addType('validate.delete', 'ezcomments.hook.comments.validate.delete');
        $bundle->addType('process.edit', 'ezcomments.hook.comments.process.edit');
        $bundle->addType('process.delete', 'ezcomments.hook.comments.process.delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber_area.filter.ezcomments.comments', 'filter', $this->__('EZComment Comments Filter'));
        $bundle->addType('ui.filter', 'ezcomments.hook.filter.comments');
        $this->registerHookSubscriberBundle($bundle);
    }
}
