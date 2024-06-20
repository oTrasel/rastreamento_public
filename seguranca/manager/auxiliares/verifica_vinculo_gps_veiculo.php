<?php
include('../manager/conexao.php');
$selectGeral = $pdo->prepare('EXEC VerificaVinculoRastreadorVeiculoGps @id_empresa = :id_empresa, @local_trabalho = :local_trabalho');
$selectGeral->bindParam(':id_empresa', $_SESSION['id_empresa']);
$selectGeral->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$selectGeral->execute();

$rastreadores = $selectGeral->fetchAll(PDO::FETCH_ASSOC);


$selectGeral->nextRowset();


$veiculos = $selectGeral->fetchAll(PDO::FETCH_ASSOC);


?>