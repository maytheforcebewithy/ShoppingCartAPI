<?php

namespace App\Validator;

class ProductValidation
{
    public function validateProductData($productData)
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
