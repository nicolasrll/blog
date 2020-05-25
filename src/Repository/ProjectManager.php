<?php

namespace App\Repository;

use Core\AbstractManager;
use App\Entity\Project;

class ProjectManager extends AbstractManager
{

    const TABLE_NAME = 'project';
    const TABLE_PK = 'id';

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getTablePk(): string
    {
        return self::TABLE_PK;
    }
}
