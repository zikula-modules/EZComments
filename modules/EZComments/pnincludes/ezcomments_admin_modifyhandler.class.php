<?php
// $Id: mh_admin_edithandler.class.php 161 2007-01-28 17:00:20Z landseer $
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Frank Schummertz
// Purpose of file:  edit a comment
// ----------------------------------------------------------------------

class EZComments_admin_modifyhandler
{
    var $id;
    var $objectid;
    var $redirect;
    var $user_is_anonymous;

    function initialize(&$pnRender)
    {
        $this->id       = (int)FormUtil::getPassedValue('id', -1, 'GETPOST');
        $this->objectid =      FormUtil::getPassedValue('objectid', '', 'GETPOST');
        $this->redirect =      FormUtil::getPassedValue('redirect', '', 'GETPOST');

        $pnRender->caching = false;
        $pnRender->add_core_data();
        
        $comment = pnModAPIFunc('EZComments', 'user', 'get', array('id' => $this->id));
        if ($comment == false || !is_array($comment)) {      
            return LogUtil::registerError(_NOSUCHITEM, pnModURL('EZComments', 'admin', 'main'));
        }

        // check if users has posted as anonymous, this is needed later on in handleCommand
        $this->user_is_anonymous = !empty($comment['anonname']);
        
        // Add a hidden variable for the item id.
        $pnRender->assign('id', $id);

        // Add the return to return to after modifying the item.
        $pnRender->assign('redirect', $redirect);

        // assign the status flags
        $statuslevels = array( array('text' => _EZCOMMENTS_APPROVED, 'value' => 0),
                               array('text' => _EZCOMMENTS_PENDING,  'value' => 1),
                               array('text' => _EZCOMMENTS_REJECTED, 'value' => 2));

        $pnRender->assign('statuslevels', $statuslevels); 
        
        // finally asign the comment information
        $pnRender->assign($comment);

        return true;
    }


    function handleCommand(&$pnRender, &$args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('EZComments::', '::' . $this->id, ACCESS_EDIT)) {
            return LogUtil::registerPermissionError(pnModURL('MultiHook', 'admin', 'main'));
        }
        
        if ($args['commandName'] == 'cancel') {  
            // nothing to do            
        } else if ($args['commandName'] == 'submit') {
            $ok = $pnRender->pnFormIsValid();

            $data = $pnRender->pnFormGetValues();
            $data['id'] = $this->id;
            
            if($this->user_is_anonymous == true) {
                // check anon fields
                if(empty($data['ezcomments_anonname'])) {
                    $ifield = & $pnRender->pnFormGetPluginById('ezcomments_anonname');
                    $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_ANONNAMEMISSING));
                    $ok = false;
                }
                // anonmail must be valid - really necessary if an admin changes this?
                if(empty($data['ezcomments_anonmail']) || !pnVarValidate($data['ezcomments_anonmail'], 'email') ) {
                    $ifield = & $pnRender->pnFormGetPluginById('ezcomments_anonmail');
                    $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_ANONMAILMISSING));
                    $ok = false;
                }
                // anonwebsite must be valid
                if(!empty($data['ezcomments_anonwebsite'])  && !pnVarValidate($data['ezcomments_anonmail'], 'url')) {
                    $ifield = & $pnRender->pnFormGetPluginById('ezcomments_anonwebsite');
                    $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_ANONWEBSITEINVALID));
                    $ok = false;
                }
            } else {
                // user has not posted as anonymous, continue normally
            }
            
            // no check on ezcomments_subject as this may be empty
            
            if(empty($data['ezcomments_comment'])) {
                $ifield = & $pnRender->pnFormGetPluginById('ezcomments_comment');
                $ifield->setError(DataUtil::formatForDisplay(_EZCOMMENTS_EMPTYCOMMENT));
                $ok = false;
            }

//prayer($data); die();            
            if(!$ok) {
                return false;
            }
            
            // Call the API to update the item.
            if(pnModAPIFunc('EZComments', 'admin', 'update',
                            array('id'          => $this->id, 
                                  'subject'     => $data['ezcomments_subject'], 
                                  'comment'     => $data['ezcomments_comment'], 
                                  'status'      => $data['ezcomments_status'],
                                  'anonname'    => $data['ezcomments_anonname'], 
                                  'anonmail'    => $data['ezcomments_anonmail'], 
                                  'anonwebsite' => $data['ezcomments_anonwebsite']))) {
                // Success
                LogUtil::registerStatus(_UPDATESUCCEDED);
            }
        }

        if(!empty($this->redirect)) {
            return pnRedirect($this->redirect);
        } else {
            return pnRedirect(pnModURL('EZComments', 'admin', 'main'));
        }
    }

}

?>