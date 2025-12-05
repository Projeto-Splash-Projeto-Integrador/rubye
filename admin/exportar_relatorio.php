<?php
require_once '../config/db.php';

// --- 1. VERIFICAÇÃO DE SEGURANÇA ---
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado.");
}

// --- 2. CONFIGURAÇÃO DO ARQUIVO CSV ---
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=relatorio_vendas_' . date('Y-m-d') . '.csv');

// Cria o ponteiro de saída
$output = fopen('php://output', 'w');

// Adiciona o BOM para o Excel reconhecer acentos (UTF-8)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// --- 3. APLICAÇÃO DOS MESMOS FILTROS DA TELA ---
$where = "WHERE 1=1";
$params = [];
$types = "";

if (!empty($_GET['data_inicio'])) {
    $where .= " AND DATE(data_pedido) >= ?";
    $params[] = $_GET['data_inicio'];
    $types .= "s";
}
if (!empty($_GET['data_fim'])) {
    $where .= " AND DATE(data_pedido) <= ?";
    $params[] = $_GET['data_fim'];
    $types .= "s";
}
if (!empty($_GET['status'])) {
    $where .= " AND status = ?";
    $params[] = $_GET['status'];
    $types .= "s";
}

// --- 4. CONSULTA DOS DADOS DETALHADOS ---
$sql = "SELECT p.id, u.nome as cliente, p.data_pedido, p.status, p.total 
        FROM pedidos p 
        JOIN usuarios u ON p.usuario_id = u.id 
        $where 
        ORDER BY p.data_pedido DESC";

$stmt = $conexao->prepare($sql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// --- 5. ESCREVE O CABEÇALHO E AS LINHAS NO CSV ---
fputcsv($output, ['ID Pedido', 'Cliente', 'Data', 'Status', 'Valor Total (R$)'], ';');

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'], 
        $row['cliente'], 
        date('d/m/Y H:i', strtotime($row['data_pedido'])), 
        $row['status'], 
        number_format($row['total'], 2, ',', '.')
    ], ';');
}

fclose($output);
exit();
?>