<?php

return [
    'dsn' => 'pgsql:host=database;dbname=prod_db_name',
    'username' => 'prod_db_user',
    'password' => 'prod_db_password',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
