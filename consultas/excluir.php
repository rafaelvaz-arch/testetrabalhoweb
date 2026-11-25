<?php
require_once '../includes/auth_check.php';
require_once '../config/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM consultas WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: index.php");
exit;