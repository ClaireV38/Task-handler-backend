# Laravel Task Handler Backend

This is the **backend part of a Task Handler web application**, built with **Laravel** and **Docker**. It exposes a **REST API** for managing tasks and user authentication, ready to be consumed by a frontend application (React, Vue, Angular, etc.).

---

## Features

- User registration and login (token-based authentication via Sanctum)  
- CRUD operations for tasks (create, read, update, delete)  
- Dockerized development environment (PHP 8.3 + Apache + MySQL)  
- Ready for local development or deployment  

---

## Prerequisites

- Docker & Docker Compose installed on your machine  
- Optional: Postman or any REST client to test the API  

---

## Getting Started

### 1. Clone the repository
```bash
git clone https://github.com/ClaireV38/Task-handler-backend.git
cd Task-handler-backend
```
### 2. Clone the repository
```bash
docker compose up -d --build
```
### 3. install Laravel dependencies (if not done yet)
```bash
docker compose run --rm app composer install
```
### 4. Copy .env file
```bash
docker compose run --rm app cp .env.example .env
```
### 5. Generate Laravel application key
```bash
docker compose run --rm app php artisan key:generate
```
### 6. Run migrations
```bash
docker compose exec app php artisan migrate
```
### 7. Access the application

- API will be available at: [http://localhost:8000](http://localhost:8000)  
- MySQL is accessible via the `db` container if needed

