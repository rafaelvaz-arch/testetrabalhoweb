<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Vet - Sistema A3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/PROJETO-A3/index.php">🐾 VetSystem A3</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/PROJETO-A3/index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="/PROJETO-A3/consultas/index.php">Consultas</a></li>
                <li class="nav-item"><a class="nav-link" href="/PROJETO-A3/relatorios/gerar_pdf.php" target="_blank">Relatório PDF</a></li>
            </ul>
            <div class="d-flex align-items-center text-white">
                <span class="me-3 small">Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?></strong></span>
                <a href="/PROJETO-A3/auth/logout.php" class="btn btn-outline-light btn-sm">Sair</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">