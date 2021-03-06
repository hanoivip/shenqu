<?php

namespace Hanoivip\Shenqu;

interface IShenquAuthen
{
    /**
     *
     * @param string $username
     * @param string $password
     * @return string|NULL Token string
     */
    public function authen($username, $password);
    /**
     *
     * @param string $token
     * @return array
     */
    public function getUserByToken($token);
}