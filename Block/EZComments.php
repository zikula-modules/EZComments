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

class EZComments extends AbstractBlockHandler
{

    /**
     * display block
     *
     * @param array       $blockinfo     a blockinfo structure
     * @return output      the rendered bock
     */
    public function display(array $properties)
    {
        /*// Security check
        if (!SecurityUtil::checkPermission('EZComments:EZCommentsblock:', "$blockinfo[bid]::", ACCESS_READ)) {
            return false;
        }

        if (!ModUtil::load('EZComments')) {
            return false;
        }

        // Get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Defaults
        if (!isset($vars['numentries'])) {
            $vars['numentries'] = 5;
        }

        if (!isset($vars['numdays'])) {
            $vars['numdays'] = 0;
        }

        if (!isset($vars['showdate'])) {
            $vars['showdate'] = 0;
        }

        if (!isset($vars['showusername'])) {
            $vars['showusername'] = 0;
        }

        if (!isset($vars['linkusername'])) {
            $vars['linkusername'] = 0;
        }

        $options = array('numitems' => $vars['numentries']);

        if (isset($vars['mod']) && $vars['mod'] != '*') {
            $options['mod'] = $vars['mod'];
        }

        if (!isset($vars['showpending']) || $vars['showpending'] == 0) {
            // don't show pending comments
            $options['status'] = 0;
        }

        // filter comments posted in last number of days
        if ($vars['numdays'] > 0) {
            // date for filtering in format: yyyy-mm-dd hh:mm:ss
            $options['addwhere'] = "date>='".DateUtil::getDatetime_NextDay(-$vars['numdays'])."'";
        }

        // get the comments
        $items = ModUtil::apiFunc('EZComments', 'user', 'getall', $options);

        // augment the info
        $comments = ModUtil::apiFunc('EZComments', 'user', 'prepareCommentsForDisplay', $items);

        $this->view->assign($vars);
        $this->view->assign('comments', $comments);

        // Populate block info and pass to theme
        $blockinfo['content'] = $this->view->fetch('ezcomments_block_ezcomments.tpl');

        return BlockUtil::themesideblock($blockinfo);*/
        return "EZComments";
    }

    public function getFormClassName() {
        return null;
    }
}