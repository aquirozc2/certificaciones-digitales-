# 📜 Certificaciones Digitales — UMSA Facultad de Medicina

Sistema web para la generación, diseño y emisión de **certificados digitales** y **memorandums** con firma, QR de validación y fondos personalizables.

Desarrollado con **Laravel 12** · **PHP 8.2** · **PostgreSQL** · **Bootstrap 5** · **DomPDF**

---

## ⚙️ Requisitos previos

Asegúrate de tener instalado en tu sistema:

| Herramienta | Versión mínima |
|---|---|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| npm | 9+ |
| PostgreSQL | 14+ |

### Extensiones PHP necesarias

```
php-pdo
php-pgsql
php-mbstring
php-xml
php-gd
php-zip
php-curl
php-fileinfo
php-tokenizer
```

En Ubuntu/Debian:
```bash
sudo apt install php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-gd php8.2-zip php8.2-curl
```

---

## 🚀 Instalación y despliegue

### 1. Clonar el repositorio

```bash
git clone https://github.com/aquirozc2/certificaciones-digitales.git
cd certificaciones-digitales
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar variables de entorno

Copia el archivo de ejemplo y edítalo:

```bash
cp .env.example .env
```

Abre `.env` y configura lo siguiente:

```env
APP_NAME="Certificaciones Digitales"
APP_ENV=local
APP_KEY=                          # se genera en el paso 5
APP_URL=http://localhost:8000

# Base de datos PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=certificaciones
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Correo (opcional)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="no-reply@medicina.umsa.bo"
MAIL_FROM_NAME="Certificaciones Digitales"
```

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

### 6. Crear la base de datos en PostgreSQL

```bash
psql -U postgres -c "CREATE DATABASE certificaciones;"
```

### 7. Ejecutar migraciones

```bash
php artisan migrate
```

### 8. Ejecutar seeders

```bash
php artisan db:seed
```

Se crean roles, permisos y referencias a fondos. La consola solicita una contraseña
propia (mínimo 12 caracteres) para `sysadmin` (rol `admin`) y `operador`.
Si se deja vacía, esa cuenta no se crea. No hay contraseñas predeterminadas.
Las cuentas existentes no se modifican.

### 9. Crear enlace de almacenamiento público

```bash
php artisan storage:link
```

### 10. Compilar assets

Para **desarrollo** (con hot reload):
```bash
npm run dev
```

Para **producción**:
```bash
npm run build
```

### 11. Iniciar el servidor

```bash
php artisan serve
```

Accede en: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 📁 Estructura relevante del proyecto

```
app/Http/Controllers/     → Controladores (Eventos, Memorandums, Personas, Certificados...)
app/Models/               → Modelos Eloquent
database/migrations/      → Migraciones de la base de datos
database/seeders/         → Datos iniciales (roles, permisos, fondos, usuarios)
public/fondo-completos/   → Imágenes de fondos para certificados verticales
public/fondo-memo/        → Imagen de fondo para memorandums
public/fonts/             → Fuentes TTF para generación de PDFs (Roboto, Great Vibes...)
public/img/               → Logos e imágenes del sistema
resources/views/          → Vistas Blade (eventos, memorandums, personas, partials, pdf)
storage/app/certs/        → PDFs de certificados generados [excluido del repo]
storage/app/certs-memo/   → PDFs de certificados desde memorandums [excluido del repo]
storage/app/memos/        → PDFs de memorandums generados [excluido del repo]
```

---

## 🔑 Roles y permisos

| Rol | Descripción |
|---|---|
| `super_admin` | Acceso total |
| `admin` | Gestión de eventos, memorandums y generación de PDFs |
| `operador` | Visualización y generación de documentos |

---

## 🖨️ Fuentes para DomPDF

Los PDFs usan fuentes TTF ubicadas en `public/fonts/`. Deben estar presentes:

```
public/fonts/Roboto-Regular.ttf
public/fonts/Roboto-Slab.ttf
public/fonts/RobotoSlab-Bold.ttf
public/fonts/GreatVibes-Regular.ttf
```

Si los PDFs salen sin fuente, limpia la caché de DomPDF:

```bash
# Eliminar archivos de caché de fuentes (se regeneran solos)
del public\fonts\*.php
del public\fonts\*.json
```

---

## 📄 Comandos de mantenimiento

```bash
# Limpiar caché de vistas compiladas
php artisan view:clear

# Limpiar toda la caché de la aplicación
php artisan optimize:clear

# Ver estado de migraciones
php artisan migrate:status

# Ver rutas registradas
php artisan route:list

# Reiniciar BD y reseedear (⚠️ borra todos los datos)
php artisan migrate:fresh --seed
```

---

## 🛠️ Despliegue en producción

1. Actualiza `.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tu-dominio.com
   ```

2. Optimiza la aplicación:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

3. Permisos de carpetas (Linux):
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

---

## 🐛 Problemas comunes

| Error | Solución |
|---|---|
| `Duplicate table` en migrate | Las migraciones ya existen. Usa `php artisan migrate:status` |
| `Class Permission not found` | Ejecuta `php artisan optimize:clear && php artisan db:seed` |
| PDFs sin fuentes / caracteres raros | Verifica que existan los `.ttf` en `public/fonts/` |
| Error 500 en storage | Ejecuta `php artisan storage:link` y verifica permisos |


---

## 📦 Dependencias principales

| Paquete | Propósito |
|---|---|
| `barryvdh/laravel-dompdf` | Generación de PDFs |
| `spatie/laravel-permission` | Roles y permisos |
| `simplesoftwareio/simple-qrcode` | Códigos QR en certificados |
| `maatwebsite/excel` | Exportación Excel |
| `tecnickcom/tcpdf` | Soporte adicional PDF |
| Quill.js 1.3.6 (CDN) | Editor de texto enriquecido |
| Bootstrap 5.0.2 (CDN) | UI |
| DataTables 1.13.8 (CDN) | Tablas con búsqueda y paginación |


## Alcance de esta copia para publicación

Preparada para el repositorio previsto `aquirozc2/certificaciones-digitales`.
La preparación de archivos no significa que el repositorio ya exista.

Se excluyeron `.env`, credenciales, respaldos y scripts SQL, historial Git,
dependencias instaladas, cachés, registros y documentos generados.
También se excluyeron las imágenes institucionales, firmas, sellos y fondos
(`public/img`, `public/fondo-completos`, `public/fondo-memo`).
Las plantillas conservan referencias a esos recursos: deben configurarse con
imágenes propias o autorizadas antes de generar documentos completos.
El archivo original se conserva sin cambios.

Esta copia fue revisada para retirar credenciales evidentes; no equivale a una
auditoría de seguridad. No se ejecutó la aplicación en este entorno.
Verificar migraciones, instalación y generación de documentos antes de desplegar.
La documentación original menciona fuentes de `public/fonts` que no están en esta copia.
