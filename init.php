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
    if (!ModUtil::registerHook('item', 'display', 'GUI', 'EZComments', 'user', 'view')) {
        return LogUtil::registerError(__('Error creating hook.', $dom));
    }

    // register  delete Hook (Timo)
    // TODO: Check the Hook's name!
    if (!ModUtil::registerHook('item', 'delete', 'API', 'EZComments', 'admin', 'deletebyitem')) {
        return LogUtil::registerError(__('Error creating hook.', $dom));
    }

    // register the module delete hook
    if (!ModUtil::registerHook('module', 'remove', 'API', 'EZComments', 'admin', 'deletemodule')) {
        return LogUtil::registerError(__('Error creating hook.', $dom));
    }

    // Misc
    ModUtil::setVar('EZComments', 'template', 'Standard');
    ModUtil::setVar('EZComments', 'css', 'style.css');
    ModUtil::setVar('EZComments', 'anonusersinfo', false);
    ModUtil::setVar('EZComments', 'anonusersrequirename', false);
    ModUtil::setVar('EZComments', 'logip', false);
    ModUtil::setVar('EZComments', 'itemsperpage', 25);
    ModUtil::setVar('EZComments', 'enablepager', false);
    ModUtil::setVar('EZComments', 'commentsperpage', 25);
    ModUtil::setVar('EZComments', 'migrated', array('dummy' => true));
    ModUtil::setVar('EZComments', 'useaccountpage', '1');
    // Notification
    ModUtil::setVar('EZComments', 'MailToAdmin', false);
    ModUtil::setVar('EZComments', 'moderationmail', false);
    // Moderation
    ModUtil::setVar('EZComments', 'moderation', 0);
    ModUtil::setVar('EZComments', 'alwaysmoderate', false);
    ModUtil::setVar('EZComments', 'dontmoderateifcommented', false);
    ModUtil::setVar('EZComments', 'modlinkcount', 2);
    ModUtil::setVar('EZComments', 'modlist', '');
    // Blacklisting
    ModUtil::setVar('EZComments', 'blacklinkcount', 5);
    ModUtil::setVar('EZComments', 'blacklist', '');
    ModUtil::setVar('EZComments', 'proxyblacklist', false);
    ModUtil::setVar('EZComments', 'modifyowntime', 6);
    // Akismet
    ModUtil::setVar('EZComments', 'akismet', false);
    ModUtil::setVar('EZComments', 'akismetstatus', 1);
    // Feeds
    ModUtil::setVar('EZComments', 'feedtype', 'rss');
    ModUtil::setVar('EZComments', 'feedcount', 10);

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
    $dom = ZLanguage::getModuleDomain('EZComments');

    if (!DBUtil::changeTable('EZComments')) {
        return LogUtil::registerError(__('Error updating the table.', $dom));
    }

    switch ($oldversion)
    {
        case '1.2':
            ModUtil::setVar('EZComments', 'enablepager', false);
            ModUtil::setVar('EZComments', 'commentsperpage', '25');

        case '1.3':
            ModUtil::setVar('EZComments', 'blacklinkcount', 5);
            ModUtil::setVar('EZComments', 'akismet', false);

        case '1.4':
            ModUtil::setVar('EZComments', 'anonusersrequirename', false);
            ModUtil::setVar('EZComments', 'akismetstatus', 1);

        case '1.5':
            if (!DBUtil::changeTable('EZComments')) {
                return '1.5';
            }
            ModUtil::setVar('EZComments', 'template', 'Standard');
            ModUtil::setVar('EZComments', 'modifyowntime', 6);
            ModUtil::setVar('EZComments', 'useaccountpage', '1');

        case '1.6':
        case '1.61':
        case '1.62':
            ModUtil::setVar('EZComments', 'migrated', array('dummy' => true));
            ModUtil::setVar('EZComments', 'css', 'style.css');

        case '2.0.0':
        case '2.1.0':
            // future upgrade routines
            break;
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

    if (!ModUtil::unregisterHook('item', 'display', 'GUI', 'EZComments', 'user', 'view')) {
        return LogUtil::registerError(__('Error deleting hook.', $dom));
    }

    if (!ModUtil::unregisterHook('item', 'delete', 'API', 'EZComments', 'admin', 'deletebyitem')) {
        return LogUtil::registerError(__('Error deleting hook.', $dom));
    }

    if (!ModUtil::unregisterHook('module', 'remove', 'API', 'EZComments', 'admin', 'deletemodule')) {
        return LogUtil::registerError(__('Error deleting hook.', $dom));
    }

    // drop main table
    if (!DBUtil::dropTable('EZComments')) {
        return false;
    }

    // delete all module vars for the ezcomments module
    ModUtil::delVar('EZComments');

    // Deletion successful
    return true;
}
