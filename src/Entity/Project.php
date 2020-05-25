<?php

namespace App\Entity;

use Core\AbstractEntity;
use Exception;

class Project extends AbstractEntity
{
    protected int $userId;
    protected string $title = '';
    protected string $chapo = '';
    protected string $content = '';
    protected string $author = '';
    protected string $dateUpdated = '';
    protected string $linkToProject = '';
<<<<<<< HEAD
    protected string $linkToPicture = '';
=======
>>>>>>> feat #4 : project controller, entity and manager created with its templates project list, project and add project. And CSRF implemented.

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getChapo(): string
    {
        return $this->chapo;
    }

    public function setChapo(string $chapo): self
    {
        $this->chapo = $chapo;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDateUpdated(): string
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(string $dateUpdated): self
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    public function getLinkToProject(): string
    {
        return $this->linkToProject;
    }

    public function setLinkToProject(string $linkToProject)
    {
        $this->linkToProject = $linkToProject;

        return $this;
    }
<<<<<<< HEAD

    public function getlinkToPicture(): string
    {
        return $this->linkToPicture;
    }

    public function setlinkToPicture(string $linkToPicture)
    {
        $this->linkToPicture = $linkToPicture;

        return $this;
    }
=======
>>>>>>> feat #4 : project controller, entity and manager created with its templates project list, project and add project. And CSRF implemented.
}
