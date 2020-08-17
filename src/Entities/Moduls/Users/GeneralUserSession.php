<?php

namespace Nsingularity\GeneralModule\Foundation\Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;
use Exception;
use Nsingularity\GeneralModule\Foundation\Entities\Abstracts\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Entities\Traits\TimeStampAttributes;

abstract class GeneralUserSession extends AbstractEntities
{
    use TimeStampAttributes;

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
