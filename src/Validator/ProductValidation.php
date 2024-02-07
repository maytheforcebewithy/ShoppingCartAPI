<?php

namespace App\Validator;

use App\Interfaces\Validator\ProductValidatorInterface;

class ProductValidation implements ProductValidatorInterface
{
    public function validateProductData(array $productData): array
    {
        $errors = [];

        if (empty($productData['name'])) {
            $errors[] = 'Der Name des Produkts fehlt.';
        }

        if (!isset($productData['price']) || $productData['price'] <= 0) {
            $errors[] = 'Der Preis des Produkts muss eine positive Zahl sein.';
        }

        if (!isset($productData['quantity']) || $productData['quantity'] < 0) {
            $errors[] = 'Die Menge des Produkts muss eine positive Zahl sein.';
        }

        return $errors;
    }
}
