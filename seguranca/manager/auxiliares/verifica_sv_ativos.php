<?php
include('../manager/conexao.php');
$selectGeral = $pdo->prepare('EXEC VerificaServicosAtivosNew @id_empresa = :id_empresa, @local_trabalho = :local_trabalho');
$selectGeral->bindParam(':id_empresa', $_SESSION['id_empresa']);
$selectGeral->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$selectGeral->execute();


$cartaoPrograma = $selectGeral->fetchAll(PDO::FETCH_ASSOC);


$selectGeral->nextRowset();


$rastreadores = $selectGeral->fetchAll(PDO::FETCH_ASSOC);


$selectGeral->nextRowset();


$veiculos = $selectGeral->fetchAll(PDO::FETCH_ASSOC);
$row_veiculos = $selectGeral->rowCount();

$selectGeral->nextRowset();


$prestador = $selectGeral->fetchAll(PDO::FETCH_ASSOC);

$selectGeral->nextRowset();


$seguranca = $selectGeral->fetchAll(PDO::FETCH_ASSOC);

$selectGeral->nextRowset();

$row_sv = $selectGeral->rowCount();

$servicosAtivos = $selectGeral->fetchAll(PDO::FETCH_ASSOC);


?>