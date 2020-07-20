<?php

namespace Nsingularity\GeneralModule\Foundation\Entities\Locations;

use App\Exceptions\CustomMessagesException;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\GeneralModule\Foundation\Entities\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\TimeStampAttributes;

abstract class GeneralProvince extends AbstractEntities
{
    use TimeStampAttributes;

    /**
     * @var Country
     * @ORM\manyToOne(targetEntity="Country", inversedBy="provinces")
     * @ORM\JoinColumn(name="country_id", onDelete="SET NULL", nullable=true)
     */
    protected $country;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    //one to many relations
    /**
     * @var ArrayCollection|City[]
     * @ORM\OneToMany(targetEntity="City", mappedBy="province")
     */
    protected $cities;

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
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country): void
    {
        $this->country = $country;
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
     * @return City[]|ArrayCollection
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * @param City[]|ArrayCollection $cities
     */
    public function setCities($cities): void
    {
        $this->cities = $cities;
    }
}
