# Technical test Lleego
# Symfony Technical Test

This project is a technical test where we read an XML file and display data both via command line and API. The project utilizes unit tests to ensure the correctness of the implementation.

## Features

- Read and parse XML files
- Display data via command line
- Provide data through an API
- Unit tests for validation

## Requirements

- Symfony
- PHP
- Composer

## Installation

1. Clone the repository
2. Run `composer install`
3. Follow the setup instructions

## Usage

- To run the command line interface, use: `php bin/console lleego:avail MAD BIO 2023-06-01`
- To access the API, navigate to: `/api/avail?origin=MAD&destination=BIO&date=2022-06-01`

## Testing

Run the unit tests with:
```
php bin/phpunit
```