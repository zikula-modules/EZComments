<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @license See license.txt
 */

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
    $securityCheck = ModUtil::apiFunc('EZComments', 'user', 'checkPermission',
                                  array('module'    => '',
                                        'objectid'  => '',
                                        'commentid' => $id,
                                        'level'     => ACCESS_EDIT));
    if (!$securityCheck) {
        $redirect = base64_decode(FormUtil::getPassedValue('redirect'));
        if (!isset($redirect)) {
            $redirect = System::getHomepageUrl();
        }
        return LogUtil::registerPermissionError($redirect);
    }

    // load edithandler class from file
    $handler = "EZComments_Form_Handler_" . ucwords($type) . "_Modify";

    // Create Form output object
    $zform = FormUtil::newpnForm('EZComments');

    // Return the output that has been generated by this function
    return $zform->pnFormExecute("ezcomments_{$type}_modify.tpl", new $handler);
}
