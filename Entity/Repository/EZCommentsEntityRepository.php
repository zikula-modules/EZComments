<?php

namespace Zikula\EZCommentsModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;

class EZCommentsEntityRepository extends EntityRepository
{
    /**
     * Get comments for a specific item inside a module
     *
     * This function provides the main user interface to the comments
     * module.
     *
     * @param $mod         Name of the module to get comments for
     * @param $objectid    ID of the item to get comments for
     * @param $search      an array with words to search for and a boolean
     * @param $startnum    First comment
     * @param $numitems    number of comments
     * @param $sortorder   order to sort the comments
     * @param $sortby      field to sort the comments by
     * @param $status      get all comments of this status
     * @param $uid         (optional) get all comments of this user
     * @param $owneruid    (optional) get all comments of this content owner
     * @param $admin       (optional) is set to 1 for admin mode (permission check)
     * @return array array of items, or false on failure
     */
    public function getall($mod,
                           $objectid,
                           $search = null,
                           $startnum = 1,
                           $numitems = -1,
                           $sortorder='ASC',
                           $sortby,
                           $status = -1,
                           $uid = -1,
                           $ownerid = -1,
                           $admin = true )
    {

    }
}