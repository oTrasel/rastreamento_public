<?php
include('../conexao.php');
session_start();

// Consulta para obter informações sobre a posição dos veículos
$buscaLocal = $pdo->prepare("select id_local, nome_local, supervisor_local, contato_supervisor, id_empresa, lat_central, long_central  from local_trabalho lt 
where id_empresa = :id_empresa 
and id_local = :local_trabalho");
$buscaLocal->bindParam(':id_empresa', $_SESSION['id_empresa']);
$buscaLocal->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$buscaLocal->execute();
$result = $buscaLocal->fetchAll(PDO::FETCH_ASSOC);


$json_result = json_encode($result, JSON_PRETTY_PRINT);

echo $json_result;




?>

