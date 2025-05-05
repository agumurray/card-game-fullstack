> ⚠️ **Nota**: Si bien este proyecto está subido en un repositorio de IDEAS, recomendamos abrir directamente el repositorio del mismo en GitHub: [https://github.com/agumurray/card-game-php.git](https://github.com/agumurray/card-game-php.git)
---
# API de Juego de Cartas en PHP

Una API RESTful para un juego de cartas en línea, construida con PHP, Slim Framework y [Docker](https://www.docker.com/products/docker-desktop).

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

```bash
docker-compose up -d
```

Después de iniciar la aplicación:

* La API estará disponible en: [http://localhost:8080](http://localhost:8080)
  Puedes enviar solicitudes usando herramientas como Postman o `curl`.

  También podés usar esta [colección de Postman](https://agustin-7610866.postman.co/workspace/Agustin's-Workspace~410d3a08-beda-4a71-8e6a-fc78d13e900c/collection/43658514-79d729d0-10a9-429b-83d5-e2097f882833?action=share&creator=43658514&active-environment=43658514-925d8b7a-8a96-4b2e-a068-d062fdada82e) para probar fácilmente los endpoints disponibles (registro, login, partidas, jugadas, etc.).

  > ⚠️ **Importante**: luego de hacer login, el token JWT que devuelve la respuesta debe ser copiado y pegado manualmente en el entorno de Postman.
  > Abrí la pestaña "Environments", seleccioná el entorno "Local" y pegá el token en la variable `token`, en el campo "Current Value".
  > Luego presioná `Ctrl + S` (o `Cmd + S` en macOS) para guardar los cambios.
  > Esto permitirá que los endpoints que requieren autenticación funcionen correctamente.

* La base de datos se puede visualizar mediante phpMyAdmin en: [http://localhost:8081](http://localhost:8081)

### Detener la aplicación (conservando los datos de la base de datos):

```bash
docker-compose down
```

### Detener y reiniciar la base de datos (iniciar desde cero):

```bash
docker-compose down -v
```

---

## Esquema de Base de Datos

El archivo [`init.sql`](init.sql) contiene el esquema de base de datos provisto en la práctica.
**No se realizaron modificaciones** sobre el contenido original del mismo.

---

## Dependencias (composer.json)

Estas son las bibliotecas requeridas por la aplicación y su propósito:

* **`slim/slim` (^4.14)**: Framework minimalista para construir aplicaciones web y APIs REST con PHP.
* **`slim/psr7` (^1.7)**: Implementación de PSR-7 (HTTP messages) para manejar solicitudes y respuestas en Slim.
* **`php-di/php-di` (^7.0)**: Contenedor de inyección de dependencias para facilitar la gestión de objetos y servicios.
* **`firebase/php-jwt` (^6.11)**: Biblioteca para crear y verificar JSON Web Tokens (JWT), útil para autenticación y autorización.

---
