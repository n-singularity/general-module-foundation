<?php

namespace Nsingularity\GeneralModule\Foundation\Entities;

use App\Entities\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\GeneralModule\Foundation\Entities\Abstracts\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\CreatedAtAttribute;
use Nsingularity\GeneralModule\Foundation\Transformers\EntityChangeLogTransformer;

Abstract class GeneralEntityChangeLog extends AbstractEntities
{
    use EntityChangeLogTransformer, CreatedAtAttribute;

    /**
     * @var User
     * @ORM\manyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", onDelete="SET NULL", nullable=true)
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $ref_table;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $ref_id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $parent_ref_table;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $parent_ref_id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $diff;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        $this->ip = $ip_address;
        $this->generateHashId(get_class($this));
    }

    public function rule(): array
    {
        return [];
    }

    /**
     * @return GeneralUser
     */
    public function getUser(): GeneralUser
    {
        return $this->user;
    }

    /**
     * @param GeneralUser $user
     */
    public function setUser(?GeneralUser $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getRefTable(): string
    {
        return $this->ref_table;
    }

    /**
     * @param string $ref_table
     */
    public function setRefTable(string $ref_table): void
    {
        $this->ref_table = $ref_table;
    }

    /**
     * @return string
     */
    public function getRefId(): string
    {
        return $this->ref_id;
    }

    /**
     * @param string $ref_id
     */
    public function setRefId(string $ref_id): void
    {
        $this->ref_id = $ref_id;
    }

    /**
     * @return string
     */
    public function getDiff(): string
    {
        return $this->diff;
    }

    /**
     * @return string
     */
    public function getParentRefTable(): string
    {
        return $this->parent_ref_table;
    }

    /**
     * @return string
     */
    public function getParentRefId(): string
    {
        return $this->parent_ref_id;
    }

    /**
     * @param AbstractEntities|null $parent
     */
    public function setParent(?AbstractEntities $parent): void
    {
        if ($parent instanceof AbstractEntities) {
            $this->parent_ref_table = $parent->getTableName();
            $this->parent_ref_id    = $parent->getId();
        }
    }

    /**
     * @param string $diff
     */
    public function setDiff(string $diff): void
    {
        $this->diff = $diff;
    }
}
