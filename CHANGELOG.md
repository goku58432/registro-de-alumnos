# REGISTRO DE CAMBIOS

## Versión 2.0 - Febrero 2026

### ✨ Nuevas Funcionalidades

#### 1. Sistema de Edición de Alumnos
- Nuevo archivo: `editar_alumno.php`
- Permite modificar todos los datos de un alumno existente
- Formulario prellenado con datos actuales
- Validación de campos obligatorios
- Botón de cancelar para volver sin guardar cambios

#### 2. Sistema de Habilitar/Inhabilitar Alumnos
- Nueva columna en base de datos: `activo` (TINYINT)
- Estados posibles:
  * **1 = Activo** (mostrado con fondo verde)
  * **0 = Inactivo** (mostrado con fondo rojo)
- Botones dinámicos según estado:
  * Si está activo: muestra botón "❌ Inhabilitar"
  * Si está inactivo: muestra botón "✅ Habilitar"
- No se eliminan datos, solo se cambia el estado
- Útil para bajas temporales o suspensiones

#### 3. Código de Colores Visual
- **Filas verdes** (#d4edda): Alumnos activos
- **Filas rojas** (#f8d7da): Alumnos inactivos
- Badge de estado en columna dedicada
- Mejora la identificación visual rápida

#### 4. Botones de Acción Mejorados
- Reemplaza el botón único de "Eliminar"
- Ahora hay 3 acciones disponibles:
  1. **✏️ Editar** (amarillo)
  2. **❌ Inhabilitar** (rojo) - solo si está activo
  3. **✅ Habilitar** (verde) - solo si está inactivo

### 🗑️ Archivos Eliminados
- `eliminar_alumno.php` - Ya no es necesario con el nuevo sistema

### 📄 Archivos Nuevos
- `editar_alumno.php` - Formulario de edición
- `migracion_activo.sql` - Script para actualizar BD existentes

### 🔧 Archivos Modificados
- `alumnos_registrados.php` - Totalmente rediseñado con nuevas funcionalidades
- `database.sql` - Incluye columna `activo` en tabla alumnos
- `README.md` - Actualizado con nuevas instrucciones
- `CARACTERISTICAS.md` - Documentación técnica actualizada
- `GUIA_RAPIDA.txt` - Incluye nuevos pasos de uso

### 🎨 Mejoras de Diseño
- Mejor contraste en tabla de alumnos
- Botones con colores semánticos (verde=activar, rojo=desactivar, amarillo=editar)
- Estados visuales más claros y profesionales
- Hover effects mejorados

### 📊 Base de Datos
**Cambios en tabla `alumnos`:**
```sql
+ activo TINYINT(1) DEFAULT 1
```

### 🔄 Migración desde Versión 1.0
Si ya tienes instalada la versión anterior:

1. Importa `migracion_activo.sql` en tu base de datos
2. Reemplaza todos los archivos PHP
3. Los alumnos existentes se marcarán como activos automáticamente

### ✅ Compatibilidad
- Compatible con versión anterior
- No requiere reinstalación completa
- Migración sin pérdida de datos

---

## Versión 1.0 - Febrero 2026

### Funcionalidades Iniciales
- Registro de grupos con nomenclatura automática
- Registro de alumnos
- Listado de alumnos registrados
- Eliminación de alumnos
- Sistema de gestión básico

---

**Fecha de actualización: 4 de Febrero de 2026**
**Desarrollador: Sistema de Gestión Escolar**
