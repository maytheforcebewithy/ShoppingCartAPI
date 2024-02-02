<?php

use App\DataFixtures\AppFixtures;

require_once 'vendor/autoload.php';

$pdo = new PDO('pgsql:host=test_database;dbname=test_db_name', 'test_db_user', 'test_db_password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$appFixtures = new AppFixtures($pdo);
$appFixtures->load();

echo "Fixtures wurden erfolgreich geladen.\n";
