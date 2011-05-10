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
        $bundle = new Zikula_HookManager_ProviderBundle($this->name, 'modulehook_area.ezcomments.comments', 'ui', __('EZComments Comment Hooks'));
        $bundle->addHook('hookhandler.ezcomments.ui.view', 'ui.view', 'EZComments_HookHandlers', 'ui_view', 'ezcomments.hooks');
        $bundle->addHook('hookhandler.ezcomments.process.delete', 'process.delete', 'EZComments_HookHandlers', 'process_delete', 'ezcomments.hooks');
        $this->registerHookProviderBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'modulehook_area.ezcomments.commentsfilter', 'filter', $this->__('EZComment Comments Filter'));
        $bundle->addType('ui.filter', 'ezcomments.hook.commentsfilter');
        $this->registerHookSubscriberBundle($bundle);
    }
}
