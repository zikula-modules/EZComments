<?php

namespace Zikula\EZCommentsModule\Controller;

use Zikula\Core\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Component\SortableColumns\Column;
use Zikula\Core\Response\Ajax\ForbiddenResponse;
use Zikula\Bundle\HookBundle\Hook\ProcessHook;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;
use Zikula\EZCommentsModule\Entity\Repository\EZCommentsEntityRepository;

/**
 * @Route("/admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("")
     * @param $request - the incoming request.
     * The main entry point. List a page of comments
     *
     * @return Response The rendered output consisting mainly of the admin menu
     *
     * @throws AccessDeniedException Thrown if the user does not have the appropriate access level for the function.
     */
    public function indexAction(Request $request)
    {
        // Security check
        if (!$this->hasPermission('EZComments::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException($this->__('You do not have pemission to access the EZcomments admin interface.'));
        }
        //todo:use the pages index action as an example of how to set up a pager
        $startnum = $request->query->get('startnum', 1);
        $orderBy = $request->query->get('orderby', 'id');
        $currentSortDirection = $request->query->get('sdir', Column::DIRECTION_DESCENDING);


        // presentation values
        $showall = $request->query->get('showall');
        if ($showall) {
            $itemsperpage = -1;
        } else {
            $itemsperpage = $this->getVar('itemsperpage');
        }

        $startnum = $request->query->get('startnum');
        $column = $request->query->get('column');
        return  $this->render('@ZikulaEZCommentsModule\Admin\ezcomments_index.html.twig');

        $repo = $this->getDoctrine()->getManager()->getRepository('ZikulaEZCommentsModule:EZCommentsEntity');
        $repo->getall("", -1, $startnum, $itemsperpage, $currentSortDirection, $column);
        /*
         * $mod="",
                           $objectid=-1,
                           $search = null,
                           $startnum = 1,
                           $numitems = -1,
                           $sortorder= null,
                           $sortby = 'ASC',
                           $status = -1,
                           $uid = 0,
                           $ownerid = 0)
        // call the api to get all current comments
        $items = ModUtil::apiFunc('EZComments', 'user', 'getall',
                              array('startnum' => $showall == true ? true : $startnum,
                                    'numitems' => $itemsperpage,
                                    'status'   => $status,
                                    'admin'    => 1));

        if ($items === false) {
            return LogUtil::registerError($this->__('Internal Error.'));
        }

        // loop through each item adding the relevant links
        $comments = array();
        foreach ($items as $item)
        {
            $options = array(array('url' => $item['url'] . '#comment' . $item['id'],
                                   'image' => 'kview.png',
                                   'title' => $this->__('View')));

            $options[] = array('url'   => ModUtil::url('EZComments', 'admin', 'modify', array('id' => $item['id'])),
                               'image' => 'xedit.png',
                               'title' => $this->__('Edit'));

            $item['options'] = $options;
            $comments[] = $item;
        }

        $numberOfItems = ModUtil::apiFunc('EZComments', 'user', 'countitems', array('status' => $status, 'admin' => 1));

        // assign collected data to the template
        $this->view->assign('items', $comments)
                   ->assign('status', $status)
                   ->assign('showall', $showall)
                   ->assign('pager', array('numitems'     => $numberOfItems,
                                           'itemsperpage' => $itemsperpage));

        // Return the output
        return $this->view->fetch('ezcomments_admin_view.tpl');*/
    }

    /**
     * @Route("/modify")
     *
     * @param request
     * Modify a comment
     *
     * This is a standard function that is called whenever an administrator
     * wishes to modify a comment
     *
     * @param  tid  the id of the comment to be modified
     * @return string the modification page
     */
    public function modifyAction(Request $request)
    {
        /*
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

        // Create Form output object
        $render = FormUtil::newForm('EZComments', $this);

        // Return the output that has been generated by this function
        return $render->execute("ezcomments_admin_modify.tpl", new EZComments_Form_Handler_Admin_Modify());*/
    }

    /**
     * @Route("/delete/{comment}")
     * @param $request
     * @param $comment
     *
     * Delete item
     *
     * This is a standard function that is called whenever an administrator
     * wishes to delete a current module item.
     *
     * @author The EZComments Development Team
     * @param id  the id of the item to be deleted
     * @param redirect the location to redirect to after the deletion attempt
     * @return bool true on sucess, false on failure
     */
    public function deleteAction(Request $request, EZCommentsEntity $comment)
    {
        // delete functionalityx has been moved to the modify function which uses the Form framework.
        // We need this function for backwards compatibility only

        // Get parameters from whatever input we need.
/*        $id       = isset($args['id'])       ? $args['id']       : FormUtil::getPassedValue('id',       null, 'GETPOST');
        $objectid = isset($args['objectid']) ? $args['objectid'] : FormUtil::getPassedValue('objectid', null, 'GETPOST');
        $redirect = isset($args['redirect']) ? $args['redirect'] : FormUtil::getPassedValue('redirect', '', 'GETPOST');

        return System::redirect(ModUtil::url('EZComments', 'admin', 'modify',
                                   array('id'       => $id,
                                         'objectid' => $objectid,
                                         'redirect' => $redirect)));*/
    }

    /**
     * @Route("/processselected")
     * @param $request
     *
     * Process multiple comments
     *
     * This function process the comments selected in the admin view page.
     * Multiple comments may have thier state changed or be deleted
     *
     * @param  Comments   the ids of the items to be deleted
     * @param  confirmation  confirmation that this item can be deleted
     * @param  redirect the location to redirect to after the deletion attempt
     * @return bool true on sucess, false on failure
     */
    public function processselected(Request $request)
    {
        // check csrf token
        /*$this->checkCsrfToken();

        // Get parameters from whatever input we need.
        $comments = isset($args['comments']) ? $args['comments'] : FormUtil::getPassedValue('comments', null, 'POST');
        $action   = isset($args['action'])   ? $args['action']   : FormUtil::getPassedValue('action', null, 'POST');
        $redirect = isset($args['redirect']) ? $args['redirect'] : FormUtil::getPassedValue('redirect', null, 'POST');

        // loop round each comment deleted them in turn
        foreach ($comments as $comment) {
            switch(strtolower($action))
            {
                case 'delete':
                    // The API function is called.
                    if (ModUtil::apiFunc('EZComments', 'admin', 'delete', array('id' => $comment))) {
                        // Success
                        LogUtil::registerStatus($this->__('Done! Item deleted.'));
                    }
                    break;

                case 'approve':
                    if (ModUtil::apiFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 0))) {
                        // Success
                        LogUtil::registerStatus($this->__('Done! Item updated.'));
                    }
                    break;

                case 'hold':
                    if (ModUtil::apiFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 1))) {
                        // Success
                        LogUtil::registerStatus($this->__('Done! Item updated.'));
                    }
                    break;

                case 'reject':
                    if (ModUtil::apiFunc('EZComments', 'admin', 'updatestatus', array('id' => $comment, 'status' => 2))) {
                        // Success
                        LogUtil::registerStatus($this->__('Done! Item updated.'));
                    }
                    break;
            }
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        if (empty($redirect)) {
            $redirect = ModUtil::url('EZComments', 'admin', 'main');
        }
        return System::redirect($redirect);*/
    }

    /**
     * @Route("/modifyconfig")
     * @param $reqeust
     * Modify configuration
     *
     * This is a standard function to modify the configuration parameters of the
     * module
     *
     * @return string The configuration page
     */
    public function modifyconfigAction(Request $request)
    {
/*        // Security check
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Create Form output object
        $render = FormUtil::newForm('EZComments', $this);

        // Return the output that has been generated by this function
        return $render->execute('ezcomments_admin_modifyconfig.tpl', new EZComments_Form_Handler_Admin_ModifyConfig());*/
    }

    /**
     * Migration functionality
     *
     * This function provides a common interface to migration scripts.
     * The migration scripts will upgrade from different other modules
     * (like NS-Comments, Reviews, My_eGallery, ...) to EZComments.
     *
     * @return output the migration interface
     */
    public function migrate()
    {
        /*if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $migrated  = $this->getVar('migrated');
        $available = FileUtil::getFiles('modules/EZComments/migrateapi', false, true, 'php', 'f');

        $selectitems = array();
        foreach ($available as $f) {
            $f = substr($f, 0, -4);
            if (!isset($migrated[$f]) || !$migrated[$f]) {
                $selectitems[$f] = $f;
            }
        }

        if (!$selectitems) {
            LogUtil::registerStatus($this->__('No migration plugins available.'));
            return System::redirect(ModUtil::url('EZComments', 'admin'));
        }

        // assign the migratation options
        $this->view->assign('selectitems', $selectitems);

        // Return the output that has been generated by this function
        return $this->view->fetch('ezcomments_admin_migrate.tpl');*/
    }

    /**
     * Do the migration
     *
     * This is the function that is called to do the actual
     * migration.
     *
     * @param $migrate The plugin to do the migration
     */
    public function migrate_go()
    {
        // Permissions
        /*if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // check csrf token
        $this->checkCsrfToken();

        // Parameter
        $migrate = FormUtil::getPassedValue('migrate');
        if (!isset($migrate)){
            return LogUtil::registerArgsError();
        }

        // call the migration function
        if (ModUtil::apiFunc('EZComments', 'migrate', $migrate)) {
            $migrated = $this->getVar('migrated', array('dummy' => true));
            $migrated[$migrate] = true;
            ModUtil::setVar('EZComments', 'migrated', $migrated);
        }

        return System::redirect(ModUtil::url('EZComments', 'admin', 'migrate'));*/
    }

    /**
     * @Route("/cleanup")
     * @param $request
     * Cleanup functionality
     *
     * This is the interface to the Cleanup functionality.
     * When a Module is deleted, EZComments doesn't know about
     * this.
     * @todo: Make this so that EZComments get notified when a module is deleted.
     * Thus, any comments for this module stay in the database.
     * With this functionality you can delete these comments.
     *
     * @return output the cleanup interface
     */
    public function cleanup(Request $request)
    {
        /*if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // build a simple array of all available modules
        $mods = ModUtil::getAllMods();
        $allmods = array();
        foreach ($mods as $mod) {
            $allmods[] = $mod['name'];
        }

        $usedmods = ModUtil::apiFunc('EZComments', 'admin', 'getUsedModules');

        $orphanedmods = array_diff($usedmods, $allmods);

        if (!$orphanedmods) {
            LogUtil::registerStatus($this->__('No orphaned comments.'));
            return System::redirect(ModUtil::url('EZComments', 'admin', 'main'));
        }

        $selectitems = array();
        foreach ($orphanedmods as $mod) {
            $selectitems[$mod] = $mod;
        }

        $this->view->assign('selectitems', $selectitems);

        return $this->view->fetch('ezcomments_admin_cleanup.tpl');*/
    }

    /**
     * Do the migration
     *
     * This is the function that is called to do the actual
     * deletion of orphaned comments.
     *
     * @param  $module The Module to delete for
     */
    public function cleanup_go()
    {
        /*// Permissions
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // check csrf token
        $this->checkCsrfToken();

        $module = FormUtil::getPassedValue('ezcomments_module');
        if (!isset($module)) {
            return LogUtil::registerArgsError();
        }

        if (!ModUtil::apiFunc('EZComments', 'admin', 'deleteall', compact('module'))) {
            return LogUtil::registerError($this->__('Error! A general failure occurs.'));
        }

        LogUtil::registerStatus($this->__('Done! All orphaned comments for this module deleted.'));

        return System::redirect(ModUtil::url('EZComments', 'admin', 'main'));*/
    }

    /**
     * Purge comments
     *
     * @param  confirmation  confirmation that this item can be deleted
     * @param  redirect the location to redirect to after the deletion attempt
     * @return bool true on sucess, false on failure
     */
    public function purge($args)
    {
       /* // Get parameters from whatever input we need.
        $purgepending  = isset($args['purgepending'])  ? $args['purgepending']  : FormUtil::getPassedValue('purgepending', null, 'POST');
        $purgerejected = isset($args['purgerejected']) ? $args['purgerejected'] : FormUtil::getPassedValue('purgerejected', null, 'POST');
        $confirmation  = isset($args['confirmation'])  ? $args['confirmation']  : FormUtil::getPassedValue('confirmation', null, 'POST');

        // Security check
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet - display a suitable form to obtain confirmation
            // of this action from the user

            // Return the output that has been generated by this function
            return $this->view->fetch('ezcomments_admin_purge.tpl');
        }

        // If we get here it means that the user has confirmed the action
        // check csrf token
        $this->checkCsrfToken();

        // The API function is called.
        if (ModUtil::apiFunc('EZComments', 'admin', 'purge',
            array('purgepending' => $purgepending, 'purgerejected' => $purgerejected))) {
            // Success
            LogUtil::registerStatus($this->__('Done! Comment deleted.'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('EZComments', 'admin', 'main'));*/
    }

    /**
     * @Route("/commentstats")
     * @param $request
     *
     * @author Mark West
     * @return string html output
     */
    public function commentStatsAction(Request $request)
    {
       /* // security check
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // assign the module vars
        $this->view->assign(ModUtil::getVar('EZComments'));

        // get a list of the hooked modules
        $subscriberModules = HookUtil::getHookSubscribers();
        $hookedmodules = array();
        foreach ($subscriberModules as $module) {
            $bindingCount = count(HookUtil::getBindingsBetweenOwners($module['name'], 'EZComments'));
            if ($bindingCount > 0) {
                $hookedmodules[] = $module['name'];
            }
        }

        // get a list of comment stats by module
        $commentstats = array();
        foreach ($hookedmodules as $mod)
        {
            $data = ModUtil::getInfo(ModUtil::getIdFromName($mod));
            $data['modid'] = $data['id'];
            $data['approvedcomments'] = ModUtil::apiFunc('EZComments', 'user', 'countitems', array('status' => 0, 'mod' => $data['name']));
            $data['pendingcomments']  = ModUtil::apiFunc('EZComments', 'user', 'countitems', array('status' => 1, 'mod' => $data['name']));
            $data['rejectedcomments'] = ModUtil::apiFunc('EZComments', 'user', 'countitems', array('status' => 2, 'mod' => $data['name']));
            $data['totalcomments']    = $data['approvedcomments'] + $data['pendingcomments'] + $data['rejectedcomments'];

            $commentstats[] = $data;
        }
        $this->view->assign('commentstats', $commentstats);

        // Return the output
        return $this->view->fetch('ezcomments_admin_stats.tpl');*/
    }

    /**
     * @Route("/modulestats")
     * @param $request
     *
     * display all comments for a module
     *
     * @author Mark West
     * @return string html output
     */
    public function modulestatsAction(Request $request)
    {
        // security check
       /* if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // get our input
        $mod = FormUtil::getPassedValue('mod');

        // assign the module vars
        $this->view->assign(ModUtil::getVar('EZComments'));

        // get a list of comments
        $modulecomments = ModUtil::apiFunc('EZComments', 'user', 'getallbymodule', array('mod' => $mod));

        // assign the module info
        $modid = ModUtil::getIdFromName($mod);
        $this->view->assign('modid', $modid)
                   ->assign(ModUtil::getInfo($modid));

        // get a list of comment stats by module
        $commentstats = array();
        foreach ($modulecomments as $data) {
            $data['approvedcomments'] = ModUtil::apiFunc('EZComments', 'user', 'countitems', array('status' => 0, 'mod' => $mod, 'objectid' => $data['objectid']));
            $data['pendingcomments']  = ModUtil::apiFunc('EZComments', 'user', 'countitems', array('status' => 1, 'mod' => $mod, 'objectid' => $data['objectid']));
            $data['rejectedcomments'] = ModUtil::apiFunc('EZComments', 'user', 'countitems', array('status' => 2, 'mod' => $mod, 'objectid' => $data['objectid']));
            $data['totalcomments']    = $data['count'];
            $commentstats[] = $data;
        }
        $this->view->assign('commentstats', $commentstats);

        // Return the output
        return $this->view->fetch('ezcomments_admin_modulestats.tpl');*/
    }

    /**
     * @Route("/deletemodule")
     * @param $request
     * @param $moduleName
     *
     * delete all comments attached to a module
     *
     * @author Mark West
     * @param  modname the name of the module to delete all comments for
     * @param  confirmation  confirmation that this item can be deleted
     * @return bool true on sucess, false on failure
     */
    public function deletemoduleAciton(Request $request, $moduleName)
    {
        // Get parameters from whatever input we need.
        /*$modid        = isset($args['modid']) ? $args['modid'] : FormUtil::getPassedValue('modid', null, 'GETPOST');
        $confirmation = isset($args['confirmation']) ? $args['confirmation'] : FormUtil::getPassedValue('confirmation', null, 'GETPOST');

        // get our module info
        $modinfo = ModUtil::getInfo($modid);

        // Security check
        if (!$modinfo || $modinfo['name'] == 'zikula' || !SecurityUtil::checkPermission('EZComments::', "$modinfo[name]::", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet

            // Add a hidden field for the item ID to the output
            $this->view->assign('modid', $modid)
                       ->assign($modinfo);

            // Return the output that has been generated by this function
            return $this->view->fetch('ezcomments_admin_deletemodule.tpl');
        }

        // If we get here it means that the user has confirmed the action
        // check csrf token
        $this->checkCsrfToken();

        // The API function is called.
        // note: the api call is a little different here since we'll really calling a hook function that will
        // normally be executed when a module is deleted. The extra nesting of the modname inside an extrainfo
        // array reflects this
        $xtra = pnModAPIFunc('EZComments', 'admin', 'deletemodule', array('extrainfo' => array('module' => $modinfo['name'])));
        if ($xtra['EZComments']) {
            // Success
            LogUtil::registerStatus($this->__('Done! Comment deleted.'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('EZComments', 'admin', 'main'));*/
    }



    /**
     * Delete all comments attached to a module
     * todo:I am not sure what the point of this one is.
     * @author Mark West
     * @param  mod the name of the module to delete all comments for
     * @param  confirmation confirmation that this item can be deleted
     * @param  allcomments delete all comments fir this module
     * @param  status only delete comments of this status
     * @return bool true on sucess, false on failure
     */
    public function applyrules($args)
    {
        /*// Get parameters from whatever input we need.
        $mod          = isset($args['mod']) ? $args['mod'] : FormUtil::getPassedValue('mod', null, 'GETPOST');
        $confirmation = isset($args['confirmation']) ? $args['confirmation'] : FormUtil::getPassedValue('confirmation', null, 'GETPOST');
        $allcomments  = isset($args['allcomments']) ? $args['allcomments'] : FormUtil::getPassedValue('allcomments', null, 'GETPOST');
        $status       = isset($args['status']) ? $args['status'] : FormUtil::getPassedValue('status', null, 'GETPOST');

        // Security check
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet

            // assign the status flags
            $this->view->assign('statuslevels', array('1' => $this->__('Pending'),
                                                      '2' => $this->__('Rejected'),
                                                      '0' => $this->__('Approved')));

            // Return the output that has been generated by this function
            return $this->view->fetch('ezcomments_admin_applyrules_form.tpl');
        }

        // If we get here it means that the user has confirmed the action
        // check csrf token
        $this->checkCsrfToken();

        // get the matching comments
        $args = array();
        if (!$allcomments) {
            $args['status'] = $status;
        }
        $comments = ModUtil::apiFunc('EZComments', 'user', 'getall', $args);

        // these processes could take some time
        set_time_limit(0);

        // apply the moderation filter to each comment
        $moderatedcomments = array();
        $blacklistedcomments = array();
        foreach ($comments as $comment)
        {
            $subjectstatus = ModUtil::apiFunc('EZComments', 'user', 'checkcomment', array($comment['subject']));
            $commentstatus = ModUtil::apiFunc('EZComments', 'user', 'checkcomment', array($comment['comment']));
            // Akismet
            if (ModUtil::available('Akismet') && $this->getVar('Akismet')
                && ModUtil::apiFunc('Akismet', 'user', 'isspam',
                                array('author'      => ($comment['uid'] > 0) ? UserUtil::getVar('uname', $comment['uid']) : $comment['anonname'],
                                      'authoremail' => ($comment['uid'] > 0) ? UserUtil::getVar('email', $comment['uid']) : $comment['anonmail'],
                                      'authorurl'   => ($comment['uid'] > 0) ? UserUtil::getVar('url', $comment['uid']) : $comment['anonwebsite'],
                                      'content'     => $comment['comment'],
                                      'permalink'   => $comment['url']))) {
                $akismetstatus = $this->getVar('akismetstatus');
            } else {
                $akismetstatus = $commentstatus;
            }
            if (($subjectstatus == 0 && $commentstatus == 0 && $akismetstatus == 0) && $comment['status'] != 0) {
                continue;
            }

            // defines the available options
            $options = array(array('url' => $comment['url'] . '#comment' . $comment['id'],
                                   'title' => $this->__('View')));

            if (SecurityUtil::checkPermission('EZComments::', "$comment[mod]:$comment[objectid]:$comment[id]", ACCESS_EDIT)) {
                $options[] = array('url'   => ModUtil::url('EZComments', 'admin', 'modify', array('id' => $comment['id'])),
                                   'title' => $this->__('Edit'));
            }
            $comment['options'] = $options;

            // fill the corresponding array
            if (($subjectstatus == 1 || $commentstatus == 1 || $akismetstatus == 1) && $comment['status'] != 1) {
                $moderatedcomments[] = $comment;
            }
            if (($subjectstatus == 2 || $commentstatus == 2 || $akismetstatus == 2) && $comment['status'] != 2) {
                $blacklistedcomments[] = $comment;
            }
        }

        // for the first confirmation display a results page to the user
        if (!empty($confirmation) && $confirmation == 1) {
            $this->view->assign('moderatedcomments', $moderatedcomments)
                       ->assign('blacklistedcomments', $blacklistedcomments)
                       ->assign('status', $status)
                       ->assign('allcomments', $allcomments);

            // Return the output that has been generated by this function
            return $this->view->fetch('ezcomments_admin_applyrules_results.tpl');
        }

        if (!empty($confirmation) && $confirmation == 2) {
            foreach ($moderatedcomments as $comment) {
                $comment['status'] = 1;
                ModUtil::apiFunc('EZComments', 'admin', 'update', $comment);
            }

            foreach ($blacklistedcomments as $comment) {
                $comment['status'] = 2;
                ModUtil::apiFunc('EZComments', 'admin', 'update', $comment);
            }

            LogUtil::registerStatus($this->__('New comment rules applied'));
            return System::redirect(ModUtil::url('EZComments', 'admin'));
        }*/
    }
}
