<?php

namespace App\Repository;

use Core\AbstractManager;
use App\Entity\Project;

class MessageManager extends AbstractManager
{

    const TABLE_NAME = 'message';
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
