<?php

namespace App\Entity;

use Core\AbstractEntity;
use Exception;

class Message extends AbstractEntity
{
    protected string $fullname = '';
    protected string $email = '';
    protected string $message = '';
    protected string $sentOn = ''; // sending date


    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSentOn(): string
    {
        return $this->sentOn;
    }

    public function setSentOn(string $sentOn): self
    {
        $this->sentOn = $sentOn;

        return $this;
    }
}
