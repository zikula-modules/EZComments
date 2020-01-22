<?php

namespace Zikula\EZCommentsModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class EZCommentsEntityRepository extends EntityRepository
{
    const MINDATE = 0;
    const MAXDATE = 1;

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
     * @param $uid (optional) get all comments of this user
     * @param $owneruid (optional) get all comments of this content owner
     * @param $admin (optional) is set to 1 for admin mode (permission check)
     * @return array array of items, or false on failure
     */
    public function getComments($mod = "",
                                $objectid = -1,
                                $replyTo = -1,
                                $search = null,
                                $startnum = 1,
                                $numitems = -1,
                                $sortorder = 'ASC',
                                $sortby = 'date',
                                $status = -1,
                                $uid = 0,
                                $ownerid = 0)
    {
        //I do not do security checking here. That is the job of the controller.
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u');

        if (!empty($mod)) {
            $qb->andWhere($qb->expr()->eq('u.modname', ':mod'));
            $qb->setParameter('mod', $mod);
        }
        if ($objectid != -1) {
            $qb->andWhere($qb->expr()->eq('u.objectid', ':objectid'));
            $qb->setParameter('objectid', $objectid);
        }
        if ($replyTo != -1) {
            $qb->andWhere($qb->expr()->eq('u.replyto', '?5'));
            $qb->setParameter('5', $replyTo);
        }
        //search the comments
        if ($search != null) {
            $qb->orWhere($qb->expr()->like('u.subject', '?2'), $qb->expr()->literal('%' . $search . '%'));
            $qb->orWhere($qb->expr()->like('u.comment', '?2'), $qb->expr()->literal('%' . $search . '%'));
        }

        //limit to the startnum and limit it if numitems is set
        $qb->setFirstResult($startnum);
        if ($numitems > 0) {
            $qb->setMaxResults($numitems);
        }

        //enter the sort order
        if ($sortorder !== null) {
            $qb->orderBy('u.' . $sortby, $sortorder);
        }

        //search for status
        if ($status >= 0) {
            $qb->andWhere($qb->expr()->eq('u.status', '?3'));
            $qb->setParameter('3', $status);
        }

        if ($uid > 0) {
            $qb->andWhere($qb->expr()->eq('u.uid', '?4'));
            $qb->setParameter('4', $uid);
        }

        if ($ownerid > 0) {
            $qb->andWhere($qb->expr()->eq('u.ownerid', '?5'));
            $qb->setParameter('5', $ownerid);
        }

        $query = $qb->getQuery();
        $results = $query->getResult();

        return $results;
    }

    /**
     * deleteReplies
     * Given a comment id, do a bulk delete of any replies.
     * @param $commentId
     * @return mixed
     */
    public function deleteReplies($commentId)
    {
        //This call may not delete any replies, that's just fine.
        $q = $this->_em->createQuery("delete from 'ZikulaEZCommentsModule:EZCommentsEntity' m where m.replyto = " . $commentId);
        $numDeleted = $q->execute();
        return $numDeleted;
    }

    public function getLatestPost()
    {
        //find the last date of the comment
        $date = $this->getPostBorder(self::MAXDATE);
        return $this->getPostWithDate($date);

    }

    public function getPostWithDate($inDate)
    {
        $qb2 = $this->_em->createQueryBuilder();
        $qb2->select('b')
            ->from($this->_entityName, 'b')
            ->andWhere($qb2->expr()->eq('b.date', '?1'))
            ->setParameter(1, $inDate);
        return $qb2->getQuery()->getResult();
    }

    public function getEarliestPost()
    {
        $date = $this->getPostBorder(self::MINDATE);
        return $this->getPostWithDate($date);
    }

    public function getPostBorder($inPostBorder){
        $qb = $this->_em->createQueryBuilder();
        $qb->from($this->_entityName, 'a');
        $dateItem = null;
        if($inPostBorder === self::MINDATE){
            $dateItem =  $qb->select($qb->expr()->min('a.date'))->getQuery()->getResult();
        } else {
            $dateItem =  $qb->select($qb->expr()->max('a.date'))->getQuery()->getResult();
        }
        return $dateItem[0];
    }

    public function count($row, $parameter = '', $distinct = false)
    {
        $qb = $this->_em->createQueryBuilder();
        if ($distinct) {
            $qb->select($qb->expr()->countDistinct('t.' . $row));
        } else {
            $qb->select($qb->expr()->count('t'));
        }
        $qb->from($this->_entityName, 't');
        if ($parameter) {
            $qb->where($qb->expr()->eq('t.' . $row, '?1'))
                ->setParameter(1, $parameter);

        }
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }

    public function mostActivePosters($number)
    {
        if($number < 1){
            return [];
        }
        $uniqueUsers = $this->findUniqueUsers();
        $userCounts= [];
        foreach ($uniqueUsers as $user) {
            $currCount = $this->count('anonname', $user['anonname']);
            $userCounts[$user['anonname']] = $currCount;
        }
        //use rsort to get the array sorted.
        arsort($userCounts);
        $sliceNo = min(count($userCounts), $number);
        return array_slice($userCounts, 0, $sliceNo);
    }

    public function findUniqueUsers(){
        $qb = $this->_em->createQueryBuilder();
        $qb->from($this->_entityName, 't');
        $qb->select('t.anonname')->distinct();
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findPostRate(){
        //get the min post
        $firstDate = $this->getPostBorder(self::MINDATE);
        $lastDate = $this>$this->getPostBorder(self::MAXDATE);
        $firstDay = new \DateTime($firstDate[1]);
        $lastDay = new \DateTime($lastDate[1]);
        $interval = $firstDay->diff($lastDay);

        $totalPosts = $this->count('modname');
        $days = $interval->days + 1;

        return $totalPosts/$days;
    }

    public function getLatestComments($properties){
        //Grab all comments after the set date
        $cutOffTime = new \DateTime("now");
        $cutOffTime->sub(new \DateInterval("P" . $properties['numdays'] . "D"));
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b');
        $qb->from($this->_entityName, 'b')
            ->andWhere($qb->expr()->gte('b.date', '?1'))
            ->setParameter(1, $cutOffTime)
            ->orderBy('b.date', 'DESC')
            ->setMaxResults($properties['numcomments']);
       return  $qb->getQuery()->getResult();
    }
}