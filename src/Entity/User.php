<?php

namespace App\Entity;

use Core\AbstractEntity;
use Exception;

class User extends AbstractEntity
{
    protected const ADMIN = 'admin';
    protected const USER = 'user';
    protected string $login = '';
    protected string $password = '';
    protected string $role = self::USER;
    protected bool $active = false;

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
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
    public function setRole(string $role): self
    {
        $existingRole = [self::ADMIN, self::USER];

        if (!in_array($role, $existingRole)) {
            throw new Exception('Le rôle ' . $role . ' saisie n\'existe pas ou n\'est pas valide');
        }

        $this->role = $role;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $isActive Must have value true or false
     */
    public function setisActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
