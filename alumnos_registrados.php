<?php
require_once 'config.php';

$conn = getConnection();

$message = '';
$messageType = '';

// Manejar acciones de habilitar/inhabilitar
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = sanitize($conn, $_GET['id']);
    $action = $_GET['action'];
    
    if ($action === 'toggle') {
        $query = "UPDATE alumnos SET activo = IF(activo = 1, 0, 1) WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Estado del alumno actualizado exitosamente";
            $messageType = 'success';
        } else {
            $message = "Error al actualizar el estado del alumno";
            $messageType = 'danger';
        }
        
        header('Location: alumnos_registrados.php?msg=' . urlencode($message) . '&type=' . $messageType);
        exit;
    }
}

// Capturar mensaje de la URL si existe
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = $_GET['msg'];
    $messageType = $_GET['type'];
}

// Consulta con la columna activo
$query = "SELECT 
            a.id,
            a.nombre,
            a.apellido_p,
            a.apellido_m,
            a.activo,
            g.nombre as grupo_nombre
          FROM alumnos a
          INNER JOIN grupos g ON a.grupo_id = g.id
          ORDER BY g.nombre, a.apellido_p, a.apellido_m, a.nombre";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos Registrados</title>
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
        padding: 0;
        background: #111827;
    }
    
    /* ESTILOS DE TABLA SIN BOOTSTRAP */
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
    
    /* Colores según estado */
    tbody tr.alumno-activo {
        background-color: #064e3b;
    }
    
    tbody tr.alumno-activo:hover {
        background-color: #065f46;
    }
    
    tbody tr.alumno-inactivo {
        background-color: #7f1d1d;
        opacity: 0.8;
    }
    
    tbody tr.alumno-inactivo:hover {
        background-color: #991b1b;
        opacity: 0.9;
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
    
    .badge-success {
        background-color: #16a34a;
        color: white;
    }
    
    .badge-danger {
        background-color: #dc2626;
        color: white;
    }
    
    .btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        border: none;
        font-family: 'Inter', sans-serif;
    }
    
    .btn-sm {
        padding: 0.5rem 0.9rem;
        font-size: 0.875rem;
    }
    
    .btn-warning {
        background-color: #f59e0b;
        color: #1f2937;
    }
    
    .btn-warning:hover {
        background-color: #d97706;
        transform: translateY(-2px);
        color: #1f2937;
    }
    
    .btn-danger {
        background-color: #dc2626;
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #b91c1c;
        transform: translateY(-2px);
    }
    
    .btn-success {
        background-color: #16a34a;
        color: white;
    }
    
    .btn-success:hover {
        background-color: #15803d;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background-color: #374151;
        color: #e5e7eb;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
        color: #e5e7eb;
    }
    
    .total-alumnos {
        background: #111827;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        text-align: center;
        font-weight: 600;
        color: #2563eb;
        font-size: 1.1rem;
    }
    
    .table-responsive {
        background: #111827;
        border-radius: 0 0 16px 16px;
        overflow-x: auto;
    }
    
    .text-center {
        text-align: center;
    }
    
    .py-5 {
        padding: 3rem 0;
    }
    
    .mt-4 {
        margin-top: 1.5rem;
    }
    
    .text-muted {
        color: #6b7280;
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
        .container {
            padding: 0 0.5rem;
        }
        
        table {
            font-size: 0.875rem;
        }
        
        thead th,
        tbody td {
            padding: 0.75rem 0.5rem;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
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
            <p class="modal-text" id="modalText">Esta acción cambiará el estado del alumno.</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancelar</button>
                <button class="modal-btn modal-btn-confirm" id="confirmBtn">Confirmar</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="total-alumnos">
            Total de alumnos registrados: <?php echo $result->num_rows; ?>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h4>👥 Alumnos Registrados</h4>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Grupo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($alumno = $result->fetch_assoc()): ?>
                            <tr class="<?php echo $alumno['activo'] == 1 ? 'alumno-activo' : 'alumno-inactivo'; ?>">
                                <td><?php echo $alumno['id']; ?></td>
                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($alumno['apellido_p'] . ' ' . 
                                                                    $alumno['apellido_m'] . ' ' . 
                                                                    $alumno['nombre']); ?>
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?php echo htmlspecialchars($alumno['grupo_nombre']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($alumno['activo'] == 1): ?>
                                        <span class="badge badge-success">ACTIVO</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">INACTIVO</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="editar_alumno.php?id=<?php echo $alumno['id']; ?>" 
                                       class="btn btn-warning btn-sm">
                                        ✏️ Editar
                                    </a>
                                    
                                    <?php if ($alumno['activo'] == 1): ?>
                                        <a href="#" class="btn btn-danger btn-sm"
                                           onclick="showConfirmModal(event, '?action=toggle&id=<?php echo $alumno['id']; ?>', '¿Desea inhabilitar este alumno?', 'El alumno será marcado como inactivo.')">
                                            ❌ Inhabilitar
                                        </a>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-success btn-sm"
                                           onclick="showConfirmModal(event, '?action=toggle&id=<?php echo $alumno['id']; ?>', '¿Desea habilitar este alumno?', 'El alumno será marcado como activo.', true)">
                                            ✅ Habilitar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted">No hay alumnos registrados</p>
                </div>
                <?php endif; ?>
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
        
        // Mostrar mensaje del servidor si existe
        window.addEventListener('DOMContentLoaded', function() {
            <?php if ($message): ?>
                showToast('<?php echo addslashes($message); ?>', '<?php echo $messageType; ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>
<?php closeConnection($conn); ?>