# Juego de cartas - API + Frontend

Un proyecto completo de un juego de cartas en línea, que incluye una API RESTful construida con PHP (Slim Framework) y un frontend interactivo desarrollado en React.

---

## Requisito Previo

Asegúrate de tener instalado y configurado el siguiente componente en tu sistema:

1. [Docker](https://www.docker.com/products/docker-desktop)

---

## Configuración Inicial

1. Clona el repositorio:
 
```bash
git clone https://github.com/agumurray/card-game-php.git
cd card-game-php
````

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
* Podés usar esta [colección de Postman](https://agustin-7610866.postman.co/workspace/Agustin's-Workspace~410d3a08-beda-4a71-8e6a-fc78d13e900c/collection/43658514-79d729d0-10a9-429b-83d5-e2097f882833?action=share&creator=43658514&active-environment=43658514-925d8b7a-8a96-4b2e-a068-d062fdada82e) para probar fácilmente los endpoints disponibles (registro, login, partidas, jugadas, etc.).

> ⚠️ **Importante:** Si se agregan nuevas dependencias o se actualiza `composer.json`, `package.json` o `package-lock.json`, asegurate de reconstruir la imagen afectada con:

```bash
docker-compose build frontend   # o `app` para el backend
docker-compose up -d
```

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

El archivo [`init.sql`](api/init.sql) contiene el esquema de base de datos provisto en la práctica.

---

## Dependencias

### Backend (PHP - Slim)

* **`slim/slim`**: Framework minimalista para APIs REST.
* **`slim/psr7`**: Implementación de PSR-7 para manejar solicitudes HTTP.
* **`php-di/php-di`**: Inyección de dependencias.
* **`firebase/php-jwt`**: Manejo de autenticación con JSON Web Tokens.

### Frontend (React)

* **`axios`**: Cliente HTTP.
* **`react-bootstrap`**: Componentes visuales basados en Bootstrap.
* **`bootstrap`**: Framework de estilos CSS.

---