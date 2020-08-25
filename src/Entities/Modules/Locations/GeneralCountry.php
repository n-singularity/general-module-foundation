<?php

namespace Nsingularity\GeneralModule\Foundation\Entities\Modules\Locations;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\GeneralModule\Foundation\Entities\Abstracts\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\TimeStampAttributes;

abstract class GeneralCountry extends AbstractEntities
{
    use TimeStampAttributes;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    //one to many relations
    /**
     * @var ArrayCollection|Province[]
     * @ORM\OneToMany(targetEntity="Province", mappedBy="country")
     */
    protected $provinces;

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
     * @return Province[]|ArrayCollection
     */
    public function getProvinces()
    {
        return $this->provinces;
    }

    /**
     * @param Province[]|ArrayCollection $provinces
     */
    public function setProvinces($provinces): void
    {
        $this->provinces = $provinces;
    }
}
