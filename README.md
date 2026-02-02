# PHP_Laravel12_Deploye_To_server

Deployment to Servers and Continuous Integration – Laravel 12 Project

This project demonstrates how to deploy a **Laravel 12** application to a live server and implement **Continuous Integration (CI)** using GitHub Actions. It is a beginner‑friendly **DevOps + Laravel learning project** under the Excelsior‑Technologies‑Community.

---

## Project Objective

The goal of this project is to help developers understand:

* Laravel 12 project setup
* Git and GitHub workflow
* Server deployment process
* Nginx configuration
* Continuous Integration using GitHub Actions
* Basic DevOps practices

---

## Tech Stack

* PHP 8.2+
* Laravel 12
* MySQL
* Composer
* Node.js and NPM
* Git and GitHub
* Nginx or Apache
* Ubuntu VPS or Shared Hosting
* GitHub Actions (CI)

---

## Features

* Laravel 12 basic setup
* Simple home page route
* Database configuration
* GitHub repository integration
* Server deployment guide
* Nginx configuration example
* Continuous Integration pipeline
* Production‑ready project structure

---

## Installation Guide (Local Setup)

### 1. Clone Repository

```bash
git clone https://github.com/Excelsior-Technologies-Community/PHP_Laravel12_Deploye_To_Server.git
cd PHP_Laravel12_Deploye_To_Server
```

### 2. Install Dependencies

```bash
composer install
npm install
npm run build
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update database credentials in `.env`:

```
DB_DATABASE=deploy_demo
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migration

```bash
php artisan migrate
```

### 5. Run Server

```bash
php artisan serve
```

Open browser:

```
http://127.0.0.1:8000
```

---

## Project Structure

```
PHP_Laravel12_Deploye_To_Server
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .github/workflows/
├── .env.example
├── composer.json
└── README.md
```

---

## Basic Route Example

`routes/web.php`

```php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
```

---

## Server Deployment Guide (Ubuntu VPS Example)

### 1. Connect to Server

```bash
ssh root@your_server_ip
```

### 2. Install Required Packages

```bash
sudo apt update
sudo apt install php php-mysql php-cli unzip nginx git composer -y
```

### 3. Clone Project

```bash
cd /var/www
git clone https://github.com/Excelsior-Technologies-Community/PHP_Laravel12_Deploye_To_Server.git
cd PHP_Laravel12_Deploye_To_Server
```

### 4. Install Dependencies

```bash
composer install
npm install
npm run build
```

### 5. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 6. Set Permissions

```bash
sudo chmod -R 775 storage bootstrap/cache
```

### 7. Run Migration

```bash
php artisan migrate
```
<img width="1421" height="50" alt="image" src="https://github.com/user-attachments/assets/0a0fe201-793d-4fa6-a45b-98b3c0662b58" />

---

## Nginx Configuration Example

Create configuration file:

```bash
sudo nano /etc/nginx/sites-available/laravel
```

Paste the following configuration:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/PHP_Laravel12_Deploye_To_Server/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }
}
```

Enable and restart Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

---

## Continuous Integration (GitHub Actions)

Create folder:

```
.github/workflows
```

Create file:

```
ci.yml
```

`ci.yml` example:

```yaml
name: Laravel CI

on:
  push:
    branches: [ "main" ]

jobs:
  build:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout Code
        uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2

      - name: Install Dependencies
        run: composer install --no-progress

      - name: Copy Env
        run: cp .env.example .env

      - name: Generate Key
        run: php artisan key:generate

      - name: Run Tests
        run: php artisan test
```

This workflow automatically runs CI on every push to the **main** branch.

---

## Optional Enhancements

* Docker Deployment
* Auto Deploy via SSH
* SSL Certificate using Let’s Encrypt
* Redis Caching
* Queue Workers
* Monitoring Tools

---

## Learning Outcomes

After completing this project, you will understand:

* Laravel deployment workflow
* GitHub version control
* VPS server setup
* Continuous Integration basics
* Production environment configuration
* Real‑world DevOps introduction

---

## Use Case

* Portfolio Project
* DevOps Practice
* Laravel Deployment Training
* Community Learning Resource

---

## Maintained By

Excelsior Technologies Community

---

## License

This project is open‑source and free to use for learning and development purposes.
