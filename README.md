# Shipping API


## Introduction

This Shipping API is built to demonstrate basic CRUD operations, authentication, and API functionalities for a shipment company

## Requirements

- PHP >= 8.2
- Composer
- MySQL
- Redis
- Docker (optional, for Dockerized deployment)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/jgodstime/shipment-api

2. Navigate to the project directory:
   ```bash
   cd shipment-api

3. Install PHP dependencies:
   ```bash
   composer install

4. Copy the .env.example file and configure your environment variables:
   ```bash
   cp .env.example .env

5. Generate application key:
   ```bash
   php artisan key:generate

6. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed```

Note: The seeder


## Configuration

- Update the .env file with your database connection details and other configuration options.

  Dont forget to set the SHIPMENT_RATE_PER_KG= 

## Usage

- Start the Laravel development server with the command below and visit http://127.0.0.1:8000
   ```bash
   php artisan serve

## Note

- Login credentials for Admin 

``` js
{
    "email": admin@gmail.com
    "password": password
}


