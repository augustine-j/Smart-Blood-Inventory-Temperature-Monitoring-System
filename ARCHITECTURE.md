

# Architecture Explanation

## Overview

This Laravel 11 backend API manages blood bags, blood banks, refrigerators, temperature logs, expiry prediction, critical alerts, and dashboard analytics.

## Database Tables

- users
- blood_banks
- blood_bank_user
- refrigerators
- blood_bags
- temperature_logs
- temperature_alerts
- notifications

## Relationships

- BloodBank hasMany Refrigerators
- BloodBank belongsToMany Users
- Refrigerator belongsTo BloodBank
- Refrigerator hasMany BloodBags
- Refrigerator hasMany TemperatureLogs
- Refrigerator hasMany TemperatureAlerts
- BloodBag belongsTo Refrigerator
- TemperatureLog belongsTo Refrigerator
- TemperatureAlert belongsTo Refrigerator
- User belongsToMany BloodBanks

## Authentication and Authorization

Laravel Sanctum is used for token-based authentication.

Roles:

- admin
- staff
- monitoring

A custom role middleware protects APIs based on user role.

## Advanced Laravel Concepts Used

- Sanctum authentication
- Custom middleware
- Form Requests
- API Resources
- Service Classes
- Jobs
- Notifications
- Eloquent relationships
- Eager loading
- Nested relationships
- whereHas filtering
- withCount aggregation
- Accessors and mutators

## Temperature Logic

Temperature status rules:

- 2°C to 6°C: safe
- Above 6°C to 8°C: warning
- Above 8°C: critical

Risk formula = Risk Percentage = (Unsafe Minutes / Total Minutes) × 100