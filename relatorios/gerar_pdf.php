<?php
require_once '../includes/auth_check.php';
require_once '../config/conexao.php';
require_once '../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Consulta consolidada (Agrupamento por Veterinário: Total de atendimentos e Faturamento)
$sqlConsolidado = "SELECT v.nome AS veterinario, v.crmv, 
                          COUNT(c.id) AS qtd_consultas, 
                          COALESCE(SUM(c.valor), 0) AS total_faturado
                   FROM veterinarios v
                   LEFT JOIN consultas c ON v.id = c.veterinario_id AND c.status != 'Cancelada'
                   GROUP BY v.id
                   ORDER BY total_faturado DESC";
$resumoVets = $pdo->query($sqlConsolidado)->fetchAll();

// 2. Listagem detalhada das consultas
$sqlLista = "SELECT c.data_consulta, p.nome AS pet, v.nome AS vet, c.valor, c.status
             FROM consultas c
             JOIN pets p ON c.pet_id = p.id
             JOIN veterinarios v ON c.veterinario_id = v.id
             ORDER BY c.data_consulta DESC";
$listaConsultas = $pdo->query($sqlLista)->fetchAll();

// Montando o HTML do PDF
ob_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 5px; }
        h3 { margin-top: 25px; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Relatório Gerencial - Clínica Veterinária</h1>
    <p>Emitido em: <?= date('d/m/Y H:i') ?> | Responsável: <?= htmlspecialchars($_SESSION['usuario_nome']) ?></p>

    <h3>1. Consolidação por Veterinário (Atendimentos e Faturamento)</h3>
    <table>
        <thead>
            <tr>
                <th>Veterinário</th>
                <th>CRMV</th>
                <th>Qtd. Consultas</th>
                <th>Total Faturado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resumoVets as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['veterinario']) ?></td>
                    <td><?= htmlspecialchars($r['crmv']) ?></td>
                    <td><?= $r['qtd_consultas'] ?></td>
                    <td>R$ <?= number_format($r['total_faturado'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>2. Listagem Geral de Consultas</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Pet</th>
                <th>Veterinário</th>
                <th>Status</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaConsultas as $item): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($item['data_consulta'])) ?></td>
                    <td><?= htmlspecialchars($item['pet']) ?></td>
                    <td><?= htmlspecialchars($item['vet']) ?></td>
                    <td><?= $item['status'] ?></td>
                    <td>R$ <?= number_format($item['valor'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_clinica_vet.pdf", ["Attachment" => false]);