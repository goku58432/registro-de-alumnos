<?php
require_once 'config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getConnection();
    
    $carrera_id = sanitize($conn, $_POST['carrera']);
    $turno_id = sanitize($conn, $_POST['turno']);
    $grado_id = sanitize($conn, $_POST['grado']);
    
    $query = "SELECT c.codigo as carrera_codigo, t.codigo as turno_codigo, g.numero as grado_numero 
              FROM carreras c, turnos t, grados g 
              WHERE c.id = ? AND t.id = ? AND g.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $carrera_id, $turno_id, $grado_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    if ($data) {
        $check_query = "SELECT COUNT(*) as total FROM grupos 
                       WHERE carrera_id = ? AND turno_id = ? AND grado_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("iii", $carrera_id, $turno_id, $grado_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $count_data = $check_result->fetch_assoc();
        $numero_grupo = $count_data['total'] + 1;
        $numero_grupo_formatted = str_pad($numero_grupo, 2, '0', STR_PAD_LEFT);
        
        $nombre_grupo = strtoupper($data['carrera_codigo']) . 
                       $data['grado_numero'] . 
                       $numero_grupo_formatted . '-' . 
                       strtoupper($data['turno_codigo']);
        
        $insert_query = "INSERT INTO grupos (nombre, carrera_id, turno_id, grado_id, numero_grupo) 
                        VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("siiii", $nombre_grupo, $carrera_id, $turno_id, $grado_id, $numero_grupo);
        
        if ($insert_stmt->execute()) {
            $message = "Grupo $nombre_grupo registrado exitosamente";
            $messageType = 'success';
            $_POST = array(); // Limpiar formulario
        } else {
            $message = "Error al registrar el grupo";
            $messageType = 'danger';
        }
    }
    
    closeConnection($conn);
}

// Obtener datos para los select - SOLO ACTIVOS
$conn = getConnection();
$carreras = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre");
$turnos = $conn->query("SELECT * FROM turnos WHERE activo = 1 ORDER BY nombre");
$grados = $conn->query("SELECT * FROM grados WHERE activo = 1 ORDER BY numero");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Grupo</title>
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
        text-decoration: none;
        display: inline-block;
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
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
        color: #e5e7eb;
    }
    
    /* Para el preview del grupo */
    #grupo-preview {
        background: #020617;
        border: 1px solid #1e293b;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 600;
        color: #2563eb;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
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
    
    /* Toast Notifications */
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
</style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>📚 Registrar Grupo</h4>
            </div>
            <div class="card-body">
                <form method="POST" id="grupoForm">
                    <div class="mb-3">
                        <label for="carrera" class="form-label">Carrera</label>
                        <select class="form-select" id="carrera" name="carrera" required>
                            <option value="">Seleccione una carrera</option>
                            <?php while ($carrera = $carreras->fetch_assoc()): ?>
                                <option value="<?php echo $carrera['id']; ?>" 
                                        data-codigo="<?php echo $carrera['codigo']; ?>">
                                    <?php echo $carrera['nombre']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="turno" class="form-label">Turno</label>
                        <select class="form-select" id="turno" name="turno" required>
                            <option value="">Seleccione un turno</option>
                            <?php while ($turno = $turnos->fetch_assoc()): ?>
                                <option value="<?php echo $turno['id']; ?>" 
                                        data-codigo="<?php echo $turno['codigo']; ?>">
                                    <?php echo $turno['nombre']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="grado" class="form-label">Grado</label>
                        <select class="form-select" id="grado" name="grado" required>
                            <option value="">Seleccione un grado</option>
                            <?php while ($grado = $grados->fetch_assoc()): ?>
                                <option value="<?php echo $grado['id']; ?>" 
                                        data-numero="<?php echo $grado['numero']; ?>">
                                    <?php echo $grado['descripcion']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Vista Previa del Grupo</label>
                        <div id="grupo-preview">-</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mb-3">Registrar Grupo</button>
                    <a href="index.php" class="btn btn-secondary w-100">Volver al Inicio</a>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Toast Notifications
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
        
        // Preview del grupo
        document.addEventListener('DOMContentLoaded', function() {
            const carreraSelect = document.getElementById('carrera');
            const turnoSelect = document.getElementById('turno');
            const gradoSelect = document.getElementById('grado');
            const preview = document.getElementById('grupo-preview');
            
            function updatePreview() {
                const carrera = carreraSelect.options[carreraSelect.selectedIndex];
                const turno = turnoSelect.options[turnoSelect.selectedIndex];
                const grado = gradoSelect.options[gradoSelect.selectedIndex];
                
                if (carrera.value && turno.value && grado.value) {
                    const carreraCodigo = carrera.dataset.codigo;
                    const turnoCodigo = turno.dataset.codigo;
                    const gradoNumero = grado.dataset.numero;
                    
                    preview.textContent = carreraCodigo + gradoNumero + '0X-' + turnoCodigo;
                } else {
                    preview.textContent = '-';
                }
            }
            
            carreraSelect.addEventListener('change', updatePreview);
            turnoSelect.addEventListener('change', updatePreview);
            gradoSelect.addEventListener('change', updatePreview);
            
            // Mostrar mensaje del servidor si existe
            <?php if ($message): ?>
                showToast('<?php echo addslashes($message); ?>', '<?php echo $messageType; ?>');
            <?php endif; ?>
            
            // Limpiar formulario después de registro exitoso
            <?php if ($messageType === 'success'): ?>
                document.getElementById('grupoForm').reset();
                preview.textContent = '-';
            <?php endif; ?>
        });
    </script>
</body>
</html>
<?php closeConnection($conn); ?>