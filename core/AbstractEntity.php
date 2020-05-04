<?php

namespace Core;

abstract class AbstractEntity
{
    private $id;

    public function hydrate(array $data): AbstractEntity
    {
        foreach ($data as $property => $value) {
            $method = 'set'.ucfirst($property);
            if(method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function convertToArray(): array
    {
        $data = get_object_vars($this);
        unset($data['id']);

        return $data;
    }
}
