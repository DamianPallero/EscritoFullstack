<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $cedula = trim($_POST['cedula_paciente'] ?? '');
    $fecha = $_POST['fecha_emision'] ?? '';
    $ruta = trim($_POST['ruta_archivo'] ?? '');

    if ($titulo !== '' && in_array($tipo, ['indicacion', 'informacion']) && $cedula !== '' && $fecha !== '' && $ruta !== '') {
        $stmt = $pdo->prepare("INSERT INTO documento (titulo, tipo, cedula_paciente, fecha_emision, ruta_archivo, activo) VALUES (:titulo, :tipo, :cedula, :fecha, :ruta, 1)");
        $stmt->execute([
            ':titulo' => $titulo,
            ':tipo' => $tipo,
            ':cedula' => $cedula,
            ':fecha' => $fecha,
            ':ruta' => $ruta
        ]);
        header('Location: documentos.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a href="documentos.php" class="navbar-brand mb-0 h1">&larr; Volver</a>
    </div>
</nav>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-4">Nuevo Documento</h4>
                    <form method="POST" action="alta_documento.php">
                        <div class="mb-3">
                            <label class="form-label">Titulo</label>
                            <input type="text" name="titulo" class="form-control" required maxlength="150">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-select" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="indicacion">Indicacion</option>
                                <option value="informacion">Informacion</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cedula Paciente</label>
                            <input type="text" name="cedula_paciente" class="form-control" required maxlength="8">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Emision</label>
                            <input type="date" name="fecha_emision" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Ruta Archivo</label>
                            <input type="text" name="ruta_archivo" class="form-control" required maxlength="255">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>