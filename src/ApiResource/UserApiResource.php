<?php

namespace App\ApiResource;

use App\Entity\User;
use App\Trait\TransformCollectionTrait;

class UserApiResource implements EntityApiResourceInterface
{

    use TransformCollectionTrait;
    public function transform($entity): array
    {
        if (!$entity instanceof User) {
            throw new \InvalidArgumentException('Expected a User entity.');
        }

        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'email' => $entity->getEmail(),
            'role' => $entity->getRole(),
            'company' => $entity->getCompany()?->getName(),

        ];
    }

}