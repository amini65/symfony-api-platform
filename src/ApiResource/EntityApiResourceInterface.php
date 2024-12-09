<?php

namespace App\ApiResource;

interface EntityApiResourceInterface
{

    public function transform($entity): array;

}