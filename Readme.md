# Phonebook REST API Example

## Installation

```
git clone https://github.com/bukachuk/phonebook.git && cd phonebook
```

Create new database
```
mysqladmin create phonebook
```
Install the required dependencies
```
composer install
```
Set database configuration
```
vi config/config.php
```
Create database schema
```
/usr/bin/php vendor/bin/doctrine orm:schema-tool:create
```
Run tests
```
phpunit
```
Run php development server
```
php -S 127.0.0.1:8000
```

## How to use API
List all phone, 100 per page
```
GET http://127.0.0.1:8000/phonebook
```
List all phone, 100 per page with offset 100
```
GET http://127.0.0.1:8000/phonebook?offset=100
```
List all phone, 10 per page with offset 10
```
GET http://127.0.0.1:8000/phonebook?offset=10&limit=10
```
List all phone, 10 per page with offset 10 and firstName LIKE Mikhail%
```
GET http://127.0.0.1:8000/phonebook?offset=10&limit=10&firstName=Mikhail
```
List phone ID = 1
```
GET http://127.0.0.1:8000/phonebook/1
```
Create new phone
```
POST http://127.0.0.1:8000/phonebook
id=1
firstName=Mikhail
lastName=Bukachuk
phone=+79083125328
countryCode=RU
timeZone=Europe/Moscow
```
Update phone ID = 1
```
PUT http://127.0.0.1:8000/phonebook
id=1
firstName=Igor
```
Delete phone ID = 1
```
DELETE http://127.0.0.1:8000/phonebook
id=1
```


