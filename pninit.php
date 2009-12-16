<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

/**
 * initialise the EZComments module
 *
 * This function initializes the module to be used. it creates tables,
 * registers hooks,...
 *
 * @return boolean true on success, false otherwise.
 */
function EZComments_init()
{
    $dom = ZLanguage::getModuleDomain('EZComments');

    // create main table
    if (!DBUtil::createTable('EZComments')) {
        return false;
    }

    // register Hook
    if (!pnModRegisterHook('item', 'display', 'GUI', 'EZComments', 'user', 'view')) {
        return LogUtil::registerError(__('Error creating hook.', $dom));
    }

    // register  delete Hook (Timo)
    // TODO: Check the Hook's name!
    if (!pnModRegisterHook('item', 'delete', 'API', 'EZComments', 'admin', 'deletebyitem')) {
        return LogUtil::registerError(__('Error creating hook.', $dom));
    }

    // register the module delete hook
    if (!pnModRegisterHook('module', 'remove', 'API', 'EZComments', 'admin', 'deletemodule')) {
        return LogUtil::registerError(__('Error creating hook.', $dom));
    }

    pnModSetVar('EZComments', 'MailToAdmin', false);
    pnModSetVar('EZComments', 'migrated', serialize(array('dummy')));
    pnModSetVar('EZComments', 'template', 'Standard');
    pnModSetVar('EZComments', 'itemsperpage', 25);
    pnModSetVar('EZComments', 'anonusersinfo', false);
    pnModSetVar('EZComments', 'moderation', 0);
    pnModSetVar('EZComments', 'modlist', '');
    pnModSetVar('EZComments', 'blacklist', '');
    pnModSetVar('EZComments', 'modlinkcount', 2);
    pnModSetVar('EZComments', 'blacklinkcount', 5);
    pnModSetVar('EZComments', 'moderationmail', false);
    pnModSetVar('EZComments', 'alwaysmoderate', false);
    pnModSetVar('EZComments', 'proxyblacklist', false);
    pnModSetVar('EZComments', 'logip', false);
    pnModSetVar('EZComments', 'dontmoderateifcommented', false);
    pnModSetVar('EZComments', 'feedtype', 'rss');
    pnModSetVar('EZComments', 'feedcount', '10');
    pnModSetVar('EZComments', 'enablepager', false);
    pnModSetVar('EZComments', 'commentsperpage', '25');
    pnModSetVar('EZComments', 'akismet', false);
    pnModSetVar('EZComments', 'apikey', '');
    pnModSetVar('EZComments', 'anonusersrequirename', false);
    pnModSetVar('EZComments', 'modifyowntime', '6');

    // Initialisation successful
    return true;
}

/**
 * upgrade the EZComments module from an old version
 *
 * This function upgrades the module to be used. It updates tables,
 * registers hooks,...
 *
 * @return boolean true on success, false otherwise.
 */
function EZComments_upgrade($oldversion)
{
    switch ($oldversion)
    {
        case '1.2':
            pnModSetVar('EZComments', 'enablepager', false);
            pnModSetVar('EZComments', 'commentsperpage', '25');

        case '1.3':
            pnModSetVar('EZComments', 'blacklinkcount', 5);
            pnModSetVar('EZComments', 'akismet', false);
            pnModSetVar('EZComments', 'apikey', '');

        case '1.4':
            pnModSetVar('EZComments', 'anonusersrequirename', false);
            pnModDelVar('EZComments', 'apikey');
            pnMoDSetVar('EZComments', 'akismetstatus', 1);

        case '1.5':
            DBUtil::changeTable('EZComments');
            pnModSetVar('EZComments', 'template', 'Standard');
            pnModSetVar('EZComments', 'modifyowntime', '6');
    }

    return true;
}

/**
 * delete the EZComments module from an old version
 *
 * This function deletes the module to be used. It deletes tables,
 * registers hooks,...
 *
 * @return boolean true on success, false otherwise.
 */
function EZComments_delete()
{
    $dom = ZLanguage::getModuleDomain('EZComments');

    if (!pnModUnregisterHook('item', 'display', 'GUI', 'EZComments', 'user', 'view')) {
        return LogUtil::registerError(__('Error deleting hook.', $dom));
    }

    if (!pnModUnregisterHook('item', 'delete', 'API', 'EZComments', 'admin', 'deletebyitem')) {
        return LogUtil::registerError(__('Error deleting hook.', $dom));
    }

    if (!pnModUnregisterHook('module', 'remove', 'API', 'EZComments', 'admin', 'deletemodule')) {
        return LogUtil::registerError(__('Error deleting hook.', $dom));
    }

    // drop main table
    if (!DBUtil::dropTable('EZComments')) {
        return false;
    }

    // delete all module vars for the ezcomments module
    pnModDelVar('EZComments');

    // Deletion successful
    return true;
}
