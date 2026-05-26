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

### 1. Install Dependencies

```bash
composer install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database in `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blood_inventory
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

### 5. Start the Server

```bash
php artisan serve
```

### 6. Start Queue Worker (Required for Alerts)

```bash
php artisan queue:work
```

**API Base URL:** `http://127.0.0.1:8000/api`

---

## Test Users

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Blood Bank Staff | staff@example.com | password |
| Monitoring User | monitor@example.com | password |

---

## Authentication

Login to get token:

```
POST /api/login
Body: { "email": "admin@example.com", "password": "password" }
```

Use token in all protected requests:

```
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
```

---

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/login | Login and get token |
| GET | /api/me | Get current user |
| POST | /api/logout | Logout current device |

### Blood Bags

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/blood-bags | List all blood bags |
| POST | /api/blood-bags | Create a blood bag |
| GET | /api/blood-bags/{id} | Get single blood bag |
| PUT | /api/blood-bags/{id} | Update blood bag |
| DELETE | /api/blood-bags/{id} | Delete blood bag |

### Blood Bag Filters

```
GET /api/blood-bags?blood_group=O-
GET /api/blood-bags?status=available
GET /api/blood-bags?blood_bank_id=1
GET /api/blood-bags?expiring_soon=1
```

### Temperature Monitoring

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/temperature-logs | Add a temperature log |
| GET | /api/refrigerators/{id}/temperature-risk | Get risk analysis for date |

### Analytics

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/inventory/expiry-summary | Blood expiry summary |
| GET | /api/dashboard | Full dashboard analytics |

### Relationship Demo

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/relationship-demo/blood-banks | Blood banks with nested data |
| GET | /api/relationship-demo/critical-refrigerators | Refrigerators with critical logs |
| GET | /api/relationship-demo/available-stock-blood-banks | Available stock per bank |

---

## Queue Setup

The alert system uses Laravel's queue system. If `QUEUE_CONNECTION=database` in `.env`, run the queue worker separately:

```bash
php artisan queue:work
```

This is required for critical temperature alerts to be processed and stored automatically.

---

## Submission Files

- GitHub Repository — full source code
- `blood_inventory.sql` — Database SQL dump
- `Blood Inventory API.postman_collection.json` — Postman collection
- `README.md` — Setup and documentation guide
- `ARCHITECTURE.md` — Architecture explanation
