<?php

namespace App\Interfaces\Validator;

interface ProductValidatorInterface
{
    public function validateProductData(array $productData): array;
}
