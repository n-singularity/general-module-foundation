<?php

namespace Nsingularity\GeneralModule\Foundation\Entities;

use App\Repositories\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;
use Exception;

abstract class GeneralUserSession extends AbstractEntities
{
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $user_id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $user_agent;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $remember_me;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    protected $expired_at;

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
     * @return mixed
     */
    public function toArray($arrayType, $include = "")
    {
        return $this->generateTransformer($arrayType, $include);
    }

    function rule()
    {

    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return GeneralUser
     */
    public function getUser()
    {
        $userRepository = new UserRepository();
        return $userRepository->show($this->user_id, [], false);
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * @param string $user_agent
     */
    public function setUserAgent(string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isRememberMe(): bool
    {
        return $this->remember_me;
    }

    /**
     * @param bool $remember_me
     */
    public function setRememberMe(bool $remember_me): void
    {
        $this->remember_me = $remember_me;
    }

    /**
     * @return DateTime
     */
    public function getExpiredAt(): DateTime
    {
        return $this->expired_at;
    }

    /**
     * @throws Exception
     */
    public function generateExpiredAt()
    {
        $this->expired_at = (new DateTime())->setTimestamp(time() + ($this->remember_me ? 3600 * 1000 : 600));
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    /**
     * @param DateTime $created_at
     */
    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param DateTime $updated_at
     */
    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function generateToken()
    {
        return encrypt(json_encode([
            "token_hashId" => $this->getHashId(),
            "agent"        => base64_encode($this->user_agent),
            "remember_me"  => $this->remember_me,
            "expired_at"   => $this->expired_at->getTimestamp(),
        ]));
    }
}
