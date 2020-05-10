<?php

namespace App\Entity;

use Core\AbstractEntity;
use Exception;

class User extends AbstractEntity
{
    protected $login = '';
    protected $password = '';
    protected $role = '';
    protected $status = 'active';
    const ADMIN = 'admin';
    const USER = 'user';
    const ENABLE = 'enable';
    const DISABLE = 'disable';

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
        $existingRole = [self::ADMIN, self::USER];

        if (!in_array($role, $existingRole)) {
            throw new Exception('La valeur passé n\'est pas valide');
        }

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
        $existingStatus = [self::ENABLE, self::DISABLE];

        if (!in_array($status, $existingStatus)) {
            throw new Exception('La valeur passé n\'est pas valide');
        }

        $this->status = $status;

        return $this;
    }
}
