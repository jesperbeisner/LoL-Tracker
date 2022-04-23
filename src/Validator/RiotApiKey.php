<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class RiotApiKey extends Constraint
{
    public string $wrongStartMessage = "The RIOT-API-KEY needs to start with 'RGAPI-'";
}
