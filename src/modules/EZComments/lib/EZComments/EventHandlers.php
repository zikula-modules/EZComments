<?php
/**
 * Copyright 2009 Zikula Foundation.
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * EventHandlers class.
 */
class EZComments_EventHandlers
{
    /**
     * Handle module uninstall event "installer.module.uninstalled".
     * Receives $modinfo as $args
     * 
     * @param Zikula_Event $event
     *
     * @return void
     */
    public static function moduleDelete(Zikula_Event $event)
    {
        if (!$event['modname'] == 'EZComments') {
            return;
        }

        // do stuff here...
    }
}
