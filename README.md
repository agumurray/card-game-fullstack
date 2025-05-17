# Juego de cartas - API + Frontend

Un proyecto completo de un juego de cartas en línea, que incluye una API RESTful construida con PHP (Slim Framework) y un frontend interactivo desarrollado en React.

## Requisito Previo

Asegúrate de tener instalado y configurado el siguiente componente en tu sistema:

1. [Docker](https://www.docker.com/products/docker-desktop)

---

## Configuración Inicial

1. Clona el repositorio:

```bash
git clone https://github.com/agumurray/card-game-php.git
cd card-game-php
```

2. Copia el archivo de entorno de ejemplo:

```bash
cp .env.dist .env
```

---

## Uso

### Iniciar la aplicación:

Para iniciar todos los servicios (API, frontend, base de datos y phpMyAdmin):

```bash
docker-compose up -d --build
```

Esto asegurará que se instalen todas las dependencias tanto del backend como del frontend.

* La API estará disponible en: [http://localhost:8080](http://localhost:8080)
* El frontend estará disponible en: [http://localhost:3000](http://localhost:3000)
* La base de datos se puede visualizar mediante phpMyAdmin en: [http://localhost:8081](http://localhost:8081)

> ⚠️ **Importante:** Si se agregan nuevas dependencias o se actualiza `composer.json` o `package.json`, asegúrate de ejecutar `docker-compose up -d --build` nuevamente.

---

### Detener la aplicación (conservando los datos de la base de datos):

```bash
docker-compose down
```

### Detener y eliminar todos los datos de la base de datos (iniciar desde cero):

```bash
docker-compose down -v
```

---

## Esquema de Base de Datos

El archivo [`init.sql`](init.sql) contiene el esquema de base de datos provisto en la práctica.

---

## Dependencias (composer.json / package.json)

Estas son las bibliotecas requeridas por la aplicación y su propósito:

### Backend (PHP):

* **`slim/slim` (^4.14)**: Framework minimalista para construir aplicaciones web y APIs REST con PHP.
* **`slim/psr7` (^1.7)**: Implementación de PSR-7 (HTTP messages) para manejar solicitudes y respuestas en Slim.
* **`php-di/php-di` (^7.0)**: Contenedor de inyección de dependencias para facilitar la gestión de objetos y servicios.
* **`firebase/php-jwt` (^6.11)**: Biblioteca para crear y verificar JSON Web Tokens (JWT), útil para autenticación y autorización.

### Frontend (React):

* **`axios`**: Cliente HTTP para realizar peticiones a la API.
* **`react-bootstrap`**: Componentes de UI basados en Bootstrap.
* **`bootstrap`**: Framework CSS para estilos y diseño responsivo.

---
