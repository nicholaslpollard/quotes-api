# Midterm Project - PHP OOP REST Quotes-API
# Creator - Nicholas Pollard
# INF 653 2025S

## Project Overview

This project is a PHP OOP REST API for managing quptes, their authors, and their categories. The API provides endpoints for handling CRUD operations (Create, Read, Update, Delete) for authors, categories, and quotes. It supports a relational database with PostgreSQL for creating it.

## Features
- CRUD Operations for Quotes, Authors, and Categories.
- Support for CORS to allow cross-origin requests.
- Database Relationships using foreign keys between Quotes, Authors, and Categories.
- API Responses in JSON format.
- Tested on Postman.
- Deployed on Render.com with Docker.

---

## Technologies Used

- **PHP (OOP)**: Used for building the REST API and handling business logic.
- **PostgreSQL**: Used as the database for storing Quotes, Authors, and Categories.
- **Docker**: Used to containerize the application for deployment on Render.com.
- **CORS (Cross-Origin Resource Sharing)**: Configured for handling cross-origin requests.
- **Postman**: For testing API endpoints.

---

*Project Structure*
quotes-api/
│
├── api/
│   ├── authors/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── read.php
│   │   ├── read_single.php
│   │   ├── update.php
│   │   └── delete.php
│   ├── categories/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── read.php
│   │   ├── read_single.php
│   │   ├── update.php
│   │   └── delete.php
│   ├── quotes/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── read.php
│   │   ├── read_single.php
│   │   ├── update.php
│   │   └── delete.php
├── config/
│   └── Database.php
├── models/
│   ├── Author.php
│   ├── Category.php
│   └── Quote.php
└── index.php

## Endpoints

### Quotes
- **GET** `/api/quotes/read.php` - Retrieve all quotes.
- **GET** `/api/quotes/read_single.php?id=<quote_id>` - Retrieve a specific quote by its ID.
- **POST** `/api/quotes/create.php` - Create a new quote.
- **PUT** `/api/quotes/update.php` - Update an existing quote.
- **DELETE** `/api/quotes/delete.php?id=<quote_id>` - Delete a specific quote by its ID.

### Authors
- **GET** `/api/authors/read.php` - Retrieve all authors.
- **GET** `/api/authors/read_single.php?id=<author_id>` - Retrieve a specific author by their ID.
- **POST** `/api/authors/create.php` - Create a new author.
- **PUT** `/api/authors/update.php` - Update an existing author.
- **DELETE** `/api/authors/delete.php?id=<author_id>` - Delete a specific author by their ID.

### Categories
- **GET** `/api/categories/read.php` - Retrieve all categories.
- **GET** `/api/categories/read_single.php?id=<category_id>` - Retrieve a specific category by its ID.
- **POST** `/api/categories/create.php` - Create a new category.
- **PUT** `/api/categories/update.php` - Update an existing category.
- **DELETE** `/api/categories/delete.php?id=<category_id>` - Delete a specific category by its ID.

---

## Database Structure
### Tables
#### authors:
- `id` (Primary Key, Auto Increment)
- `author` (NOT NULL)

#### categories:
- `id` (Primary Key, Auto Increment)
- `category` (NOT NULL)

#### quotes:
- `id` (Primary Key, Auto Increment)
- `quote` (NOT NULL)
- `author_id` (Foreign Key from authors table)
- `category_id` (Foreign Key from categories table)

---

## Testing
All API endpoints were tested using Postman and in the browser.

---

- **API URL**: `https://your-deployment-url.onrender.com/api`

