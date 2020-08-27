<?php

namespace Nsingularity\GeneralModule\Foundation\Entities\Modules\Locations;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\GeneralModule\Foundation\Entities\Abstracts\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\TimeStampAttributes;

abstract class GeneralDistrict extends AbstractEntities
{
    use TimeStampAttributes;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * @var City
     * @ORM\manyToOne(targetEntity="City", inversedBy="districts", fetch="EAGER")
     * @ORM\JoinColumn(name="city_id", onDelete="SET NULL", nullable=true)
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var ArrayCollection|Village[]
     * @ORM\OneToMany(targetEntity="Village", mappedBy="district")
     */
    protected $villages;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->generateHashId(get_class($this));
    }

    static function rule(): array
    {
        return [];
    }

    /**
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city): void
    {
        $this->city = $city;
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
     * @return Village[]|ArrayCollection
     */
    public function getVillages()
    {
        return $this->villages;
    }

    /**
     * @param Village[]|ArrayCollection $villages
     */
    public function setVillages($villages): void
    {
        $this->villages = $villages;
    }
}
