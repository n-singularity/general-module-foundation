<?php

namespace Nsingularity\GeneralModule\Foundation\Controller\Api;

use App\Entities\UserSession;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralUser;

class Auth
{
    /** @var  GeneralUser */
    private $user;

    /** @var UserSession */
    private $user_session;

    /**
     * @return GeneralUser
     */
    public function getUser(): GeneralUser
    {
        return $this->user;
    }

    /**
     * @param string $currentSession
     */
    public function setUser($currentSession)
    {
        $this->user = $currentSession;
        $this->rebinding();
    }

    /**
     * @return UserSession
     */
    public function getUserSession(): UserSession
    {
        return $this->user_session;
    }

    /**
     * @param UserSession $user_session
     */
    public function setUserSession(UserSession $user_session): void
    {
        $this->user_session = $user_session;
        $this->rebinding();
    }

    protected function rebinding()
    {
        app()->bind(get_class($this), function ($app) {
            return $this;
        });
    }
}
