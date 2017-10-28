<?php

/**
 * Created by PhpStorm.
 * User: didierberlioz
 * Date: 29/10/2017
 * Time: 00:48
 */
class UserRenderer
{
    use SingletonTrait;

    private $user;

    public function first_name()
    {
        return ucfirst(mb_strtolower($this->user->firstname));
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}