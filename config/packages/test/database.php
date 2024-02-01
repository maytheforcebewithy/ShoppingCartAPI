<?php

return [
    'dsn' => 'pgsql:host=test_database;dbname=test_db_name',
    'username' => 'test_db_user',
    'password' => 'test_db_password',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
