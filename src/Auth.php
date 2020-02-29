<?php

namespace Nsingularity\GeneralModule\Foundation\Controller\Api;

use Nsingularity\GeneralModule\Foundation\Entities\GeneralUser;

class Auth
{
    /** @var  GeneralUser */
    private $user;

    /** @var  boolean */
    private $remember_me;

    /**
     * @return GeneralUser
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
