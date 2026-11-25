<?php
require_once 'includes/auth_check.php';
require_once 'config/conexao.php';
require_once 'includes/header.php';

// Contadores rápidos para o Dashboard
$totalPets = $pdo->query("SELECT COUNT(*) FROM pets")->fetchColumn();
$totalConsultas = $pdo->query("SELECT COUNT(*) FROM consultas")->fetchColumn();
$faturamento = $pdo->query("SELECT COALESCE(SUM(valor), 0) FROM consultas WHERE status = 'Concluída'")->fetchColumn();
?>

<h2 class="mb-4">Painel Administrativo</h2>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-primary shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Pets Cadastrados</h6>
                <h2 class="text-primary fw-bold"><?= $totalPets ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total de Consultas</h6>
                <h2 class="text-success fw-bold"><?= $totalConsultas ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-dark shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Faturamento (Concluídas)</h6>
                <h2 class="text-dark fw-bold">R$ <?= number_format($faturamento, 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5>Acesso Rápido</h5>
        <p class="text-muted">Gerencie os atendimentos ou gere o relatório consolidado em PDF.</p>
        <a href="consultas/index.php" class="btn btn-primary me-2">Gerenciar Consultas</a>
        <a href="relatorios/gerar_pdf.php" target="_blank" class="btn btn-danger">Emitir Relatório PDF</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>