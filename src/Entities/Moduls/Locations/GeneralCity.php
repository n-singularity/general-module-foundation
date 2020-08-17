<?php

namespace Nsingularity\GeneralModule\Foundation\Entities\Locations;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\GeneralModule\Foundation\Entities\Abstracts\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\TimeStampAttributes;

abstract class GeneralCity extends AbstractEntities
{
    use TimeStampAttributes;

    /**
     * @var Province
     * @ORM\manyToOne(targetEntity="Province", inversedBy="cities", fetch="EAGER")
     * @ORM\JoinColumn(name="province_id", onDelete="SET NULL", nullable=true)
     */
    protected $province;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var ArrayCollection|District[]
     * @ORM\OneToMany(targetEntity="District", mappedBy="city")
     */
    protected $districts;

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    protected $created_at;

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    protected $updated_at;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->generateHashId(get_class($this));
    }

    public function rule(): array
    {
        return [];
    }

    /**
     * @return Province
     */
    public function getProvince(): Province
    {
        return $this->province;
    }

    /**
     * @param Province $province
     */
    public function setProvince(Province $province): void
    {
        $this->province = $province;
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
     * @return District[]|ArrayCollection
     */
    public function getDistricts()
    {
        return $this->districts;
    }

    /**
     * @param District[]|ArrayCollection $districts
     */
    public function setDistricts($districts): void
    {
        $this->districts = $districts;
    }
}
