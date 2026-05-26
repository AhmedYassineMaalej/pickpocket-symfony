# pickpocket-symfony

## Initial Setup

make sure you have an empty database named `pickpocket`.

make sure you configure the `.env` file following the `.env.example` example file.

run the following command in the root directory for the first full setup
```
composer install
```

run the following command for doctrine to run the migrations and create the schema
```bash
php bin/console doctrine:migrations:migrate
```
then
```
composer dev
```
To see the wesite, open the browser on 
```
http://127.0.0.1:8000/
```
## Running the Server

For the next times, you can run the server with
```
symfony server:start
```
For fresher data you can rerun
```
composer dev
```
