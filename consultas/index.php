<?php
require_once '../includes/auth_check.php';
require_once '../config/conexao.php';
require_once '../includes/header.php';

// Busca avançada com 3 filtros de tipos diferentes:
// 1. Texto (Nome do Pet)
// 2. Seleção (Status ENUM)
// 3. Data (Data da consulta)
$where = ["1=1"];
$params = [];

if (!empty($_GET['pet'])) {
    $where[] = "p.nome LIKE :pet";
    $params[':pet'] = '%' . $_GET['pet'] . '%';
}
if (!empty($_GET['status'])) {
    $where[] = "c.status = :status";
    $params[':status'] = $_GET['status'];
}
if (!empty($_GET['data'])) {
    $where[] = "DATE(c.data_consulta) = :data";
    $params[':data'] = $_GET['data'];
}

$sql = "SELECT c.*, p.nome AS pet_nome, t.nome AS tutor_nome, v.nome AS vet_nome 
        FROM consultas c
        INNER JOIN pets p ON c.pet_id = p.id
        INNER JOIN tutores t ON p.tutor_id = t.id
        INNER JOIN veterinarios v ON c.veterinario_id = v.id
        WHERE " . implode(" AND ", $where) . "
        ORDER BY c.data_consulta DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$consultas = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gerenciamento de Consultas</h3>
    <a href="criar.php" class="btn btn-success">+ Nova Consulta</a>
</div>

<!-- Formulário de Busca Avançada (3 Filtros) -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filtro 1: Nome do Pet (Texto)</label>
                <input type="text" name="pet" class="form-control" placeholder="Ex: Rex" value="<?= htmlspecialchars($_GET['pet'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Filtro 2: Status (Seleção)</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="Agendada" <?= ($_GET['status'] ?? '') === 'Agendada' ? 'selected' : '' ?>>Agendada</option>
                    <option value="Concluída" <?= ($_GET['status'] ?? '') === 'Concluída' ? 'selected' : '' ?>>Concluída</option>
                    <option value="Cancelada" <?= ($_GET['status'] ?? '') === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filtro 3: Data (Calendário)</label>
                <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($_GET['data'] ?? '') ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                <a href="index.php" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de Resultados -->
<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Data/Hora</th>
                <th>Pet (Tutor)</th>
                <th>Veterinário</th>
                <th>Valor</th>
                <th>Status</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($consultas) === 0): ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">Nenhuma consulta encontrada.</td></tr>
            <?php endif; ?>

            <?php foreach ($consultas as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($c['data_consulta'])) ?></td>
                    <td><strong><?= htmlspecialchars($c['pet_nome']) ?></strong> <small class="text-muted">(<?= htmlspecialchars($c['tutor_nome']) ?>)</small></td>
                    <td><?= htmlspecialchars($c['vet_nome']) ?></td>
                    <td>R$ <?= number_format($c['valor'], 2, ',', '.') ?></td>
                    <td>
                        <?php
                        $badge = match($c['status']) {
                            'Concluída' => 'bg-success',
                            'Cancelada' => 'bg-danger',
                            default => 'bg-warning text-dark'
                        };
                        ?>
                        <span class="badge <?= $badge ?>"><?= $c['status'] ?></span>
                    </td>
                    <td class="text-end">
                        <a href="editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="excluir.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta consulta?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>