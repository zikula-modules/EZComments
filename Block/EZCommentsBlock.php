<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */
namespace Zikula\EZCommentsModule\Block;

use Zikula\BlocksModule\AbstractBlockHandler;
use Zikula\EZCommentsModule\Block\Form\EZCommentsBlockType;

class EZCommentsBlock extends AbstractBlockHandler
{
    /**
     * display block
     *
     * @param array       $blockinfo     a blockinfo structure
     * @return output      the rendered bock
     */
    public function display(array $properties)
    {
        if (!$this->hasPermission('ZikulaEZComments:EZCommentsBlock:', $properties['bid'] . '::', ACCESS_OVERVIEW)) {
            return '';
        }

        $currentUserApi = $this->get('zikula_users_module.current_user');
        if (!$currentUserApi->isLoggedIn()) {
            return '';
        }

        // set default values for all params which are not properly set
        $defaults = $this->getDefaults();
        $properties = array_merge($defaults, $properties);

        $ezCommentsRepository = $this->get('zikula_ezcomments_module.ezcomments_module_repository');

        //return the desired items based upon how the properties have been set
        $items = $ezCommentsRepository->getLatestComments($properties);

        return $this->renderView("@ZikulaEZCommentsModule\Block\list_latest_comments.html.twig",
                    [ 'items' => $items,
                    'days' => $properties['numdays'],
                    'showdate' => $properties['showdate'] == 'yes',
                    'showuser' => $properties['showuser'] == 'yes',
                    'linksuer' => $properties['linkuser'] == 'yes']);
    }

    public function getFormClassName(){
        return EZCommentsBlockType::class;
    }

    public function getFormTemplate()
    {
        return '@ZikulaEZCommentsModule/Block/options_modify.html.twig';
    }

    public function getDefaults(){
        return [
                'numcomments' => 5,
                'numdays' => 14,
                'showdate' => 'yes',
                'showuser' => 'yes',
                'linkuser' => 'no'
        ];
    }

}