<?php
include('../manager/conexao.php');

// Verifica se a sessão de 'user' ou 'empresa' existe
if (isset($_SESSION['user'])) {
    $tipoUsuario = 'successUser';
} elseif (isset($_SESSION['empresa'])) {
    $tipoUsuario = 'successEmpresa';
} else {
    echo 'error';
    exit;  // Encerra a execução do script se nenhum tipo de usuário for encontrado
}

// Busca gps cadastrado no sistema no qual não tem vínculo ativo.
$stmt = $pdo->prepare("EXEC VerificaVinculosPlacasGps");
$stmt->execute();


$lotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$lotes_count = $stmt->rowCount();

$stmt->nextRowset();

// Resultados GPS
$gps = $stmt->fetchAll(PDO::FETCH_ASSOC);
$gps_count = $stmt->rowCount();

// Vai para o próximo SELECT
$stmt->nextRowset();

// Resultados Placas
$placas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$placa_count = $stmt->rowCount();




