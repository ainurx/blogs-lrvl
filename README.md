Please run the following command for app configuration & running it in local environment

### ENV setup
create .env file and copy paste key value from .env.example to it

### Create database
create MySQL database with name **blogs_lrvl**

### Install Laravel dependencies
`composer install`

### Database migration
`php artisan migrate`

### Database seeding
`php artisan db:seed`

### Run the application
`php artisan serve`