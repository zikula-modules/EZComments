<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id$
 * @license See license.txt
 */

// FIXME Check where this is called and possible merge

/**
 * process multiple comments
 *
 * This function process the comments selected in the admin view page.
 * Multiple comments may have thier state changed or be deleted
 *
 * @author The EZComments Development Team
 * @param Comments   the ids of the items to be deleted
 * @param confirmation  confirmation that this item can be deleted
 * @param redirect the location to redirect to after the deletion attempt
 * @return bool true on sucess, false on failure
 */
function ezc_processSelected($args)
{
    // Get parameters from whatever input we need.
    $comments = FormUtil::getPassedValue('comments');
    $action   = FormUtil::getPassedValue('action');

    $dom = ZLanguage::getModuleDomain('EZComments');

    // get the type of function call: admin or user
    $type = FormUtil::getPassedValue('type', 'user');
    if (!in_array($type, array('user', 'admin'))) {
        $type = 'user';
    }

    // If we get here it means that the user has confirmed the action
    // Confirm authorisation code.
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('EZComments', 'admin', 'main'));
    }

    // loop round each comment deleted them in turn
    foreach ($comments as $comment)
    {
        switch (strtolower($action))
        {
            case 'delete':
                // The API function is called.
                if (pnModAPIFunc('EZComments', 'admin', 'delete', array('id' => $comment))) {
                    // Success
                    LogUtil::registerStatus(__('Done! Comment deleted.', $dom));
                }
                break;

            case 'approve':
                if (pnModAPIFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 0))) {
                    // Success
                    LogUtil::registerStatus(__('Done! Comment updated.', $dom));
                }
                break;

            case 'hold':
                if (pnModAPIFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 1))) {
                    // Success
                    LogUtil::registerStatus(__('Done! Comment updated.', $dom));
                }
                break;

            case 'reject':
                if (pnModAPIFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 2))) {
                    // Success
                    LogUtil::registerStatus(__('Done! Comment updated.', $dom));
                }
                break;
        }
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    if (isset($args['redirect']) && !empty($args['redirect'])) {
        return pnRedirect($args['redirect']);
    } else {
        return pnRedirect(pnModURL('EZComments', $type, 'main'));
    }
}

/**
 * modify a comment
 *
 * This is a standard function that is called whenever an administrator
 * wishes to modify a comment
 *
 * @author The EZComments Development Team
 * @param tid the id of the comment to be modified
 * @return string the modification page
 */
function ezc_modify($args)
{
    // get the type of function call: admin or user
    $type = FormUtil::getPassedValue('type', 'user');
    if (!in_array($type, array('user', 'admin'))) {
        $type = 'user';
    }

    // get our input
    $id = isset($args['id']) ? $args['id'] : FormUtil::getPassedValue('id', null, 'GETPOST');

    // Security check
    $securityCheck = pnModAPIFunc('EZComments', 'user', 'checkPermission',
                                  array('module'    => '',
                                        'objectid'  => '',
                                        'commentid' => $id,
                                        'level'     => ACCESS_EDIT));
    if (!$securityCheck) {
        $redirect = base64_decode(FormUtil::getPassedValue('redirect'));
        if (!isset($redirect)) {
            $redirect = pnGetHomepageURL();
        }
        return LogUtil::registerPermissionError($redirect);
    }

    // load edithandler class from file
    $class = "EZComments_{$type}_modifyhandler";
    Loader::requireOnce('modules/EZComments/pnincludes/'.strtolower($class).'.class.php');

    // Create pnForm output object
    $pnf = FormUtil::newpnForm('EZComments');

    // Return the output that has been generated by this function
    return $pnf->pnFormExecute("ezcomments_{$type}_modify.htm", new $class);
}
