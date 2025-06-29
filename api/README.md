# API - Cambios realizados

Este documento detalla los cambios introducidos en la API con respecto a la entrega anterior. Estas modificaciones tienen como objetivo mejorar la gestión de autenticación, la funcionalidad de los endpoints y la interacción con el frontend.

---

## 🔐 Autenticación JWT

Anteriormente se utilizaba Firebase JWT para la gestión de autenticación. Esta implementación fue reemplazada por una solución propia basada en:

- `random_bytes` y `bin2hex` para la generación de tokens únicos y seguros.
- Compatibilidad directa con la estructura del campo `token` en la base de datos.

Esta decisión se tomó para simplificar la validación y almacenamiento de los tokens dentro del sistema.

---

## 🔚 Nuevo endpoint: `/logout`

Se incorpora un nuevo endpoint `POST /logout` que permite cerrar sesión de forma segura.  
### Comportamiento:

- Verifica que el usuario esté autenticado.
- Si el usuario tiene una partida en curso:
  - Se restauran las cartas de su mazo y del servidor al estado original (`en_mazo`).
  - Se marca la partida como finalizada con resultado "perdió".
  - Se eliminan las jugadas asociadas.
- Finalmente, se borra el token del usuario de la base de datos.

Este endpoint es clave para asegurar una correcta limpieza del estado del usuario al cerrar sesión.

---

## 🃏 Mejora en endpoint: `/usuarios/{usuario}/mazos`

Se amplía la respuesta de este endpoint para incluir el **nombre del atributo** de cada carta del mazo.

Esto facilita la visualización y funcionalidad en el frontend, eliminando la necesidad de múltiples llamadas adicionales para obtener esta información.

---

## 👤 Nuevo endpoint: `/yo`

Se agrega el endpoint `GET /yo` que permite al frontend verificar de forma rápida y segura si el usuario autenticado sigue siendo válido.

### Comportamiento:

- Verifica la validez del token.
- Devuelve la información básica del usuario si está autenticado.
- En caso contrario, devuelve un mensaje de error.

Este endpoint mejora la experiencia de usuario en el frontend, permitiendo validaciones transparentes y eficientes.

---

