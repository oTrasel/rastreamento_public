<?php
include('../manager/conexao.php');
$especie_veiculo = $pdo->prepare('select id_especie, descr_especie from especie_veiculo');
$especie_veiculo->execute();
$especies = $especie_veiculo->fetchAll(PDO::FETCH_ASSOC);
?>