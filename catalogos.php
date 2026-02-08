<?php
require_once 'config.php';

$message = '';
$messageType = '';
$conn = getConnection();

// Manejar acciones de habilitar/inhabilitar
if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['type'])) {
    $id = sanitize($conn, $_GET['id']);
    $action = $_GET['action'];
    $type = $_GET['type'];
    
    if ($action === 'toggle') {
        if ($type === 'carrera') {
            $query = "UPDATE carreras SET activo = IF(activo = 1, 0, 1) WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Inhabilitar alumnos de grupos con esta carrera si se está desactivando
            $conn->query("
                UPDATE alumnos a
                INNER JOIN grupos g ON a.grupo_id = g.id
                INNER JOIN carreras c ON g.carrera_id = c.id
                SET a.activo = 0
                WHERE c.activo = 0
            ");
            
            // Activar alumnos si la carrera, turno y grado están activos
            $conn->query("
                UPDATE alumnos a
                INNER JOIN grupos g ON a.grupo_id = g.id
                INNER JOIN carreras c ON g.carrera_id = c.id
                INNER JOIN turnos t ON g.turno_id = t.id
                INNER JOIN grados gr ON g.grado_id = gr.id
                SET a.activo = 1
                WHERE c.activo = 1 AND t.activo = 1 AND gr.activo = 1
            ");
            
            $message = "Carrera actualizada exitosamente";
            $messageType = 'success';
            
        } elseif ($type === 'turno') {
            $query = "UPDATE turnos SET activo = IF(activo = 1, 0, 1) WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Inhabilitar alumnos de grupos con este turno si se está desactivando
            $conn->query("
                UPDATE alumnos a
                INNER JOIN grupos g ON a.grupo_id = g.id
                INNER JOIN turnos t ON g.turno_id = t.id
                SET a.activo = 0
                WHERE t.activo = 0
            ");
            
            // Activar alumnos si la carrera, turno y grado están activos
            $conn->query("
                UPDATE alumnos a
                INNER JOIN grupos g ON a.grupo_id = g.id
                INNER JOIN carreras c ON g.carrera_id = c.id
                INNER JOIN turnos t ON g.turno_id = t.id
                INNER JOIN grados gr ON g.grado_id = gr.id
                SET a.activo = 1
                WHERE c.activo = 1 AND t.activo = 1 AND gr.activo = 1
            ");
            
            $message = "Turno actualizado exitosamente";
            $messageType = 'success';
            
        } elseif ($type === 'grado') {
            $query = "UPDATE grados SET activo = IF(activo = 1, 0, 1) WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Inhabilitar alumnos de grupos con este grado si se está desactivando
            $conn->query("
                UPDATE alumnos a
                INNER JOIN grupos g ON a.grupo_id = g.id
                INNER JOIN grados gr ON g.grado_id = gr.id
                SET a.activo = 0
                WHERE gr.activo = 0
            ");
            
            // Activar alumnos si la carrera, turno y grado están activos
            $conn->query("
                UPDATE alumnos a
                INNER JOIN grupos g ON a.grupo_id = g.id
                INNER JOIN carreras c ON g.carrera_id = c.id
                INNER JOIN turnos t ON g.turno_id = t.id
                INNER JOIN grados gr ON g.grado_id = gr.id
                SET a.activo = 1
                WHERE c.activo = 1 AND t.activo = 1 AND gr.activo = 1
            ");
            
            $message = "Grado actualizado exitosamente";
            $messageType = 'success';
        }
        
        // Redirigir sin los parámetros GET
        header('Location: catalogos.php?tab=' . $type . 's&msg=' . urlencode($message) . '&type=' . $messageType);
        exit;
    }
}

// Procesar formulario de nueva carrera
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar') {
    $nombre = sanitize($conn, $_POST['nombre']);
    $codigo = sanitize($conn, strtoupper($_POST['codigo']));
    
    // Verificar que el código no exista
    $check = $conn->prepare("SELECT id FROM carreras WHERE codigo = ?");
    $check->bind_param("s", $codigo);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $message = "Error: El código de carrera ya existe";
        $messageType = 'danger';
    } else {
        $query = "INSERT INTO carreras (nombre, codigo, activo) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nombre, $codigo);
        
        if ($stmt->execute()) {
            $message = "Carrera agregada exitosamente";
            $messageType = 'success';
        } else {
            $message = "Error al agregar la carrera";
            $messageType = 'danger';
        }
    }
}

// Capturar mensaje de la URL si existe
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = $_GET['msg'];
    $messageType = $_GET['type'];
}

// Obtener todas las carreras, turnos y grados
$carreras = $conn->query("SELECT * FROM carreras ORDER BY nombre");
$turnos = $conn->query("SELECT * FROM turnos ORDER BY nombre");
$grados = $conn->query("SELECT * FROM grados ORDER BY numero");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogos - Sistema de Alumnos</title>
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
    
    .page-title {
        color: #e5e7eb;
        text-align: center;
        margin-bottom: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.45);
        margin-bottom: 2rem;
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
    
    .form-control {
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
    
    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        background: #020617;
        color: #e5e7eb;
        outline: none;
    }
    
    .btn-primary {
        background: #2563eb;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
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
    
    .btn-danger {
        background-color: #dc2626;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-danger:hover {
        background-color: #b91c1c;
        transform: translateY(-2px);
    }
    
    .btn-success {
        background-color: #16a34a;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-success:hover {
        background-color: #15803d;
        transform: translateY(-2px);
    }
    
    .btn-sm {
        padding: 0.5rem 0.9rem;
        font-size: 0.875rem;
    }
    
    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
        display: inline-block;
    }
    
    .badge-primary {
        background: #2563eb;
        color: white;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .p-0 {
        padding: 0 !important;
    }
    
    .mb-3 {
        margin-bottom: 1rem;
    }
    
    .mt-4 {
        margin-top: 1.5rem;
    }
    
    .text-center {
        text-align: center;
    }
    
    /* Tabs Navigation */
    .nav-tabs {
        display: flex;
        border-bottom: 2px solid #1e293b;
        margin-bottom: 2rem;
        list-style: none;
    }
    
    .nav-item {
        flex: 1;
    }
    
    .nav-link {
        display: block;
        padding: 1rem;
        text-align: center;
        color: #94a3b8;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        font-weight: 500;
        background: transparent;
        border: none;
        width: 100%;
        cursor: pointer;
    }
    
    .nav-link:hover {
        color: #e5e7eb;
        background: rgba(37, 99, 235, 0.1);
    }
    
    .nav-link.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
        background: rgba(37, 99, 235, 0.1);
    }
    
    .tab-content {
        display: block;
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        background: #111827;
        color: #e5e7eb;
    }
    
    thead {
        background: #1e293b;
    }
    
    thead th {
        font-weight: 600;
        color: #94a3b8;
        border: none;
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-align: left;
    }
    
    tbody {
        background: #111827;
    }
    
    tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid #1e293b;
        color: #e5e7eb;
    }
    
    tbody tr {
        transition: all 0.2s ease;
    }
    
    tbody tr.item-activo {
        background-color: #064e3b;
    }
    
    tbody tr.item-activo:hover {
        background-color: #065f46;
    }
    
    tbody tr.item-inactivo {
        background-color: #7f1d1d;
        opacity: 0.8;
    }
    
    tbody tr.item-inactivo:hover {
        background-color: #991b1b;
        opacity: 0.9;
    }
    
    .table-responsive {
        background: #111827;
        border-radius: 0 0 16px 16px;
        overflow-x: auto;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.5rem;
    }
    
    .col-md-8, .col-md-4 {
        padding: 0 0.5rem;
    }
    
    .col-md-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
    }
    
    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
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
    
    /* Custom Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 10000;
        animation: fadeIn 0.2s ease;
    }
    
    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes scaleIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .modal-content {
        background: #111827;
        border-radius: 16px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.8);
        animation: scaleIn 0.3s ease;
    }
    
    .modal-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }
    
    .modal-icon.success {
        background: rgba(22, 163, 74, 0.2);
        border: 3px solid #16a34a;
        color: #16a34a;
    }
    
    .modal-icon.warning {
        background: rgba(220, 38, 38, 0.2);
        border: 3px solid #dc2626;
        color: #dc2626;
    }
    
    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #e5e7eb;
    }
    
    .modal-text {
        color: #94a3b8;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    
    .modal-btn {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }
    
    .modal-btn-confirm {
        background: #2563eb;
        color: white;
    }
    
    .modal-btn-confirm:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }
    
    .modal-btn-cancel {
        background: #374151;
        color: #e5e7eb;
    }
    
    .modal-btn-cancel:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .col-md-8, .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .nav-link {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .modal-content {
            padding: 1.5rem;
        }
        
        .modal-buttons {
            flex-direction: column;
        }
        
        .modal-btn {
            width: 100%;
        }
    }
</style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Custom Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-content">
            <div class="modal-icon warning" id="modalIcon">
                ❌
            </div>
            <h3 class="modal-title" id="modalTitle">¿Está seguro?</h3>
            <p class="modal-text" id="modalText">Esta acción afectará a los alumnos relacionados.</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancelar</button>
                <button class="modal-btn modal-btn-confirm" id="confirmBtn">Confirmar</button>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">⚙️ Administración de Catálogos</h1>
        
        <!-- Tabs -->
        <ul class="nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-tab="carreras" onclick="switchTab(event, 'carreras')">
                    📚 Carreras
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-tab="turnos" onclick="switchTab(event, 'turnos')">
                    🕐 Turnos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-tab="grados" onclick="switchTab(event, 'grados')">
                    📖 Grados
                </button>
            </li>
        </ul>
        
        <div class="tab-content">
            <!-- PESTAÑA DE CARRERAS -->
            <div class="tab-pane active" id="carreras" role="tabpanel">
                <!-- Formulario para agregar carrera -->
                <div class="card">
                    <div class="card-header">
                        <h4>➕ Agregar Nueva Carrera</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="agregar">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="nombre" class="form-label">Nombre de la Carrera</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           placeholder="Ej: Ingeniería en Sistemas Computacionales" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="codigo" class="form-label">Código (3 letras)</label>
                                    <input type="text" class="form-control" id="codigo" name="codigo" 
                                           placeholder="Ej: ISC" maxlength="3" required 
                                           style="text-transform: uppercase;">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                ✅ Agregar Carrera
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Tabla de Carreras -->
                <div class="card">
                    <div class="card-header">
                        <h4>📚 Carreras Registradas</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Carrera</th>
                                        <th>Desactivar</th>
                                        <th>Activar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($carrera = $carreras->fetch_assoc()): ?>
                                    <tr class="<?php echo $carrera['activo'] == 1 ? 'item-activo' : 'item-inactivo'; ?>">
                                        <td>
                                            <strong><?php echo htmlspecialchars($carrera['nombre']); ?></strong>
                                            <br>
                                            <span class="badge badge-primary">
                                                <?php echo htmlspecialchars($carrera['codigo']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($carrera['activo'] == 1): ?>
                                                <a href="#" class="btn btn-danger btn-sm"
                                                   onclick="showConfirmModal(event, '?action=toggle&id=<?php echo $carrera['id']; ?>&type=carrera', '¿Desea desactivar esta carrera?', 'Los alumnos asociados también se desactivarán.')">
                                                    ❌
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($carrera['activo'] == 0): ?>
                                                <a href="#" class="btn btn-success btn-sm"
                                                   onclick="showConfirmModal(event, '?action=toggle&id=<?php echo $carrera['id']; ?>&type=carrera', '¿Desea activar esta carrera?', 'Los alumnos se activarán si su turno y grado también están activos.', true)">
                                                    ✅
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- PESTAÑA DE TURNOS -->
            <div class="tab-pane" id="turnos" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>🕐 Turnos Disponibles</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Turno</th>
                                        <th>Desactivar</th>
                                        <th>Activar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($turno = $turnos->fetch_assoc()): ?>
                                    <tr class="<?php echo $turno['activo'] == 1 ? 'item-activo' : 'item-inactivo'; ?>">
                                        <td>
                                            <strong><?php echo htmlspecialchars($turno['nombre']); ?></strong>
                                            <br>
                                            <span class="badge badge-primary">
                                                <?php echo htmlspecialchars($turno['codigo']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($turno['activo'] == 1): ?>
                                                <a href="#" class="btn btn-danger btn-sm"
                                                   onclick="showConfirmModal(event, '?action=toggle&id=<?php echo $turno['id']; ?>&type=turno', '¿Desea desactivar este turno?', 'Los alumnos asociados también se desactivarán.')">
                                                    ❌
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($turno['activo'] == 0): ?>
                                                <a href="#" class="btn btn-success btn-sm"
                                                   onclick="showConfirmModal(event, '?action=toggle&id=<?php echo $turno['id']; ?>&type=turno', '¿Desea activar este turno?', 'Los alumnos se activarán si su carrera y grado también están activos.', true)">
                                                    ✅
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- PESTAÑA DE GRADOS -->
            <div class="tab-pane" id="grados" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4>📖 Grados Académicos</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Grado</th>
                                        <th>Desactivar</th>
                                        <th>Activar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($grado = $grados->fetch_assoc()): ?>
                                    <tr class="<?php echo $grado['activo'] == 1 ? 'item-activo' : 'item-inactivo'; ?>">
                                        <td>
                                            <strong><?php echo $grado['numero']; ?>° - <?php echo htmlspecialchars($grado['descripcion']); ?></strong>
                                        </td>
                                        <td>
                                            <?php if ($grado['activo'] == 1): ?>
                                                <a href="#" class="btn btn-danger btn-sm"
                                                   onclick="showConfirmModal(event, '?action=toggle&id=<?php echo $grado['id']; ?>&type=grado', '¿Desea desactivar este grado?', 'Los alumnos asociados también se desactivarán.')">
                                                    ❌
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($grado['activo'] == 0): ?>
                                                <a href="#" class="btn btn-success btn-sm"
                                                   onclick="showConfirmModal(event, '?action=toggle&id=<?php echo $grado['id']; ?>&type=grado', '¿Desea activar este grado?', 'Los alumnos se activarán si su carrera y turno también están activos.', true)">
                                                    ✅
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">
                ← Volver al Inicio
            </a>
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
        
        // Custom Confirm Modal
        let confirmAction = null;
        
        function showConfirmModal(event, url, title, text, isActivate = false) {
            event.preventDefault();
            
            const modal = document.getElementById('confirmModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalText = document.getElementById('modalText');
            const modalIcon = document.getElementById('modalIcon');
            const confirmBtn = document.getElementById('confirmBtn');
            
            modalTitle.textContent = title;
            modalText.textContent = text;
            
            if (isActivate) {
                modalIcon.innerHTML = '✅';
                modalIcon.className = 'modal-icon success';
            } else {
                modalIcon.innerHTML = '❌';
                modalIcon.className = 'modal-icon warning';
            }
            
            confirmAction = function() {
                window.location.href = url;
            };
            
            modal.classList.add('active');
        }
        
        function closeModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('active');
            confirmAction = null;
        }
        
        document.getElementById('confirmBtn').addEventListener('click', function() {
            if (confirmAction) {
                confirmAction();
            }
        });
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Tab Navigation
        function switchTab(event, tabId) {
            const panes = document.querySelectorAll('.tab-pane');
            panes.forEach(pane => pane.classList.remove('active'));
            
            const links = document.querySelectorAll('.nav-link');
            links.forEach(link => link.classList.remove('active'));
            
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        
        // Manejar mensajes y tabs desde URL
        window.addEventListener('DOMContentLoaded', function() {
            <?php if ($message): ?>
                showToast('<?php echo addslashes($message); ?>', '<?php echo $messageType; ?>');
            <?php endif; ?>
            
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            
            if (tab && document.getElementById(tab)) {
                const tabButton = document.querySelector(`[data-tab="${tab}"]`);
                if (tabButton) {
                    tabButton.click();
                }
            }
        });
    </script>
</body>
</html>
<?php closeConnection($conn); ?>