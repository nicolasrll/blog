<?php

namespace Core;

abstract class AbstractEntity
{
    protected int $id;

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

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function convertToArray(): array
    {
        $data = get_object_vars($this);
        unset($data['id']);

        return $data;
    }
}
