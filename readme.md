
## About:

This is a demo for Multi Auth boilerplate Laravel app (versions 5.6, 5.7, 5.8). 

## Features:

- User & Admin clients with their home/dashboard pages.
- Admins can have different roles (used [Spatie's permission package](https://github.com/spatie/laravel-permission)).
- AdminLTE customized template for Admin Panel.
- Manageable Admin URI-segment path.

## Demo Test:

```shell
$ git clone https://github.com/boolfalse/laravel-multiauth.git
$ cd laravel-multiauth/
```
Setup a DB and .env
```shell
DEV_NAME="Test"
DEV_EMAIL="test@gmail.com"
DEV_PASSWORD="secret"
ADMIN_PREFIX="admin"
```
In the root of you project run:
```shell
$ composer install
$ php artisan key:generate
$ php artisan storage:link
$ php artisan clearcaches
$ php artisan cleanuploads
$ php artisan droptables
$ php artisan migrate
$ php artisan db:seed
```
Now you can check Multi Auth system:
* for Users: '/login', '/register'
* for Admins: '<ADMIN_PREFIX>/login'
