<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 03/01/2022 at 10:47
*@Class: Csrf
*@NameSpace: Core
*/

namespace Core;

use DateTime;

class Csrf
{
    private string $token;
    private  $expireAt;

    public function __construct()
    {
        $this->token = uniqid(token(), true);
        $now = time();
        $this->expireAt = $now +(60*5);
        $_SESSION['csrf'][] = $this;
    }



    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return float|int
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

}