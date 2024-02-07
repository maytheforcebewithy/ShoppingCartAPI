<?php

namespace App\Validator;

use App\Interfaces\Validator\UserValidatorInterface;

class UserValidator implements UserValidatorInterface
{
    public function validateUser(array $user): array
    {
        $errors = [];

        if (empty($user['username'])) {
            $errors[] = 'Der Name darf nicht leer sein.';
        }

        if (empty($user['email'])) {
            $errors[] = 'Die E-Mail-Adresse darf nicht leer sein.';
        }

        return $errors;
    }
}
