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
use Zikula\EZCommentsModule\Entity\Repository\EZCommentsEntityRepository;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;

class MostCommentsBlock extends AbstractBlockHandler
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

        $activePosters = $this->commentsRepository->mostActivePosters($properties['numcommenters']);

        return $this->renderView("@ZikulaEZCommentsModule\Block\list_most_commenters.html.twig",
            [ 'activeposters' => $activePosters,
                'showcount' => $properties['showcount'] == 'yes']);
    }

    public function getFormClassName(): string {
        return MostCommentsBlockType::class;
    }

    public function getFormTemplate(): string
    {
        return '@ZikulaEZCommentsModule/Block/most_comments.html.twig';
    }

    public function getDefaults(){
        return [
            'numcommenters' => 5,
            'showcount' => 'yes'
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
