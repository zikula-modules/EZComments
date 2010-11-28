<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id: ezcomments.php 662 2010-02-04 11:53:04Z herr.vorragend $
 * @license See license.txt
 */

/**
 * initialise block
 */
function EZComments_mostcommentsblock_init()
{ 
    // Security
    SecurityUtil::registerPermissionSchema('EZComments:mostommentsblock:', 'Block ID::');
} 

/**
 * get information on block
 * 
 * @return array       The block information
 */
function EZComments_mostcommentsblock_info()
{ 
    $dom = ZLanguage::getModuleDomain('EZComments');

    return array('module'          => 'EZComments',
                 'text_type'       => __('mostomments', $dom),
                 'text_type_long'  => __('Show content with most comments', $dom),
                 'allow_multiple'  => true,
                 'form_content'    => false,
                 'form_refresh'    => false,
                 'show_preview'    => true,
                 'admin_tableless' => true);

} 

/**
 * display block
 * 
 * @param array       $blockinfo     a blockinfo structure
 * @return output      the rendered bock
 */
function EZComments_mostcommentsblock_display($blockinfo)
{ 
    // Security check
    if (!SecurityUtil::checkPermission('EZComments:mostommentsblock:', "$blockinfo[bid]::", ACCESS_READ)) {
        return false;
    } 

    if (!pnModLoad('EZComments')) {
        return false;
    }

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

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
    $items = EZComments_mostcommentsblock_getall($options);

    // augment the info
    $comments = EZComments_prepareCommentsForDisplay($items);
    
    $renderer = & pnRender::getInstance('EZComments');

    $renderer->assign($vars);
    $renderer->assign('comments', $comments); 

    // Populate block info and pass to theme
    $blockinfo['content'] = $renderer->fetch('ezcomments_mostcommentsblock_ezcomments.htm');

    return themesideblock($blockinfo);
} 

function EZComments_mostcommentsblock_getall($args = array())
{
    if (!isset($args['numitems']) || !is_numeric($args['numitems'])) {
        $args['numitems'] = -1;
    }

    // Security check
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_OVERVIEW)) {
        return array();
    }

    // Get database setup
    $pntable = pnDBGetTables();
    $table = $pntable['EZComments'];
    $columns = &$pntable['EZComments_column'];

    // form where clause
    $whereclause = array();

    if (isset($args['mod'])) {
        $whereclause[] = "$columns[modname] = '" . DataUtil::formatForStore($args['mod']) . "'";
    }

    // create where clause
    $where = '';
    if (!empty($whereclause)) {
        $where = implode(' AND ', $whereclause).' and ';
    }

    $permFilter[] = array('component_left'   => 'EZComments',
                          'component_middle' => '',
                          'component_right'  => '',
                          'instance_left'    => 'modname',
                          'instance_middle'  => 'objectid',
                          'instance_right'   => 'id',
                          'level'            => ACCESS_READ);

    $cols = DBUtil::_getAllColumns('EZComments'); // FIXME: don't need all
    $ca = DBUtil::getColumnsArray('EZComments');
    $ca[] = "count";
    $sql = "
SELECT DISTINCT
       $cols,
       count(*) as count
FROM $table
where $where $columns[status] = 0
group by $columns[modname],$columns[objectid]
order by count desc
";

    $dbresult = DBUtil::executeSQL($sql, 0, $args['numitems']);

    $items = DBUtil::marshallObjects($dbresult, $ca);

    // backwards compatibilty: modname -> mod
    foreach (array_keys($items) as $k) {
        $items[$k]['mod'] = $items[$k]['modname'];
    }

    // return the items
    return $items;
}

/**
 * modify block settings
 * 
 * @param array $blockinfo a blockinfo structure
 * @return output the bock form
 */
function EZComments_mostcommentsblock_modify($blockinfo)
{
    if (!SecurityUtil::checkPermission('EZComments:mostommentsblock:', "$blockinfo[bid]::", ACCESS_ADMIN)) {
        return false;
    } 

    $dom = ZLanguage::getModuleDomain('EZComments');

    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

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

    // get all modules with EZComments active
    $usermods = pnModAPIFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname'=> 'EZComments'));

    // Create output object
    $renderer = & pnRender::getInstance('EZComments', false);

    // assign the block vars
    $renderer->assign($vars);

    $renderer->assign('usermods', array_keys($usermods));
    
    // Return the output that has been generated by this function
    return $renderer->fetch('ezcomments_mostcommentsblock_ezcomments_modify.htm');
} 

/**
 * update block settings
 * 
 * @param array       $blockinfo     a blockinfo structure
 * @return $blockinfo  the modified blockinfo structure
 */
function EZComments_mostcommentsblock_update($blockinfo)
{
    $dom = ZLanguage::getModuleDomain('EZComments');

    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // alter the corresponding variable
    $vars['mod']          = (string)FormUtil::getPassedValue('mod', '', 'POST');
    $vars['numentries']   =    (int)FormUtil::getPassedValue('numentries', 5, 'POST');
    $vars['showcount'] =   (bool)FormUtil::getPassedValue('showcount', false, 'POST');

    // write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars); 

    // clear the block cache
    $renderer = & pnRender::getInstance('EZComments');
    $renderer->clear_cache('ezcomments_mostcommentsblock_ezcomments.htm');

    return $blockinfo;
} 
