# pickpocket-symfony

## Initial Setup

make sure you have an empty database named `pickpocket`.

run the following command for doctrine to run the migrations and create the schema
```bash
php bin/console doctrine:migrations:migrate
```

to populate the database run the product extraction script (make sure to change the .env variables to mention your newly created db `pickpocket`)

## Running the Server

you can run the server with
```
symfony server:start
```
