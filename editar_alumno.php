<?php
require_once 'config.php';

$message = '';
$messageType = '';
$alumno = null;

// Verificar que se recibió el ID
if (!isset($_GET['id'])) {
    header('Location: alumnos_registrados.php');
    exit;
}

$conn = getConnection();
$id = sanitize($conn, $_GET['id']);

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = sanitize($conn, $_POST['nombre']);
    $apellido_p = sanitize($conn, $_POST['apellido_p']);
    $apellido_m = sanitize($conn, $_POST['apellido_m']);
    $grupo_id = sanitize($conn, $_POST['grupo']);
    
    $query = "UPDATE alumnos SET nombre = ?, apellido_p = ?, apellido_m = ?, grupo_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssii", $nombre, $apellido_p, $apellido_m, $grupo_id, $id);
    
    if ($stmt->execute()) {
        $message = "Alumno actualizado exitosamente";
        $messageType = 'success';
    } else {
        $message = "Error al actualizar el alumno";
        $messageType = 'danger';
    }
}

// Obtener datos del alumno
$query = "SELECT * FROM alumnos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$alumno = $result->fetch_assoc();

if (!$alumno) {
    header('Location: alumnos_registrados.php');
    exit;
}

// Obtener grupos disponibles
$grupos = $conn->query("SELECT * FROM grupos ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.45);
        max-width: 600px;
        margin: 0 auto;
        background: #111827;
    }
    
    .card-header {
        background: #020617;
        color: #e5e7eb;
        border-radius: 16px 16px 0 0 !important;
        padding: 1.5rem;
        border: none;
    }
    
    .card-header h4 {
        margin: 0;
        font-weight: 600;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #e5e7eb;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #1e293b;
        background: #020617;
        color: #e5e7eb;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .form-control::placeholder {
        color: #64748b;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        background: #020617;
        color: #e5e7eb;
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
        transition: all 0.3s ease;
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
    }
    
    .btn-secondary:hover {
        background: #4b5563;
        color: #e5e7eb;
    }
    
    .alert {
        border-radius: 8px;
        border: none;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>✏️ Editar Alumno</h4>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo htmlspecialchars($alumno['nombre']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellido_p" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_p" name="apellido_p" 
                               value="<?php echo htmlspecialchars($alumno['apellido_p']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellido_m" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_m" name="apellido_m" 
                               value="<?php echo htmlspecialchars($alumno['apellido_m']); ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="grupo" class="form-label">Grupo</label>
                        <select class="form-select" id="grupo" name="grupo" required>
                            <option value="">Seleccione un grupo</option>
                            <?php while ($grupo = $grupos->fetch_assoc()): ?>
                                <option value="<?php echo $grupo['id']; ?>" 
                                        <?php echo ($grupo['id'] == $alumno['grupo_id']) ? 'selected' : ''; ?>>
                                    <?php echo $grupo['nombre']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mb-3">Guardar Cambios</button>
                    <a href="alumnos_registrados.php" class="btn btn-secondary w-100">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php closeConnection($conn); ?>
