# Smart Blood Inventory & Temperature Monitoring System

Laravel 11 backend API for blood bag inventory, refrigerator temperature monitoring, alert handling, expiry prediction, and dashboard analytics.

## Tech Stack

- Laravel 11
- PHP 8.2.26
- MySQL
- Laravel Sanctum
- Queues / Jobs
- Notifications
- Form Requests
- API Resources
- Service Classes

## Setup Guide


composer install
cp .env.example .env
php artisan key:generate

## Configure Database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blood_inventory
DB_USERNAME=root
DB_PASSWORD=

## Run Migrations and Seeders
php artisan migrate --seed

## Start the Server
php artisan serve

## Start Queue Worker (Required for Alerts)

php artisan queue:work

## API Base URL: http://127.0.0.1:8000/api

## Test Users

## Role                 Email                 Passwor
   Admin                admin@example.com      password
   Blood Bank Staff     staff@example.com      password
   Monitoring User      monitor@example.com    password

## Authentication
Login to get token:
POST /api/login
Body: { "email": "admin@example.com", "password": "password" }

Use token in all protected requests:
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json

## API Endpoints

Authentication

POST http://127.0.0.1:8000/api/login
GET  http://127.0.0.1:8000/api/me
POST http://127.0.0.1:8000/api/logout


Blood Bags

GET http://127.0.0.1:8000/api/blood-bags    list of all blood bags
POST http://127.0.0.1:8000/api/blood-bags     Create a blood bag
GET http://127.0.0.1:8000/api/blood-bags/{id} Get single blood bag
PUT http://127.0.0.1:8000/api/blood-bags/{id}   Update blood bag
DELETE http://127.0.0.1:8000/api/blood-bags/{id}  Delete blood bag


Blood Bag Filters:

GET /api/blood-bags?blood_group=o-
GET /api/blood-bags?status=available
GET /api/blood-bags?blood_bank_id=1
GET /api/blood-bags?expiring_soon=1

Temperature Monitoring

POST http://127.0.0.1:8000/api/temperature-logs  Add a temperature log
GET http://127.0.0.1:8000/api/refrigerators/{id}/temperature-risk Get risk analysis for date

Analytics

GET http://127.0.0.1:8000/api/inventory/expiry-summary  Blood expiry summary
GET http://127.0.0.1:8000/api/dashboard           Full dashboard analytics

Relationship Demo

GET http://127.0.0.1:8000/api/relationship-demo/blood-banks  Blood banks with nested data

GET http://127.0.0.1:8000/api/relationship-demo/critical-refrigerators  Refrigerators with critical logs

GET http://127.0.0.1:8000/api/relationship-demo/available-stock-blood-banks                       Available stock per bank      


## Queue Setup
The alert system uses Laravel's queue system. If QUEUE_CONNECTION=database in .env,run the queue worker separately
php artisan queue:work
