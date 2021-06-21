
#Tech Stack
- Laravel
- Mysql


#How to run:

- composer install
- Run `cp .env.example .env`
- Run `vendor/bin/sail up -d`

Once container are up and running : 

- Run `docker-compose exec laravel.test php artisan migrate`
- visit `http://localhost/init/data` ( this should generate the test admin credential, which can be use for POC purpose with the followin detail, [ u: test@example.com | pw : 12345678 ])




# Additional info
- Run unit test `php artisan test`
