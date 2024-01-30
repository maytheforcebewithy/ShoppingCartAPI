<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function getId(): int;
    public function getName(): string;
    public function getPrice(): float;
    public function getQuantity(): int;
    public function setName(string $name): void;
    public function setPrice(float $price): void;
    public function setQuantity(int $quantity): void;
}
