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

$errors = [];
$titulo = $_POST['titulo'] ?? $doc['titulo'];
$tipo = $_POST['tipo'] ?? $doc['tipo'];
$cedula = $_POST['cedula_paciente'] ?? $doc['cedula_paciente'];
$fecha = $_POST['fecha_emision'] ?? $doc['fecha_emision'];
$ruta = $_POST['ruta_archivo'] ?? $doc['ruta_archivo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $cedula = trim($_POST['cedula_paciente'] ?? '');
    $fecha = $_POST['fecha_emision'] ?? '';
    $ruta = trim($_POST['ruta_archivo'] ?? '');

    if ($titulo === '' || strlen($titulo) > 150) $errors[] = "El título es obligatorio y no puede superar los 150 caracteres.";
    if (!in_array($tipo, ['indicacion', 'informacion'])) $errors[] = "Debe seleccionar un tipo válido.";
    if (!preg_match('/^\d{7,8}$/', $cedula)) $errors[] = "La cédula debe tener 7 u 8 dígitos.";
    
    $fecha_parts = explode('-', $fecha);
    if (count($fecha_parts) !== 3 || !checkdate((int)$fecha_parts[1], (int)$fecha_parts[2], (int)$fecha_parts[0])) {
        $errors[] = "La fecha de emisión no es válida.";
    }
    if (substr($ruta, 0, 11) !== 'documentos/' || substr($ruta, -4) !== '.pdf') {
        $errors[] = "La ruta debe empezar con 'documentos/' y terminar en '.pdf'.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE documento SET titulo = :titulo, tipo = :tipo, cedula_paciente = :cedula, fecha_emision = :fecha, ruta_archivo = :ruta WHERE id = :id");
            $stmt->execute([
                ':titulo' => $titulo,
                ':tipo' => $tipo,
                ':cedula' => $cedula,
                ':fecha' => $fecha,
                ':ruta' => $ruta,
                ':id' => $id
            ]);
            header('Location: documentos.php?ok=2');
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "La ruta del archivo ya existe en otro documento.";
            } else {
                $errors[] = "Error de base de datos: " . $e->getMessage();
            }
        }
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

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="editar_documento.php?id=<?= $id ?>">
    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" required maxlength="150" value="<?= htmlspecialchars($titulo) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Tipo</label>
        <select name="tipo" class="form-select" required>
            <option value="indicacion" <?= $tipo === 'indicacion' ? 'selected' : '' ?>>Indicación</option>
            <option value="informacion" <?= $tipo === 'informacion' ? 'selected' : '' ?>>Información</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Cédula Paciente</label>
        <input type="text" name="cedula_paciente" class="form-control" required maxlength="8" value="<?= htmlspecialchars($cedula) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Fecha Emisión</label>
        <input type="date" name="fecha_emision" class="form-control" required value="<?= htmlspecialchars($fecha) ?>">
    </div>
    <div class="mb-4">
        <label class="form-label">Ruta Archivo</label>
        <input type="text" name="ruta_archivo" class="form-control" required maxlength="255" value="<?= htmlspecialchars($ruta) ?>">
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