# CARACTERÍSTICAS DEL SISTEMA

## 🎯 Funcionalidades Principales

### 1. PÁGINA PRINCIPAL (index.php)
✨ Dashboard moderno con 3 opciones principales:
- Registrar Grupo
- Registrar Alumno  
- Ver Alumnos Registrados

🎨 Diseño:
- Cards con efectos hover
- Gradiente morado profesional
- Iconos emoji para mejor UX
- Bootstrap 5 responsivo

---

### 2. REGISTRAR GRUPO (registrar_grupo.php)

📋 Campos del formulario:
- **Carrera**: Dropdown con carreras disponibles (ISC, IND, IEL, IME, IGE)
- **Turno**: Matutino (M) o Vespertino (V)
- **Grado**: Semestres del 1 al 8
- **Vista Previa**: Muestra el código del grupo en tiempo real

🔄 Funcionalidad automática:
- Genera código automático: [CARRERA][GRADO][NÚMERO]-[TURNO]
- Ejemplos:
  * ISC801-V (Sistemas, 8vo sem, grupo 01, vespertino)
  * IND502-M (Industrial, 5to sem, grupo 02, matutino)
  * IEL103-V (Electrónica, 1er sem, grupo 03, vespertino)

📊 Lógica de numeración:
- Si existe ISC801-V, el siguiente será ISC802-V
- Si existe ISC801-M, el siguiente será ISC802-M
- Cada combinación carrera+turno+grado tiene su propia secuencia

---

### 3. REGISTRAR ALUMNO (registrar_alumno.php)

👨‍🎓 Campos del formulario:
- Nombre
- Apellido Paterno
- Apellido Materno
- Grupo (solo muestra grupos previamente registrados)

✅ Validaciones:
- Todos los campos son obligatorios
- El dropdown de grupo solo lista grupos existentes
- No se puede registrar sin un grupo creado

🎯 Flujo de trabajo:
1. Primero se deben crear grupos en "Registrar Grupo"
2. Luego aparecerán en el dropdown de "Grupo"
3. Se registra el alumno asociado a ese grupo

---

### 4. ALUMNOS REGISTRADOS (alumnos_registrados.php)

📊 Tabla completa con:
- ID del alumno
- Nombre completo (nombre + apellidos)
- Grupo asignado (con badge de color)
- Estado del alumno (badge ACTIVO/INACTIVO)
- Botones de acción (Editar, Habilitar/Inhabilitar)

🎨 Código de colores:
- **Fila con fondo verde**: Alumno ACTIVO
- **Fila con fondo rojo**: Alumno INACTIVO

🔍 Características:
- Ordenado por grupo, luego por apellido
- Contador total de alumnos
- Efecto hover en filas
- Diseño tabla responsive
- Confirmación antes de cambiar estado

🔧 Botones de acción:
- **✏️ Editar**: Redirige a formulario de edición
- **❌ Inhabilitar**: Cambia estado a inactivo (fondo rojo)
- **✅ Habilitar**: Cambia estado a activo (fondo verde)

---

### 5. EDITAR ALUMNO (editar_alumno.php)

📝 Formulario de edición con campos prellenados:
- Nombre
- Apellido Paterno
- Apellido Materno
- Grupo (dropdown con grupos disponibles)

✅ Funcionalidad:
- Carga datos actuales del alumno
- Permite modificar todos los campos
- Validación de campos obligatorios
- Botón "Guardar Cambios" actualiza en BD
- Botón "Cancelar" regresa a la lista

---

## 🛠️ TECNOLOGÍAS UTILIZADAS

### Backend
- **PHP 7+**: Lógica del servidor
- **MySQL/MariaDB**: Base de datos relacional
- **Prepared Statements**: Seguridad contra SQL injection
- **mysqli**: Extensión de PHP para MySQL

### Frontend
- **Bootstrap 5.3.2**: Framework CSS
- **Google Fonts (Inter)**: Tipografía moderna
- **JavaScript vanilla**: Interactividad
- **CSS custom**: Gradientes y animaciones

### Seguridad
✅ Sanitización de datos con mysqli
✅ Prepared Statements
✅ Validación cliente y servidor
✅ .htaccess con protecciones
✅ Headers de seguridad (X-Frame-Options, etc.)

---

## 📊 BASE DE DATOS

### Tablas y relaciones:

**carreras**
- id (PK)
- nombre
- codigo
- created_at

**turnos**
- id (PK)
- nombre
- codigo
- created_at

**grados**
- id (PK)
- numero
- descripcion
- created_at

**grupos**
- id (PK)
- nombre (UNIQUE)
- carrera_id (FK)
- turno_id (FK)
- grado_id (FK)
- numero_grupo
- created_at

**alumnos**
- id (PK)
- nombre
- apellido_p
- apellido_m
- grupo_id (FK)
- activo (TINYINT 1=activo, 0=inactivo)
- created_at

### Datos precargados:
✅ 5 carreras (ISC, IND, IEL, IME, IGE)
✅ 2 turnos (Matutino, Vespertino)
✅ 8 grados (semestres 1-8)

---

## 🎨 CARACTERÍSTICAS DE DISEÑO

### Paleta de colores:
- Gradiente principal: #667eea → #764ba2 (morado)
- Blanco para cards: #ffffff
- Texto: #333333
- Grises: #f8f9fa, #e0e0e0

### Efectos visuales:
- ✨ Hover en cards con elevación
- 🎯 Botones con transform y shadow
- 📱 100% responsive
- 🌈 Gradientes suaves
- 🔄 Transiciones CSS smooth

### Tipografía:
- Font family: 'Inter' (Google Fonts)
- Weights: 300, 400, 500, 600, 700
- Sistema de escalado jerárquico

---

## 🚀 OPTIMIZACIONES

### Performance:
- Bootstrap desde CDN (cache del navegador)
- CSS minificado
- Consultas SQL optimizadas con índices
- Sin JavaScript innecesario

### SEO y Accesibilidad:
- Meta tags apropiados
- HTML semántico
- Labels en formularios
- Mensajes de error claros

### Hosting:
- Compatible con shared hosting
- Archivos organizados (no subdirectorios)
- .htaccess incluido
- Sin dependencias externas (excepto Bootstrap CDN)

---

## 📝 ARCHIVOS DEL PROYECTO

```
sistema_alumnos/
├── index.php                  (Página principal)
├── registrar_grupo.php        (Formulario grupos)
├── registrar_alumno.php       (Formulario alumnos)
├── alumnos_registrados.php    (Lista de alumnos)
├── editar_alumno.php          (Editar estudiante)
├── config.php                 (Configuración DB)
├── database.sql               (Script SQL)
├── migracion_activo.sql       (Actualización BD)
├── .htaccess                  (Apache config)
├── README.md                  (Documentación)
├── GUIA_RAPIDA.txt           (Quick start)
└── CARACTERISTICAS.md        (Doc técnica)
```

**Total de archivos PHP: 5**
**Total de archivos: 12**
**Tamaño comprimido: ~14KB**

---

## ✨ VENTAJAS DEL SISTEMA

1. **Simplicidad**: Solo 5 archivos PHP principales
2. **Profesional**: Diseño moderno con Bootstrap
3. **Seguro**: Prepared statements y validaciones
4. **Escalable**: Fácil agregar más carreras/turnos
5. **Portable**: Funciona en cualquier hosting con PHP+MySQL
6. **Sin dependencias**: No requiere Composer ni frameworks
7. **Git friendly**: Archivos organizados, fácil de versionar
8. **Documentado**: README completo y guía rápida

---

## 🎓 CASOS DE USO

### Escenario 1: Nueva inscripción
1. Coordinador registra grupos del semestre
2. Alumnos se inscriben con sus datos
3. Se asignan automáticamente al grupo

### Escenario 2: Consulta rápida
1. Accede a "Alumnos Registrados"
2. Busca visualmente en la tabla
3. Identifica grupo por badge de color

### Escenario 3: Corrección de datos
1. Localiza alumno en la lista
2. Clic en "✏️ Editar"
3. Modifica los datos incorrectos
4. Guarda los cambios

### Escenario 4: Gestión de bajas temporales
1. Estudiante solicita baja temporal
2. Coordinador inhabilita al alumno (fila se pone roja)
3. Al regresar, se habilita nuevamente (fila se pone verde)
4. Mantiene historial sin eliminar datos

---

## 🔮 POSIBLES MEJORAS FUTURAS

- [ ] Búsqueda y filtros en lista de alumnos
- [x] Edición de datos de alumnos ✅
- [x] Sistema de habilitar/inhabilitar alumnos ✅
- [ ] Exportar a Excel/PDF
- [ ] Sistema de login para coordinadores
- [ ] Estadísticas y reportes
- [ ] Gestión de calificaciones
- [ ] API REST para integración
- [ ] Filtrar por estado (activos/inactivos)
- [ ] Historial de cambios

---

**SISTEMA LISTO PARA PRODUCCIÓN** ✅
