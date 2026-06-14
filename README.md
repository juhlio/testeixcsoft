# Laravel Wallet System

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=flat-square&logo=php)
![License](https://img.shields.io/badge/license-MIT-green)

A lightweight payment wallet system built with Laravel, supporting both individual and corporate users with real-time transaction processing and external service validation.

## Features

- **User Management** - Register individual (PF) and corporate (PJ) accounts
- **Wallet System** - Each user starts with R$ 1,000.00 credit
- **Money Transfers** - Secure peer-to-peer transactions between users
- **External Validation** - Integration with external services for transaction approval
- **Queue Processing** - Asynchronous notification system using Laravel Queues
- **Role-Based Access** - Different permissions for individual and corporate users

## Tech Stack

- **Framework**: Laravel 11.x
- **Language**: PHP 8.0+
- **Database**: MySQL
- **Frontend**: Blade Templates with Tailwind CSS
- **Authentication**: Laravel Sanctum
- **Queue**: Redis/Database Driver

## Quick Start

### Prerequisites

- PHP >= 8.0
- Composer
- MySQL 5.7+

### Installation

```bash
git clone https://github.com/juhlio/laravel-wallet-system.git
cd laravel-wallet-system

composer install
npm install
npm run build
```

### Configuration

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Usage

### Test Credentials

**Individual User (PF)**
- Email: `joao.silva@example.com`
- Password: `senha123`

**Corporate User (PJ)**
- Email: `empresateste@example.com`  
- Password: `senha123`

### Making a Transfer

1. Login to your account
2. Navigate to `/transfer`
3. Enter recipient document (CPF/CNPJ)
4. Specify amount
5. Confirm transaction

Test Documents:
- CPF: `123.456.789-00`
- CNPJ: `12.345.678/0001-99`

## Architecture

### Controllers

- **RegisterController** - User registration and validation
- **DashboardController** - User dashboard and balance display
- **TransactionController** - Payment processing and transfers

### Key Features

- CPF/CNPJ validation
- Balance verification before transfers
- External service consultation for fraud prevention
- Queue-based notifications

## Requirements

- Only individual users (PF) can send transfers
- Any user type can receive transfers
- Cannot transfer more than available balance
- Unique CPF/CNPJ and email per user

## License

MIT License - see LICENSE file for details

## Author

[@julio](https://github.com/juhlio) - PHP Developer
