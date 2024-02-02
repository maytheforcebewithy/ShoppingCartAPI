# ShoppingCartAPI Let's Code Kira Reitz

## Inhaltsverzeichnis
1. Aufbau
2. Docker
3. Mit der Prod Datenbank verbinden
4. Mit der Test Datenbank verbinden
5. Mit dem PHP Container verbinden
    1. Testing
    2. Manuelle Mockdaten

## Aufbau
Es gibt zwei Datenbanken, eine Prod- und eine Testdatenbank und einen PHP Container.

Die Prod-Datenbank hat zwar Tabellen, jedoch keine Inhalte, da dieser Teil in diesem Übungsprojekt nicht nachgebildet wird. Die Testdatenbank hat Tabellen, die mit jedem Testdurchgang mit frischen Fixtures befüllt werden. So können alle Elemente des Systems getestet werden.

## Docker
Docker build: docker-compose up --build   
Docker up: docker-compose up

## Mit der Prod Datenbank verbinden:
docker exec -it shoppingcartapi-database-1 psql -U prod_db_user prod_db_name

## Mit der Test Datenbank verbinden:
docker exec -it shoppingcartapi-test_database-1 psql -U test_db_user test_db_name

## Mit dem PHP Container verbinden:
docker exec -it shoppingcartapi-php-1 bash

### Test durchführen: 
* vendor/bin/phpunit

Die Fixtures werden vor jedem Testdurchgang automatisch neu eingespielt.

### Mockdaten manuell einspielen:
* /usr/local/bin/php load_fixtures.php