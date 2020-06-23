<?php

declare(strict_types=1);

namespace Zikula\EZCommentsModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;
use Zikula\UsersModule\Constant;

/**
 * EZComments entity class.
 *
 * @ORM\Entity(repositoryClass="Zikula\EZCommentsModule\Entity\Repository\EZCommentsEntityRepository")
 * @ORM\Table(name="ezcomments")
 */
class EZCommentsEntity extends EntityAccess
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $modname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $objectid;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $areaid = 0;

    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $uid = 0;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $ownerid = 0;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="text")
     */
    private $subject;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $replyto = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $anonname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $anonmail;

    /**
     * status - codes that determine the status of the comment. Right now blocked (1) or not (0).
     *
     * @ORM\Column(type="integer", length=4)
     */
    private $status = 0;

    /**
     * @ORM\Column(type="string", length=85)
     */
    private $ipaddr;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $anonwebsite;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getModname(): string
    {
        return $this->modname;
    }

    public function setModname(string $modname): void
    {
        $this->modname = $modname;
    }

    public function getObjectid(): string
    {
        return $this->objectid;
    }

    public function setObjectid(string $objectid): void
    {
        $this->objectid = $objectid;
    }

    public function getAreaid(): int
    {
        return $this->areaid;
    }

    public function setAreaid(int $areaid): void
    {
        $this->areaid = $areaid;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    public function getOwnerid(): int
    {
        return $this->ownerid;
    }

    public function setOwnerid(int $ownerid): void
    {
        $this->ownerid = $ownerid;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getReplyto(): int
    {
        return $this->replyto;
    }

    public function setReplyto(int $replyto): void
    {
        $this->replyto = $replyto;
    }

    public function getAnonname(): string
    {
        return $this->anonname;
    }

    public function setAnonname(string $anonname): void
    {
        $this->anonname = $anonname;
    }

    public function getAnonmail(): string
    {
        return $this->anonmail;
    }

    public function setAnonmail(string $anonmail): void
    {
        $this->anonmail = $anonmail;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getIpaddr(): string
    {
        return $this->ipaddr;
    }

    public function setIpaddr(string $ipaddr): void
    {
        $this->ipaddr = $ipaddr;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getAnonwebsite(): string
    {
        return $this->anonwebsite;
    }

    public function setAnonwebsite(string $anonwebsite): void
    {
        $this->anonwebsite = $anonwebsite;
    }

    public function setFromRequest(Request $request, int $ownerId): void
    {
        $params = $request->request->all();
        $this->setUrl($params['retUrl']);
        $this->setObjectid((string) $params['artId']);
        $this->setAreaid((int) $params['areaId']);
        $this->setModname($params['module']);
        $this->setOwnerid($ownerId);
        if (isset($params['parentId'])) {
            $this->setReplyto((int) $params['parentId']);
        }
        $this->setType('safe');
        $this->setIpaddr($request->getClientIp());
        $this->setAnonmail(Constant::USER_ID_ANONYMOUS === $ownerId ? $params['anonEmail'] : '');
        $this->setAnonwebsite(Constant::USER_ID_ANONYMOUS === $ownerId ? $params['anonWebsite'] : '');
        $this->setAnonname($params['user']);
    }
}
