<?php
include('../manager/conexao.php');
$marcadoresUsados = $pdo->prepare("select DISTINCT  tp.id_tipo_ponto, tp.tipo_ponto from tipo_ponto tp
join endereco_especial ee 
on ee.id_tipo_ponto = tp.id_tipo_ponto
where ee.id_empresa = :id_empresa
and tp.habilitado = 1
and ee.id_local_trabalho = :local_trabalho");
$marcadoresUsados->bindParam(':id_empresa', $_SESSION['id_empresa']);
$marcadoresUsados->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$marcadoresUsados->execute();
$row = $marcadoresUsados->rowCount();
$marcadores = $marcadoresUsados->fetchAll(PDO::FETCH_ASSOC);


?>