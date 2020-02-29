<?php

namespace Nsingularity\GeneralModul\Foundation\Controller\Api;

use Nsingularity\GeneralModul\Foundation\Entities\User;

class Auth
{
    /** @var  User */
    private $user;

    /** @var  boolean */
    private $remember_me;

    /**
     * @return User
     */
    public function getUser()
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
     * @return bool
     */
    public function isRememberMe()
    {
        return (bool)$this->remember_me;
    }

    /**
     * @param bool $remember_me
     */
    public function setRememberMe(bool $remember_me)
    {
        $this->remember_me = $remember_me;
        $this->rebinding();
    }

    protected function rebinding()
    {
        app()->bind(get_class($this), function ($app) {
            return $this;
        });
    }
}
