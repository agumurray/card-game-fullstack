# Card Game PHP API

A RESTful API for an online card game built with PHP, Slim Framework, and Docker.

## Prerequisites

Make sure you have the following installed and configured on your system:

1. Docker  
2. PHP with the `zip` extension enabled in `php.ini`

---

## First-Time Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/agumurray/card-game-php.git
   cd card-game-php
   ```

2. Copy the example environment file:

   ```bash
   cp .env.dist .env
   ```

---

## Usage

### Start the application:

```bash
docker-compose up -d
```

### Stop the application (preserve database data):

```bash
docker-compose down
```

### Stop and reset the database (start fresh):

```bash
docker-compose down -v
```
