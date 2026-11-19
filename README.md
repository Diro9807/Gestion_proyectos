# Gestión de proyectos

Aplicación web para crear y organizar proyectos, coordinar tareas y colaborar con otros usuarios. Proyecto personal desarrollado como parte de mi aprendizaje en Desarrollo de Aplicaciones Web (DAW).

> **Estado:** proyecto en desarrollo.

## Características principales

- Registro, inicio y cierre de sesión.
- Autenticación mediante tokens con Laravel Sanctum.
- Creación, edición y eliminación de proyectos.
- Gestión de tareas con fechas, estados y asignación de usuarios.
- Reordenación de tareas.
- Gestión de miembros y roles dentro de los proyectos.
- Consulta de proyectos propios y compartidos.
- Perfil de usuario.
- Panel de administración para consultar, filtrar, cambiar roles y eliminar usuarios.
- Navegación protegida según autenticación y permisos.
- Interfaz adaptable a diferentes tamaños de pantalla.

## Tecnologías

| Área | Tecnologías |
|---|---|
| Backend | PHP, Laravel 12, Laravel Sanctum |
| Frontend | Vue 3, JavaScript, Vite |
| Base de datos | MySQL |
| Herramientas | Composer, npm |

## Estructura del repositorio

```text
Gestion_proyectos/
├── backend-gestion-proyecto/   # API y aplicación Laravel
├── eina_gestio_vue/            # Interfaz Vue 3
├── Database-Backup/            # Volcado SQL de la base de datos
└── eina_gestio_vue.code-workspace
```

## Requisitos previos

- PHP 8.2 o superior.
- Composer.
- Node.js compatible con Vue/Vite (el frontend declara Node.js 20.19+ o 22.12+).
- MySQL.
- npm.

## Instalación y ejecución

Los siguientes pasos describen una configuración local de desarrollo. Los comandos se deben ejecutar desde la carpeta indicada.

### 1. Clonar el repositorio

```bash
git clone https://github.com/Diro9807/Gestion_proyectos.git
cd Gestion_proyectos
```

### 2. Preparar la base de datos

1. Crea una base de datos MySQL llamada `herramienta_gestion`.
2. Importa el archivo SQL de `Database-Backup` únicamente si has comprobado que es un volcado limpio y sin datos privados.
3. Configura las credenciales de conexión en el archivo `.env` del backend.

> El repositorio incluye un volcado SQL de desarrollo. No lo importes en entornos reales: utiliza una base de datos de prueba y datos ficticios.

### 3. Configurar el backend

```bash
cd backend-gestion-proyecto
composer install
```

Crea el archivo `.env` a partir de `.env.example` y configura, como mínimo, la conexión MySQL:

```dotenv
APP_NAME="Gestión de proyectos"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=herramienta_gestion
DB_USERNAME=TU_USUARIO
DB_PASSWORD=TU_CONTRASEÑA
```

Genera la clave de la aplicación:

```bash
php artisan key:generate
```

Si partes de una base de datos vacía, ejecuta las migraciones disponibles:

```bash
php artisan migrate
```

Instala las dependencias JavaScript que utiliza el backend/Vite:

```bash
npm install
```

Inicia el servidor Laravel:

```bash
php artisan serve
```

La API quedará disponible normalmente en `http://127.0.0.1:8000/api`.

### 4. Configurar el frontend

Abre otra terminal desde la raíz del repositorio:

```bash
cd eina_gestio_vue
npm install
```

Crea un archivo `.env` en esta carpeta con la URL de la API local:

```dotenv
VITE_API_URL=http://127.0.0.1:8000/api
```

Inicia Vue con Vite:

```bash
npm run dev
```

Abre en el navegador la URL local que indique Vite en la terminal.

Para generar una compilación de producción:

```bash
npm run build
```

## API

La API incluye rutas públicas de registro e inicio de sesión y rutas protegidas mediante Laravel Sanctum.

Algunos recursos disponibles:

- `POST /api/register` — registro.
- `POST /api/login` — inicio de sesión.
- `POST /api/logout` — cierre de sesión.
- `GET /api/projects` — proyectos del usuario autenticado.
- `POST /api/projects` — crear un proyecto.
- `PUT /api/projects/{project}` — actualizar un proyecto.
- `DELETE /api/projects/{project}` — eliminar un proyecto.
- `GET /api/projects/{project}/tasks` — tareas de un proyecto.
- `POST /api/tasks` — crear una tarea.
- `PUT /api/tasks/{task}` — actualizar una tarea.
- `DELETE /api/tasks/{task}` — eliminar una tarea.

Las rutas protegidas requieren el token de autenticación en la cabecera `Authorization: Bearer <token>`.

## Autor

**Jordi Pérez Fernández**  
Estudiante de Desarrollo de Aplicaciones Web (DAW).

- GitHub: [@Diro9807](https://github.com/Diro9807)
- Repositorio: [Gestion_proyectos](https://github.com/Diro9807/Gestion_proyectos)

---

Proyecto personal con finalidad formativa y de aprendizaje.
