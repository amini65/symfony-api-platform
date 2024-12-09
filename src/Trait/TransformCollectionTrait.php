<?php

namespace App\Trait;

trait TransformCollectionTrait
{
    public function transformCollection(iterable $entities): array
    {
        $result = [];
        foreach ($entities as $entity) {
            $result[] = $this->transform($entity);
        }
        return $result;
    }
}