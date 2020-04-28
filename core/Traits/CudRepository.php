<?php

namespace Core\Traits;

use Core\AbstractEntity;

trait CudRepository
{
    public function insert(AbstractEntity $entity): string
    {
        $properties = $entity->convertToArray();
        $columns = array_keys($properties);
        $columns = implode(',', $columns);
        $values = array_values($properties);
        $markers = array_fill(1, count($properties), '?');
        $markers = implode(',', $markers);

        $tableName = $this->getTableName();
        //$pdo = PdoConnect::getInstance();
        $pdo = $this->getPdo();
        $sql = 'INSERT INTO ' . $this->getTableName() . '('.$columns . ') VALUES (' . $markers . ')';
        $stmt = $pdo->prepare($sql);
        //$pdo->beginTransaction();
        $stmt->execute($values);

        return $pdo->lastInsertId();
    }

    public function update(AbstractEntity $entity): bool
    {
        $id = $entity->getId();
        $properties = $entity->convertToArray();
        $keys = array_keys($properties);
        $values = array_values($properties);
        $columns = '';
        foreach ($keys as $key) {
           $columns .= ' ' . $key . ' = ?,';
        }
        $columns = trim($columns, ',');

        $pdo = $this->getPdo();
        //$sql = 'UPDATE ' . $tableName . ' SET ' . $columns . ' WHERE id = ' . $id;
        $sql = 'UPDATE ' . $this->getTableName() . ' SET ' . $columns . ' WHERE id = ' . $id;
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($values);
    }

    public function delete(int $id): bool
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare('DELETE FROM ' . $this->getTableName() .' WHERE ' . $this->getTablePk() . '= ?');

        return $stmt->execute([$id]);
    }
}
