<?php

namespace Core;

use Pdo;
use Core\Traits\CudRepository;
use Core\Traits\SearchRepository;

abstract class AbstractManager
{
     use CudRepository;
     use SearchRepository;

    abstract public function getTableName();
    abstract public function getTablePk();

    public function getPdo(): Pdo
    {
        return PdoConnect::getInstance();
    }
}
