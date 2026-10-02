<?php
require_once 'conexion.php';

$stmt = $pdo->query("SELECT id, titulo, tipo, cedula_paciente, fecha_emision, ruta_archivo FROM documento WHERE activo = 1 ORDER BY fecha_emision DESC");
$documentos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand mb-0 h1">Gestión de Documentos</span>
    </div>
</nav>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Documentos activos</h4>
        <a href="alta_documento.php" class="btn btn-primary">Nuevo documento</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Título</th>
                            <th>Tipo</th>
                            <th>Cédula del paciente</th>
                            <th>Fecha de emisión</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($documentos)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay documentos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($documentos as $doc): ?>
                                <tr>
                                    <td class="ps-3 fw-medium"><?= htmlspecialchars($doc['titulo']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($doc['tipo']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($doc['cedula_paciente']) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($doc['fecha_emision']))) ?></td>
                                    <td class="text-end pe-3">
                                        <a href="editar_documento.php?id=<?= (int)$doc['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                        <a href="bajar_documento.php?id=<?= (int)$doc['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Está seguro de dar de baja este documento?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>