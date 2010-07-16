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
        $meta['version']        = '3.0.0';
        $meta['securityschema'] = array(
                'EZComments::'          => 'Module:Item ID:Comment ID',
                'EZComments::trackback' => 'Module:Item ID:',
                'EZComments::pingback'  => 'Module:Item ID:'
        );

        // recommended and required modules
        $meta['dependencies'] = array(
                array('modname'    => 'akismet',
                        'minversion' => '1.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED),
                array('modname'    => 'ContactList',
                        'minversion' => '1.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED),
                array('modname'    => 'MyProfile',
                        'minversion' => '1.2', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED),
                array('modname'    => 'InterCom',
                        'minversion' => '2.1', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED)
        );
        return $meta;
    }
}