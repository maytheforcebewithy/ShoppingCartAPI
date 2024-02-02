Docker build: docker-compose up --build   
Docker up: docker-compose up

Mit der Prod Datenbank verbinden: docker exec -it shoppingcartapi-database-1 psql -U prod_db_user prod_db_name

Mit der Test Datenbank verbinden: docker exec -it shoppingcartapi-test_database-1 psql -U test_db_user test_db_name

Mit dem PHP Container verbinden: docker exec -it shoppingcartapi-php-1 bash

    Test durchführen (Fixtures werden automatisch geladen): vendor/bin/phpunit

    Mockdaten manuell einspielen: /usr/local/bin/php load_fixtures.php