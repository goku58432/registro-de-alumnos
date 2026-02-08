# 📚 Sistema de Gestión de Alumnos v2.1

## 🆕 Nuevas Funcionalidades

### ⚙️ Módulo de Catálogos
- **Nueva sección completa** para gestionar carreras
- Agregar nuevas carreras desde la interfaz web
- Visualizar todas las carreras registradas
- Ver turnos y grados disponibles
- Validación de códigos únicos

### ✅ Correcciones Implementadas
- ✅ Error de columna `activo` **CORREGIDO**
- ✅ Consulta SQL actualizada para incluir estado del alumno
- ✅ Sistema de habilitar/inhabilitar funcionando correctamente
- ✅ Colores de filas según estado (verde=activo, rojo=inactivo)

## 🎯 Características Principales

### 1. Página Principal (4 opciones)
- 📚 Registrar Grupo
- 👨‍🎓 Registrar Alumno
- 👥 Alumnos Registrados
- ⚙️ **Catálogos** (NUEVO)

### 2. Gestión de Alumnos
- Registro de estudiantes
- Edición de datos
- Sistema de habilitar/inhabilitar (sin eliminar)
- Código de colores visual:
  - **Verde**: Alumno activo ✅
  - **Rojo**: Alumno inactivo ❌

### 3. Gestión de Carreras (NUEVO)
- Agregar nuevas carreras
- Código automático de 3 letras
- Validación de códigos únicos
- Tabla con todas las carreras registradas
- Ver turnos y grados del sistema

## 📦 Instalación

### Opción 1: Instalación Nueva
```bash
1. Crea la base de datos en phpMyAdmin:
   - Nombre: sistema_alumnos

2. Importa el archivo:
   database.sql

3. Configura config.php con tus credenciales:
   DB_HOST = 'localhost'
   DB_USER = 'tu_usuario'
   DB_PASS = 'tu_contraseña'
   DB_NAME = 'sistema_alumnos'

4. Sube todos los archivos a tu servidor

5. Accede a: http://tu-dominio.com
```

### Opción 2: Actualización desde v1.0
```bash
1. Si ya tienes el sistema anterior, importa:
   migracion_activo.sql
   
2. Reemplaza todos los archivos PHP

3. ¡Listo! Tu base de datos se actualizará automáticamente
```

## 📁 Estructura de Archivos

```
sistema_alumnos/
├── index.php                   ⭐ Página principal (4 opciones)
├── registrar_grupo.php         📚 Crear grupos
├── registrar_alumno.php        👨‍🎓 Inscribir estudiantes
├── alumnos_registrados.php     👥 Lista con colores
├── editar_alumno.php           ✏️ Modificar datos
├── catalogos.php               ⚙️ Gestionar carreras (NUEVO)
├── config.php                  🔧 Configuración BD
├── database.sql                💾 Base de datos completa
├── migracion_activo.sql        🔄 Script de actualización
├── .htaccess                   🔒 Seguridad Apache
└── README.md                   📖 Este archivo
```

## 🎨 Diseño

- Gradiente morado profesional (#667eea → #764ba2)
- Bootstrap 5.3.2
- Fuente: Inter (Google Fonts)
- 100% responsive
- Efectos hover suaves

## 🔐 Seguridad

- ✅ Prepared Statements (protección SQL injection)
- ✅ Sanitización de datos
- ✅ Validación cliente y servidor
- ✅ .htaccess con headers de seguridad

## 📊 Base de Datos

### Tablas:
- **carreras** - Programas académicos
- **turnos** - Matutino/Vespertino
- **grados** - Semestres 1-8
- **grupos** - Combinación de carrera+turno+grado
- **alumnos** - Estudiantes con estado activo/inactivo

### Relaciones:
```
alumnos → grupos → carreras
                → turnos
                → grados
```

## 🚀 Uso del Sistema

### 1. Agregar Carrera (NUEVO)
```
1. Ve a "Catálogos"
2. Llena el formulario:
   - Nombre: Ingeniería en Sistemas Computacionales
   - Código: ISC (3 letras)
3. Clic en "Agregar Carrera"
4. Aparecerá en la tabla inferior
```

### 2. Crear Grupo
```
1. Ve a "Registrar Grupo"
2. Selecciona: Carrera + Turno + Grado
3. Vista previa muestra el código (Ej: ISC801-V)
4. Clic en "Registrar Grupo"
```

### 3. Inscribir Alumno
```
1. Ve a "Registrar Alumno"
2. Llena: Nombre, Apellidos, Grupo
3. Clic en "Registrar Alumno"
```

### 4. Gestionar Estado
```
1. Ve a "Alumnos Registrados"
2. Localiza al estudiante
3. Opciones:
   - ✏️ Editar → Modificar datos
   - ❌ Inhabilitar → Cambiar a inactivo (rojo)
   - ✅ Habilitar → Cambiar a activo (verde)
```

## 🆕 Novedades v2.1

### ⚙️ Módulo de Catálogos
- Interfaz web para agregar carreras
- Ya no necesitas editar la base de datos manualmente
- Formulario con validación de códigos
- Tabla visual con todas las carreras

### 🔧 Mejoras Técnicas
- Consulta SQL corregida (incluye columna `activo`)
- Código más limpio y organizado
- Mejor manejo de errores
- Validaciones mejoradas

## ⚠️ Requisitos

- PHP 7.0 o superior
- MySQL 5.6 o superior / MariaDB
- Apache con mod_rewrite (opcional)
- Conexión a internet (Bootstrap CDN)

## 🎯 Casos de Uso

### Escenario 1: Agregar nueva carrera
```
El coordinador necesita agregar "Ingeniería Mecánica"
→ Va a Catálogos
→ Agrega: Nombre + Código (IME)
→ Queda disponible para crear grupos
```

### Escenario 2: Baja temporal de alumno
```
Un estudiante toma un semestre sabático
→ Se marca como "Inactivo" (fila roja)
→ Al regresar, se habilita nuevamente
→ No se pierden sus datos
```

### Escenario 3: Corrección de datos
```
Se escribió mal el apellido
→ Clic en "Editar"
→ Se corrige el dato
→ Se guarda sin crear nuevo registro
```

## 📞 Soporte

Para reportar errores o sugerencias:
1. Verifica que importaste `migracion_activo.sql` si actualizaste
2. Revisa la configuración en `config.php`
3. Verifica permisos de archivos (644 para PHP, 755 para carpetas)

## 🔄 Changelog

### v2.1 (06/02/2026) - ACTUAL
- ✅ Módulo de Catálogos agregado
- ✅ Error de columna `activo` corregido
- ✅ Interfaz para agregar carreras
- ✅ Mejoras en validación de datos

### v2.0 (04/02/2026)
- Sistema de habilitar/inhabilitar
- Edición de alumnos
- Código de colores visual

### v1.0 (Inicial)
- Registro de grupos y alumnos
- Listado básico

## 📝 Licencia

Sistema de uso libre para instituciones educativas.

---

**Sistema listo para producción** ✅
**Versión:** 2.1
**Fecha:** 06 de Febrero de 2026
