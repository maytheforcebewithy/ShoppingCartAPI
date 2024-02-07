<?php

namespace App\Interfaces\Validator;

interface ProductValidatorInterface
{
    public function validateProductData($productData): array;
}
