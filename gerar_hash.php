<?php
require_once 'config/conexao.php';
$hash = password_hash('rf123456', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE usuarios SET senha = :senha WHERE email = 'rafael.vaz@sempreceub.com'");
$stmt->execute([':senha' => $hash]);
echo "Senha criptografada com sucesso! Já pode apagar este ficheiro.";
?>