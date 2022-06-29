<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionService
{
    public $session;

    public function __construct()
    {
        $session = new Session();
        if(!empty($session->isStarted())){
            $session->start();
        }
        $this->session = $session;
    }

    public function getTestId(): string
    {
        return ($this->session->get("testId")) ?? $this->setNewId();
    }


    public function setNewId(): string
    {
        $str = bin2hex(random_bytes(5))."-".time();

        $this->session->set("testId", $str);
        return $str;
    }

}
