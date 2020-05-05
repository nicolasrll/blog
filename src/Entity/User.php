<?php

namespace App\Entity;

use Core\AbstractEntity;

class User extends AbstractEntity
{
    protected $login = '';
    protected $password = '';
    protected $role = 'user';
    protected $status = 'active';

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login)
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role Must have value admin or user
     */
    public function setRole(string $role)
    {
        $this->role = $role;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status Must have value active or inactive
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }
}
