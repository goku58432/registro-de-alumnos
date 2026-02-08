<?php
require_once 'config.php';

$message = '';
$messageType = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getConnection();
    
    $nombre = trim(sanitize($conn, $_POST['nombre']));
    $apellido_p = trim(sanitize($conn, $_POST['apellido_p']));
    $apellido_m = trim(sanitize($conn, $_POST['apellido_m']));
    $grupo_id = sanitize($conn, $_POST['grupo']);
    
    // Validar que los campos no estén vacíos después de quitar espacios
    if (empty($nombre) || empty($apellido_p) || empty($apellido_m)) {
        $message = "Por favor, rellene bien todos los campos. No se permiten campos vacíos o solo con espacios.";
        $messageType = 'danger';
    } else {
        $query = "INSERT INTO alumnos (nombre, apellido_p, apellido_m, grupo_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $nombre, $apellido_p, $apellido_m, $grupo_id);
        
        if ($stmt->execute()) {
            $message = "Alumno registrado exitosamente";
            $messageType = 'success';
            // Limpiar el formulario después del registro exitoso
            $_POST = array();
        } else {
            $message = "Error al registrar el alumno";
            $messageType = 'danger';
        }
    }
    
    closeConnection($conn);
}

// Obtener grupos disponibles - SOLO SI CARRERA, TURNO Y GRADO ESTÁN ACTIVOS
$conn = getConnection();
$grupos = $conn->query("
    SELECT g.* 
    FROM grupos g
    INNER JOIN carreras c ON g.carrera_id = c.id
    INNER JOIN turnos t ON g.turno_id = t.id
    INNER JOIN grados gr ON g.grado_id = gr.id
    WHERE c.activo = 1 AND t.activo = 1 AND gr.activo = 1
    ORDER BY g.nombre
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alumno</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #020617, #0f172a);
        color: #e5e7eb;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.45);
        max-width: 600px;
        margin: 0 auto;
        background: #111827;
        overflow: hidden;
    }
    
    .card-header {
        background: #020617;
        color: #e5e7eb;
        padding: 1.5rem;
        border: none;
    }
    
    .card-header h4 {
        margin: 0;
        font-weight: 600;
    }
    
    .card-body {
        padding: 2rem;
        background: #111827;
    }
    
    .form-label {
        font-weight: 500;
        color: #e5e7eb;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #1e293b;
        background: #020617;
        color: #e5e7eb;
        padding: 0.75rem;
        transition: all 0.3s ease;
        width: 100%;
        font-family: 'Inter', sans-serif;
    }
    
    .form-control::placeholder {
        color: #64748b;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        background: #020617;
        color: #e5e7eb;
        outline: none;
    }
    
    .form-select option {
        background: #020617;
        color: #e5e7eb;
    }
    
    .btn-primary {
        background: #2563eb;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        width: 100%;
        transition: all 0.3s ease;
        cursor: pointer;
        color: white;
    }
    
    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(37, 99, 235, 0.4);
    }
    
    .btn-secondary {
        background: #374151;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 500;
        color: #e5e7eb;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        cursor: pointer;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
        color: #e5e7eb;
    }
    
    /* Estilos para el mensaje flotante */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .toast {
        background: #111827;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.6);
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        min-width: 300px;
        max-width: 500px;
        border-left: 4px solid;
        animation: slideIn 0.3s ease-out;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .toast.hiding {
        animation: slideOut 0.3s ease-in forwards;
    }
    
    .toast-success {
        border-left-color: #16a34a;
        background: linear-gradient(135deg, #065f46 0%, #111827 100%);
    }
    
    .toast-danger {
        border-left-color: #dc2626;
        background: linear-gradient(135deg, #7f1d1d 0%, #111827 100%);
    }
    
    .toast-body {
        color: #e5e7eb;
        font-weight: 500;
        flex: 1;
    }
    
    .toast-close {
        background: transparent;
        border: none;
        color: #e5e7eb;
        font-size: 1.5rem;
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.2s;
        padding: 0;
        margin-left: 1rem;
        line-height: 1;
    }
    
    .toast-close:hover {
        opacity: 1;
    }
    
    .mb-3 {
        margin-bottom: 1rem;
    }
    
    .mb-4 {
        margin-bottom: 1.5rem;
    }
    
    .w-100 {
        width: 100%;
    }
    
    /* Estilos para campos inválidos */
    .form-control.is-invalid {
        border-color: #dc2626;
    }
    
    .form-control.is-invalid:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
    }
</style>
</head>
<body>
    <!-- Contenedor de mensajes flotantes -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>👨‍🎓 Registrar Alumno</h4>
            </div>
            <div class="card-body">
                <form method="POST" id="alumnoForm" onsubmit="return validateForm(event)">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               placeholder="Ingrese el nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellido_p" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_p" name="apellido_p" 
                               placeholder="Ingrese el apellido paterno" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellido_m" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_m" name="apellido_m" 
                               placeholder="Ingrese el apellido materno" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="grupo" class="form-label">Grupo</label>
                        <select class="form-select" id="grupo" name="grupo" required>
                            <option value="">Seleccione un grupo</option>
                            <?php while ($grupo = $grupos->fetch_assoc()): ?>
                                <option value="<?php echo $grupo['id']; ?>">
                                    <?php echo $grupo['nombre']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mb-3">Registrar Alumno</button>
                    <a href="index.php" class="btn btn-secondary w-100">Volver al Inicio</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar mensajes flotantes
        function showToast(message, type) {
            const container = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const body = document.createElement('div');
            body.className = 'toast-body';
            body.textContent = message;
            
            const closeBtn = document.createElement('button');
            closeBtn.className = 'toast-close';
            closeBtn.innerHTML = '×';
            closeBtn.onclick = function() {
                hideToast(toast);
            };
            
            toast.appendChild(body);
            toast.appendChild(closeBtn);
            container.appendChild(toast);
            
            // Auto-ocultar después de 5 segundos
            setTimeout(function() {
                hideToast(toast);
            }, 5000);
        }
        
        function hideToast(toast) {
            toast.classList.add('hiding');
            setTimeout(function() {
                toast.remove();
            }, 300);
        }
        
        // Validar el formulario antes de enviarlo
        function validateForm(event) {
            const nombre = document.getElementById('nombre').value.trim();
            const apellidoP = document.getElementById('apellido_p').value.trim();
            const apellidoM = document.getElementById('apellido_m').value.trim();
            
            // Remover clase de error de todos los campos
            document.querySelectorAll('.form-control').forEach(function(field) {
                field.classList.remove('is-invalid');
            });
            
            let isValid = true;
            let errorMessage = '';
            
            if (!nombre) {
                document.getElementById('nombre').classList.add('is-invalid');
                isValid = false;
                errorMessage = 'El campo Nombre no puede estar vacío o contener solo espacios';
            }
            
            if (!apellidoP) {
                document.getElementById('apellido_p').classList.add('is-invalid');
                isValid = false;
                errorMessage = 'El campo Apellido Paterno no puede estar vacío o contener solo espacios';
            }
            
            if (!apellidoM) {
                document.getElementById('apellido_m').classList.add('is-invalid');
                isValid = false;
                errorMessage = 'El campo Apellido Materno no puede estar vacío o contener solo espacios';
            }
            
            if (!isValid) {
                event.preventDefault();
                showToast(errorMessage || 'Por favor, rellene bien todos los campos. No se permiten campos vacíos o solo con espacios.', 'danger');
                return false;
            }
            
            return true;
        }
        
        // Mostrar mensaje del servidor si existe
        <?php if ($message): ?>
            showToast('<?php echo addslashes($message); ?>', '<?php echo $messageType; ?>');
        <?php endif; ?>
        
        // Limpiar campos después de registro exitoso
        <?php if ($messageType === 'success'): ?>
            document.getElementById('alumnoForm').reset();
        <?php endif; ?>
    </script>
</body>
</html>
<?php closeConnection($conn); ?>