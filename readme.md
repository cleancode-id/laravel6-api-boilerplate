# Laravel 6 (LTS) Backend API Boilerplate

[![Build Status](https://travis-ci.org/cleancode-id/laravel6-api-boilerplate.svg?branch=master)](https://travis-ci.org/cleancode-id/laravel6-api-boilerplate)
[![StyleCI](https://github.styleci.io/repos/206861599/shield?branch=master)](https://github.styleci.io/repos/206861599)
[![Latest Stable Version](https://poser.pugx.org/cleancode-id/laravel6-api-boilerplate/v/stable)](https://packagist.org/packages/cleancode-id/laravel6-api-boilerplate)

> A Laravel 6 (LTS) Backend API starter project kit template/boilerplate.

<p align="center">
<img src="https://i.imgur.com/Q42XOKH.png">
</p>

<p align="center">
<img src="https://i.imgur.com/Pc2f4PG.png">
</p>

## Features

- Laravel 6 (latest v6.5) (Long-term support/LTS)
- Optimized for API Backend (without UI/views)
- Frontend Vue.js starter kit ready https://github.com/cleancode-id/laravel6-frontend-boilerplate 
- Authentication with JWT
- Basic Features: Register, Login, Forgot Password, Update Profile & Password
- Unit & Feature Test
- Standard Coding Style & Clean Code
- Role & Permission (To Do)
- Authorization & Policies (To Do)

## Installation

- Run `composer create-project --prefer-dist cleancode-id/laravel6-api-boilerplate`
- Edit `.env` and set your database connection details
- Run `php artisan key:generate` and `php artisan jwt:secret`
- Run `php artisan migrate:fresh --seed`

## Usage
- Postman API Documentation Starter Kit https://documenter.getpostman.com/view/25676/SVfWN6KH
- Run Unit & Feature Test `php vendor/bin/phpunit`

## Credits
- Inspired from https://github.com/cretueusebiu/laravel-vue-spa
