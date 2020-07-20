<?php

namespace Nsingularity\GeneralModule\Foundation\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Illuminate\Support\Facades\Hash;
use Nsingularity\AesHashing\AesHashing;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\TimeStampAttributes;

abstract class GeneralAddress extends AbstractEntities
{
    use TimeStampAttributes;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $label;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $recheiver;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $province;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $regency;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $district;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $village;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->generateHashId(get_class($this));
    }
}
