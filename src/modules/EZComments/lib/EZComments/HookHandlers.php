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
 * EZComments Hooks Handlers.
 */
class EZComments_HookHandlers extends Zikula_HookHandler
{
    /**
     * Display hook for view.
     *
     * Subject is the object being viewed that we're attaching to.
     * args[id] Is the id of the object.
     * args[caller] the module who notified of this event.
     *
     * @param Zikula_Event $event The hookable event.
     *
     * @return void
     */
    public function ui_view(Zikula_Event $event)
    {
        // security check - return void if not allowed.

        //$module = $event['caller'];
        //$id = $event['id'];

        // view - get from data"base - if not found, render error template or issue a logutil
        //$comment = get_comment_from_db("where id = $id AND module = $module"); // fake database call

        //$view = Zikula_View::getInstance('Comments');
        //$view->assign('comment', $comment);

        // add this response to the event stack
        //$name = 'hookhandler.comment.general.ui.view';
        //$event->data[$name] = new Zikula_Response_DisplayHook($name, $view, 'areaname_ui_view.tpl');
    }

    /**
     * Example delete process hook handler.
     *
     * The subject should be the object that was deleted.
     * args[id] Is the is of the object
     * args[caller] is the name of who notified this event.
     *
     * @param Zikula_Event $event The hookable event.
     *
     * @return void
     */
    public function process_delete(Zikula_Event $event)
    {
       
    }

}
