<?php
require_once '../includes/auth_check.php';
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO consultas (data_consulta, descricao, valor, status, pet_id, veterinario_id) 
            VALUES (:data_consulta, :descricao, :valor, :status, :pet_id, :veterinario_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':data_consulta'  => $_POST['data_consulta'],
        ':descricao'      => $_POST['descricao'],
        ':valor'          => $_POST['valor'],
        ':status'         => $_POST['status'],
        ':pet_id'         => $_POST['pet_id'],
        ':veterinario_id' => $_POST['veterinario_id']
    ]);
    header("Location: index.php");
    exit;
}

$pets = $pdo->query("SELECT id, nome FROM pets ORDER BY nome")->fetchAll();
$vets = $pdo->query("SELECT id, nome FROM veterinarios ORDER BY nome")->fetchAll();

require_once '../includes/header.php';
?>

<h3 class="mb-3">Agendar Nova Consulta</h3>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pet</label>
                    <select name="pet_id" class="form-select" required>
                        <option value="">Selecione o Pet...</option>
                        <?php foreach ($pets as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Veterinário</label>
                    <select name="veterinario_id" class="form-select" required>
                        <option value="">Selecione o Veterinário...</option>
                        <?php foreach ($vets as $v): ?>
                            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Data e Hora</label>
                    <input type="datetime-local" name="data_consulta" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Agendada">Agendada</option>
                        <option value="Concluída">Concluída</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Descrição / Sintomas</label>
                    <textarea name="descricao" class="form-control" rows="3" required></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Salvar Consulta</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>