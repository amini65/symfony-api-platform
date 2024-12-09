<?php

namespace App\ApiResource;

use App\Entity\Company;
use App\Trait\TransformCollectionTrait;

class CompanyApiResource implements EntityApiResourceInterface
{
    use TransformCollectionTrait;
    public function transform($entity): array
    {
        if (!$entity instanceof Company) {
            throw new \InvalidArgumentException('Expected a Company entity.');
        }

        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
        ];
    }

}