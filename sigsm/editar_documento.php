<?php
require_once 'conexion.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: documentos.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM documento WHERE id = :id AND activo = 1");
$stmt->execute([':id' => $id]);
$doc = $stmt->fetch();

if (!$doc) {
    header('Location: documentos.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $cedula = trim($_POST['cedula_paciente'] ?? '');
    $fecha = $_POST['fecha_emision'] ?? '';
    $ruta = trim($_POST['ruta_archivo'] ?? '');

    if ($titulo !== '' && in_array($tipo, ['indicacion', 'informacion']) && $cedula !== '' && $fecha !== '' && $ruta !== '') {
        $stmt = $pdo->prepare("UPDATE documento SET titulo = :titulo, tipo = :tipo, cedula_paciente = :cedula, fecha_emision = :fecha, ruta_archivo = :ruta WHERE id = :id");
        $stmt->execute([
            ':titulo' => $titulo,
            ':tipo' => $tipo,
            ':cedula' => $cedula,
            ':fecha' => $fecha,
            ':ruta' => $ruta,
            ':id' => $id
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
    <title>Editar Documento</title>
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
                    <h4 class="mb-4">Editar Documento</h4>
                    <form method="POST" action="editar_documento.php?id=<?= $id ?>">
                        <div class="mb-3">
                            <label class="form-label">Titulo</label>
                            <input type="text" name="titulo" class="form-control" required maxlength="150" value="<?= htmlspecialchars($doc['titulo']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-select" required>
                                <option value="indicacion" <?= $doc['tipo'] === 'indicacion' ? 'selected' : '' ?>>Indicacion</option>
                                <option value="informacion" <?= $doc['tipo'] === 'informacion' ? 'selected' : '' ?>>Informacion</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cedula Paciente</label>
                            <input type="text" name="cedula_paciente" class="form-control" required maxlength="8" value="<?= htmlspecialchars($doc['cedula_paciente']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Emision</label>
                            <input type="date" name="fecha_emision" class="form-control" required value="<?= htmlspecialchars($doc['fecha_emision']) ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Ruta Archivo</label>
                            <input type="text" name="ruta_archivo" class="form-control" required maxlength="255" value="<?= htmlspecialchars($doc['ruta_archivo']) ?>">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>