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
                           $sortorder= null,
                           $sortby,
                           $status = -1,
                           $uid = 0,
                           $ownerid = 0)
    {
        //I do not do security checking here. That is the job of the controller.
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from('EZComments:EZCommentsEntity', 'u');

        //search the comments
        if($search != null){
            $qb->orWhere($qb->expr()->like('u.subject', '?2'), $qb->expr()->literal('%' . $search . '%'));
            $qb->orWhere($qb->expr()->like('u.comment', '?2'), $qb->expr()->literal('%' . $search . '%'));
        }

        //limit to the startnum and limit it if numitems is set
        $qb->setFirstResult($startnum);
        if($numitems > 0){
            $qb->setMaxResults($numitems);
        }

        //enter the sort order
        if($sortorder !== null){
            $qb->orderBy('u.' . $sortby, $sortorder);
        }

        //search for status
        if($status >= 0){
            $qb->andWhere('u.status', '?3');
            $qb->setParameter('3', $status);
        }

        if($uid > 0){
            $qb->andWhere('u.uid', '?4');
            $qb->setParameter('4', $uid);
        }

        if($ownerid > 0){
            $qb->andWhere('u.ownerid', '?5');
            $qb->setParameter('5', $ownerid);
        }

        $query = $qb->getQuery();
        $results = $query->getResult();

        return $results;
    }
}