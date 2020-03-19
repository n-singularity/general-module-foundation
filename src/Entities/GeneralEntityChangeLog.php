<?php

namespace Nsingularity\GeneralModule\Foundation\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Transformers\EntityChangeLogTransformer;

class GeneralEntityChangeLog extends AbstractEntities
{
    use EntityChangeLogTransformer;

    /**
     * @var GeneralUser
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
     * @return GeneralUser
     */
    public function getUser(): GeneralUser
    {
        return $this->user;
    }

    /**
     * @param GeneralUser $user
     */
    public function setUser(GeneralUser $user): void
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
