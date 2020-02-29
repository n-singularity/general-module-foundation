<?php

namespace Nsingularity\GeneralModul\Foundation\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nsingularity\AesHashing\AesHashing;
use Nsingularity\GeneralModul\Foundation\Exceptions\CustomMessagesException;

class User extends AbstractEntities
{
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

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password): bool
    {
        return AesHashing::checkAcrypt($password, $this->password);
    }
}
