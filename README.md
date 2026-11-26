# ArtSpace

Proyecto de fin de ciclo de Desarrollo de Aplicaciones Web (DAW) — I.E.S. Fernando Wirtz Suárez (A Coruña).  
Autor: **Gabriel Pazos Sieiro**

ArtSpace es una aplicación web pensada para artistas y fotógrafos independientes que quieren mostrar su catálogo de obras y recibir consultas directas de clientes sin pagar comisiones de intermediación.

## Características principales

- Catálogo público con búsqueda por título, filtro por categoría/técnica, ordenación y paginación.
- Ficha de obra con soporte para imágenes (con visor ampliado) y vídeos, además de conversión orientativa de precios (EUR a USD y GBP) mediante la API pública de Frankfurter (Banco Central Europeo).
- Sistema de obras favoritas guardado en el navegador (`localStorage`) y cargado de forma asíncrona con `fetch`.
- Autenticación con roles (`admin` y `user`), registro de clientes y rutas protegidas.
- Panel de administración y **Modo Edición** visual para cambiar colores, textos, logo, categorías y activar opciones de protección anti-IA sin tocar código.
- Mensajería con control anti-spam (solo usuarios autenticados), respuestas desde el panel y bloqueo de usuarios.
- Soporte PWA (`manifest.json` y Service Worker) y pruebas automatizadas con PHPUnit.

## Requisitos

- PHP 8.2 o superior (recomendado 8.3)
- Composer
- MySQL 8.0 (XAMPP, Laragon o Docker)
- Git
- Docker y Docker Compose (para despliegue en contenedores)

## Instalación y ejecución en local (XAMPP)

1. Clonar el repositorio:
```bash
git clone https://github.com/retr0500/artspace.git
cd artspace
```

2. Instalar las dependencias de PHP:
```bash
composer install
```

3. Configurar el entorno:
```bash
cp .env.example .env
php artisan key:generate
```
Revisa en `.env` que `DB_PORT`, `DB_DATABASE=artspace_db`, `DB_USERNAME` y `DB_PASSWORD` coinciden con tu servidor MySQL local (crea antes la base de datos vacía `artspace_db`).

4. Crear las tablas, cargar los datos iniciales y enlazar el almacenamiento público:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

5. Arrancar el servidor de desarrollo:
```bash
php artisan serve
```
La aplicación estará disponible en `http://127.0.0.1:8000`.

## Despliegue con Docker Compose

```bash
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan storage:link
```
Una vez levantados los contenedores, accede a `http://127.0.0.1:8000`.

## Cuentas de prueba (Seeder)

- **Artista / Administrador:** `admin@artspace.com` / `admin1234`
- **Cliente registrado:** `cliente@artspace.com` / `cliente1234`

## Endpoints API REST (JSON)

La colección de Postman se encuentra en `docs/ArtSpace_Postman_Collection.json`.

- `GET /api/obras` — Listado JSON de obras (admite `?buscar=texto`).
- `GET /api/obras/{id}` — Detalle de una obra en JSON (devuelve `404` si no existe).
- `GET /api/divisas?eur=480` — Conversión orientativa de EUR a USD y GBP usando Frankfurter API con caché y respaldo.

## Pruebas automatizadas

```bash
php artisan test
```