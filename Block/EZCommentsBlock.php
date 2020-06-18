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
use Zikula\EZCommentsModule\Entity\Repository\EZCommentsEntityRepository;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;

class EZCommentsBlock extends AbstractBlockHandler
{
    /**
     * @var CurrentUserApiInterface
     */
    private $currentUserApi;

    /**
     * @var EZCommentsEntityRepository
     */
    private $commentsRepository;

    /**
     * display block
     *
     * @param array       $blockinfo     a blockinfo structure
     */
    public function display(array $properties): string
    {
        if (!$this->hasPermission('ZikulaEZComments:EZCommentsBlock:', $properties['bid'] . '::', ACCESS_OVERVIEW)) {
            return '';
        }

        if (!$this->currentUserApi->isLoggedIn()) {
            return '';
        }

        // set default values for all params which are not properly set
        $defaults = $this->getDefaults();
        $properties = array_merge($defaults, $properties);


        //return the desired items based upon how the properties have been set
        $items = $this->commentsRepository->getLatestComments($properties);

        return $this->renderView("@ZikulaEZCommentsModule\Block\list_latest_comments.html.twig", [
            'items' => $items,
            'days' => $properties['numdays'],
            'showdate' => $properties['showdate'] == 'yes',
            'showuser' => $properties['showuser'] == 'yes',
            'linkuser' => $properties['linkuser'] == 'yes'
        ]);
    }

    public function getFormClassName(): string {
        return EZCommentsBlockType::class;
    }

    public function getFormTemplate(): string
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

    /**
     * @required
     */
    public function setCurrentUserApi(CurrentUserApiInterface $currentUserApi)
    {
        $this->currentUserApi = $currentUserApi;
    }

    /**
     * @required
     */
    public function setCommentsRepository(EZCommentsEntityRepository $commentsEntityRepository)
    {
        $this->commentsRepository = $commentsEntityRepository;
    }
}
