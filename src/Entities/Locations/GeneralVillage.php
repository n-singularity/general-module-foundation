<?php

namespace Nsingularity\GeneralModule\Foundation\Entities\Locations;

use App\Exceptions\CustomMessagesException;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\GeneralModule\Foundation\Entities\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\TimeStampAttributes;

abstract class GeneralVillage extends AbstractEntities
{
    use TimeStampAttributes;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * @var District
     * @ORM\manyToOne(targetEntity="District", inversedBy="villages", fetch="EAGER")
     * @ORM\JoinColumn(name="district_id", onDelete="SET NULL", nullable=true)
     */
    protected $district;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->generateHashId(get_class($this));
    }

    function rule(): array
    {
        return [];
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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return District
     */
    public function getDistrict(): District
    {
        return $this->district;
    }

    /**
     * @param District $district
     */
    public function setDistrict(District $district): void
    {
        $this->district = $district;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
