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
use Zikula\EZCommentsModule\Block\Form\MostCommentsBlockType;

class MostCommentsBlock extends AbstractBlockHandler
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

        $activePosters = $ezCommentsRepository->mostActivePosters($properties['numcommenters']);

        return $this->renderView("@ZikulaEZCommentsModule\Block\list_most_commenters.html.twig",
            [ 'activeposters' => $activePosters,
                'showcount' => $properties['showcount'] == 'yes']);
    }

    public function getFormClassName(){
        return MostCommentsBlockType::class;
    }

    public function getFormTemplate()
    {
        return '@ZikulaEZCommentsModule/Block/most_comments.html.twig';
    }

    public function getDefaults(){
        return [
            'numcommenters' => 5,
            'showcount' => 'yes'
        ];
    }
}