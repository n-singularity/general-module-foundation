<?php

namespace Nsingularity\GeneralModule\Foundation\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Illuminate\Support\Facades\Hash;
use Nsingularity\AesHashing\AesHashing;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\TimeStampAttributes;

abstract class GeneralUser extends AbstractEntities
{
    use TimeStampAttributes;

    /**
     * @var string
     * @ORM\Column(type="string",  unique=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string",  unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password): bool
    {
        return Hash::check($password, $this->password);
    }
}
