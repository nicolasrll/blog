<?php

namespace Core\Traits;

use Core\AbstractEntity;
use Pdo;

trait SearchRepository
{

    public function findOneById(int $id): ?AbstractEntity
    {
        return $this->findOne([
            $this->getTablePk() => $id
        ]);
    }

    public function findOne(array $filters = [])
    {
        $data = $this->find($filters);

        return !empty($data) ? reset($data) : null ;
    }

    // Ex function findAll()
    /**
     * [find description]
     * @param  array  $filters [description]
     * @return [array AbstractEntity]
     */
    public function find(array $filters = []): array
    {
        $pdo = $this->getPdo();
        $tableName = $this->getTableName();
        $requestSql = 'SELECT * FROM ' . $tableName;
        $values = [];

        // if not empty then search function
        if (!empty($filters)) {
            $keys = array_keys($filters);
            $values = array_values($filters);
            $requestSql .= ' WHERE 1=1 ';
            foreach ($keys as $key) {
                $requestSql .= ' AND ' . $key . ' = ?';
            }
        }
        $stmt = $pdo->prepare($requestSql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'App\Entity\\'. ucfirst($tableName));
        $stmt->execute($values);

        return $stmt->fetchAll();
    }
}
