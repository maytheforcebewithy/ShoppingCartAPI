<?php

use App\DataFixtures\AppFixtures;

require_once 'vendor/autoload.php';

$pdo = new PDO($_ENV['DATABASE_URL'], $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$appFixtures = new AppFixtures($pdo);
$appFixtures->load();

echo "Fixtures wurden erfolgreich geladen.\n";
