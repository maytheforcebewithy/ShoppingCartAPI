#!/bin/bash

until nc -z -v -w30 database 5432
do
    echo "Waiting for database connection..."
    sleep 5
done
echo "Database is up and running!"

echo "Database initialization completed!"

exec "$@"
