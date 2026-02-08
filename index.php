<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Alumnos</title>
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
    
    .container {
        max-width: 1200px;
    }
    
    h1 {
        color: #e5e7eb;
        text-align: center;
        margin-bottom: 3rem;
        font-weight: 700;
        font-size: 2.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .card {
        background: #111827;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        text-decoration: none;
        color: #e5e7eb;
        transition: all 0.3s ease;
        box-shadow: 0 10px 40px rgba(0,0,0,0.45);
        border: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        color: #2563eb;
    }
    
    .card-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .card h3 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }
    
    .card p {
        color: #9ca3af;
        font-size: 0.95rem;
    }
    
    @media (max-width: 768px) {
        .cards-container {
            grid-template-columns: 1fr;
        }
        
        h1 {
            font-size: 2rem;
        }
    }
</style>
</head>
<body>
    <div class="container">
        <h1>📚 Sistema de Gestión de Alumnos</h1>
        
        <div class="cards-container">
            <a href="registrar_grupo.php" class="card">
                <div class="card-icon">📚</div>
                <h3>Registrar Grupo</h3>
                <p>Crear nuevos grupos académicos</p>
            </a>
            
            <a href="registrar_alumno.php" class="card">
                <div class="card-icon">👨‍🎓</div>
                <h3>Registrar Alumno</h3>
                <p>Inscribir nuevos estudiantes</p>
            </a>
            
            <a href="alumnos_registrados.php" class="card">
                <div class="card-icon">👥</div>
                <h3>Alumnos Registrados</h3>
                <p>Ver lista de estudiantes</p>
            </a>
            
            <a href="catalogos.php" class="card">
                <div class="card-icon">⚙️</div>
                <h3>Catálogos</h3>
                <p>Gestionar carreras y configuración</p>
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
