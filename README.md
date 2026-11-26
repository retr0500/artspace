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
git clone [https://github.com/retr0500/artspace.git](https://github.com/retr0500/artspace.git)
cd artspace