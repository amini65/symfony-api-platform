# Symfony API Platform Project

## Overview
This project is a RESTful API for managing Users and Companies with role-based access control, built using Symfony 6.3 and API Platform.

## Features
- Role-based access control:
    - `ROLE_USER`
    - `ROLE_COMPANY_ADMIN`
    - `ROLE_SUPER_ADMIN`
- Validation with meaningful error messages.
- Comprehensive testing.

## Setup
1. Clone the repository:
   ```bash
   git clone <repository_url>
   cd symfony-api-project

2. Install dependencies:
   ```bash
   composer install

3. Configure the .env file for your PostgreSQL database.

4. Run database migrations:
   ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate

5. Load fixtures:
   ```bash
   php bin/console doctrine:fixtures:load

6. Start the server:
   ```bash
   symfony server:start


## Tests
Load test fixtures:
   ```bash
   php bin/console --env=test doctrine:fixtures:load
   
Run tests with:
   ```bash
   php bin/phpunit
