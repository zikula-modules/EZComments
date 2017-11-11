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

class MostComments extends AbstractBlockHandler
{

    /**
     * display block
     *
     * @param array       $blockinfo     a blockinfo structure
     * @return output      the rendered bock
     */
    public function display(array $properties)
    {
        // Security check
       /* if (!SecurityUtil::checkPermission('EZComments:MostCommentsBlock:', "$blockinfo[bid]::", ACCESS_READ)) {
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

        if (!isset($vars['showcount'])) {
            $vars['showcount'] = false;
        }

        $options = array('numitems' => $vars['numentries']);

        if (isset($vars['mod']) && $vars['mod'] != '*') {
            $options['mod'] = $vars['mod'];
        }

        // get the comments
        $items = $this->MostCommentsBlock_getall($options);

        // augment the info
        $comments = ModUtil::apiFunc('EZComments', 'user', 'prepareCommentsForDisplay', $items);

        $this->view->assign($vars);
        $this->view->assign('comments', $comments);

        // Populate block info and pass to theme
        $blockinfo['content'] = $this->view->fetch('ezcomments_block_mostcomments.tpl');

        return BlockUtil::themesideblock($blockinfo);*/
       return "No one";
    }
}