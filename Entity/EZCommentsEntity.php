<?php
namespace Zikula\EZCommentsModule\Entity;

use Zikula\Core\Doctrine\EntityAccess;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * EZComments entity class.
 *
 * @ORM\Entity(repositoryClass="Zikla\EZCommentsModule\Entity\Repository\EZCommentsEntityRepository")
 * @ORM\Table(name="ezcomments")
 */
class EZCommentsEntity extends \Zikula\Core\Doctrine\EntityAccess
{
    /**
     * id
     *
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * modname
     *
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $modname;

    /**
     * objectid
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $objectid;

    /**
     * areaid
     *
     * @ORM\Column(type="integer", length=11)
     */
    private $areaid = 0;

    /**
     * url
     *
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * date
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * uid
     *
     * @ORM\Column(type="integer", length=11)
     */
    private $uid = 0;

    /**
     * ownerid
     *
     * @ORM\Column(type="integer", length=11)
     */
    private $ownerid = 0;

    /**
     * comment
     *
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * subject
     *
     * @ORM\Column(type="text")
     */
    private $subject;

    /**
     * replyto
     *
     * @ORM\Column(type="integer", length=11)
     */
    private $replyto = 0;

    /**
     * anonname
     *
     * @ORM\Column(type="string", length=255)
     */
    private $anonname;

    /**
     * anonmail
     *
     * @ORM\Column(type="string", length=255)
     */
    private $anonmail;

    /**
     * status
     *
     * @ORM\Column(type="integer", length=4)
     */
    private $status = 0;

    /**
     * ipaddr
     *
     * @ORM\Column(type="string", length=85)
     */
    private $ipaddr;

    /**
     * type
     *
     * @ORM\Column(type="string", length=64)
     */
    private $type;

    /**
     * anonwebsite
     *
     * @ORM\Column(type="string", length=255)
     */
    private $anonwebsite;


    public function __construct(){
        $this->date = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getModname()
    {
        return $this->modname;
    }

    /**
     * @param mixed $modname
     */
    public function setModname($modname)
    {
        $this->modname = $modname;
    }

    /**
     * @return mixed
     */
    public function getObjectid()
    {
        return $this->objectid;
    }

    /**
     * @param mixed $objectid
     */
    public function setObjectid($objectid)
    {
        $this->objectid = $objectid;
    }

    /**
     * @return mixed
     */
    public function getAreaid()
    {
        return $this->areaid;
    }

    /**
     * @param mixed $areaid
     */
    public function setAreaid($areaid)
    {
        $this->areaid = $areaid;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getOwnerid()
    {
        return $this->ownerid;
    }

    /**
     * @param mixed $ownerid
     */
    public function setOwnerid($ownerid)
    {
        $this->ownerid = $ownerid;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getReplyto()
    {
        return $this->replyto;
    }

    /**
     * @param mixed $replyto
     */
    public function setReplyto($replyto)
    {
        $this->replyto = $replyto;
    }

    /**
     * @return mixed
     */
    public function getAnonname()
    {
        return $this->anonname;
    }

    /**
     * @param mixed $anonname
     */
    public function setAnonname($anonname)
    {
        $this->anonname = $anonname;
    }

    /**
     * @return mixed
     */
    public function getAnonmail()
    {
        return $this->anonmail;
    }

    /**
     * @param mixed $anonmail
     */
    public function setAnonmail($anonmail)
    {
        $this->anonmail = $anonmail;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getIpaddr()
    {
        return $this->ipaddr;
    }

    /**
     * @param mixed $ipaddr
     */
    public function setIpaddr($ipaddr)
    {
        $this->ipaddr = $ipaddr;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getAnonwebsite()
    {
        return $this->anonwebsite;
    }

    /**
     * @param mixed $anonwebsite
     */
    public function setAnonwebsite($anonwebsite)
    {
        $this->anonwebsite = $anonwebsite;
    }


}

