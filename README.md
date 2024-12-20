### Overview
This project demonstrates the implementation of a CSV file importer for product data with validation, error handling, and data parsing. It ensures data integrity while importing records into a database or processing them in a test environment.

### Tools and Technologies Used
- **Framework**: Laravel 10.x
- **Parser**: Custom CSV file parser
- **Unit Testing**: PHPUnit with Laravel testing utilities.
- **Database**: MySQL with migrations and transactions for safe imports.

### Installation
1. Clone the repository: 
```
git clone ...
```
2. Run make or docker command:
```
make build-up

docker-compose -f docker-compose.yml up -d --force-recreate --build --remove-orphans
```
3. Import database:
```
docker-compose cp .docker/mysql/make_database.sql mysql:/tmp/
docker-compose exec mysql mysql -u root -p <database_name> < /tmp/make_database.sql
```
4. Run migrations: 
```
docker-compose exec app php artisan migrate
```
5. Run import command:
```
docker-compose exec app php artisan import:products <filepath> --test
```
