<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @license See license.txt
 */

class EZComments_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        // Information for the modules admin
        $meta['displayname']    = $this->__('Comments');
        $meta['description']    = $this->__('Attach comments to every kind of content using hooks');
        //! module url in lowercase and different to displayname
        $meta['url']            = $this->__('comments');
        $meta['version']        = '3.0.1';
        $meta['securityschema'] = array(
                'EZComments::'          => 'Module:Item ID:Comment ID',
                'EZComments::trackback' => 'Module:Item ID:',
                'EZComments::pingback'  => 'Module:Item ID:'
        );

        $meta['capabilities'] = array(HookUtil::PROVIDER_CAPABLE => array('enabled' => true));

        // recommended and required modules
        $meta['dependencies'] = array(
                array('modname'    => 'akismet',
                        'minversion' => '1.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED)
        );
        return $meta;
    }

    protected function setupHookBundles()
    {
         $bundle = new Zikula_Version_HookProviderBundle('modulehook_area.ezcomments.comments', __('EZComments Comment Handlers'));
         $bundle->addHook('hookhandler.ezcomments.ui.view', 'ui.view', 'EZComments_HookHandlers', 'ui_view', 'ezcomments.hooks');
         $bundle->addHook('hookhandler.ezcomments.process.delete', 'process.delete', 'EZComments_HookHandlers', 'process_delete', 'ezcomments.hooks');
         $this->registerHookProviderBundle($bundle);
    }
}