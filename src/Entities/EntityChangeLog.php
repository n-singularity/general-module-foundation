<?php

namespace Nsingularity\GeneralModul\Foundation\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\GeneralModul\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModul\Foundation\Transformers\EntityChangeLogTransformer;

class EntityChangeLog extends AbstractEntities
{
    use EntityChangeLogTransformer;

    /**
     * @var User
     * @ORM\manyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", onDelete="SET NULL", nullable=true)
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
     * @ORM\Column(type="text")
     */
    protected $diff;

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    protected $created_at;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->generateHashId(get_class($this));
    }
    
    /**
     * @param $arrayType
     * @param string $include
     * @return array|mixed
     * @throws CustomMessagesException
     */
    public function toArray($arrayType, $include = "")
    {
        return $this->generateTransformer($arrayType, $include);
    }

    function rule()
    {

    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
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
     * @param string $diff
     */
    public function setDiff(string $diff): void
    {
        $this->diff = $diff;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
