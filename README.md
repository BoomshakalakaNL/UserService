# User Service

This User Service is part of one of serveral microservices created by me. This microservice is written using the Lumen PHP micro-framework. The microservice has several endpoints which can be seen using the command:
```
php artisan route:list
```

## Usage

First you'll need to configure a `.env` file and setup your database. Then run:

```
php artisan migrate
```

This will create the necessary tables for the service. After this make sure everything is working using the included automated tests:
```
phpunit --testdox
```
If no errors occur run a localhost server using the following command:
```
php -S localhost:8000 -t public
```
