# 📋 tasks-app

Proyecto desarrollado para la materia **INF781 – Seguridad de Software**  
Ingeniería Informática · Universidad Autónoma Tomás Frías (UATF)  
Docente: M. Sc. Huáscar Fedor Gonzales Guzmán

---

## 🛡️ Descripción

`tasks-app` es una aplicación web de gestión de tareas construida con **Laravel 13**, orientada a la implementación progresiva de mecanismos de seguridad de software. A lo largo de las guías de laboratorio, se incorporan funcionalidades como autenticación segura, hashing de contraseñas, autenticación multifactor (MFA/TOTP), autorización basada en roles (Policies y Gates), y protección CAPTCHA.

---

## 🧰 Tecnologías

| Componente | Versión |
|---|---|
| PHP | ^8.3 |
| Laravel | ^13.0 |
| Base de datos | PostgreSQL |
| Frontend | Blade + Vite |

---

## ⚙️ Instalación y configuración

### 1. Clonar el repositorio

```bash
git clone https://github.com/HuascarFedor/inf781_task_app.git
cd inf781_task_app
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Editar el archivo `.env` con los datos de conexión a PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tasks_db
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

### 4. Ejecutar migraciones

```bash
php artisan migrate
```

Opcionalmente, con seeders:

```bash
php artisan db:seed
```

### 5. Instalar dependencias frontend y compilar assets

```bash
npm install
npm run build
```

> Para desarrollo con hot reload: `npm run dev`

### 6. Levantar el servidor

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

---

## 🚀 Inicio rápido (setup automático)

El proyecto incluye un script de configuración completo:

```bash
composer run setup
```

Este comando ejecuta automáticamente: `composer install`, copia el `.env`, genera la clave, corre las migraciones, instala dependencias npm y compila los assets.

---

## 🧪 Pruebas

```bash
php artisan test
```

O mediante el script de Composer:

```bash
composer run test
```

---

## 📚 Guías de laboratorio

| Guía | Tema |
|------|------|
| Guía 1 | Configuración Segura del Proyecto Base con Laravel |
| Guía 2 | Validación y Sanitización de Entrada en Laravel |
| Guía 3 | Registro y Login Seguro con Hashing de Contraseñas |
| Guía 4 | Inicio de Sesión Seguro: Sesiones, Cookies y Protección CSRF |
| Guía 5 | Autenticación Multifactor con Google Authenticator (TOTP) |
| Guía 6 | Autorización con Roles, Policies y Gates en Laravel |
| Guía 7 | Protección de Formularios con CAPTCHA |

---

## 📄 Licencia

Proyecto académico — HUASCAR FEDOR GONZALES GUZMAN - 2026