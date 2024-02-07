<?php

namespace App\Interfaces\Validator;

interface UserValidatorInterface
{
    public function validateUser(array $user): array;
}
