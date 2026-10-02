<?php
require_once 'conexion.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE documento SET activo = 0 WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header('Location: documentos.php');
exit();
?>