<?php

namespace App\Validator;

class UserValidator
{
    public function validateUser($user)
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
