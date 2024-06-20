<?php
include('../conexao.php');
session_start();
$condutor = $pdo->prepare("select DISTINCT  rv.id_vinculo,
g.descr_gps,
pv.descr_placa,
rv.contato_condutor,
rv.condutor 
from rastreador_vinculado rv
join pontos_vinculo_placa_gps pvpg 
on pvpg.id_vinculo = rv.id_vinculo 
JOIN gps g 
on g.id_gps = rv.id_gps
join placas_veiculos pv 
on pv.id_placa = rv.id_placa 
where g.empresa_proprietaria = :empresa
order by 1 asc
");
$condutor->bindParam(':empresa', $_SESSION['id_empresa']);
$condutor->execute();
$condutor_count = $condutor->rowCount();
$resultados = $condutor->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
$json_result = json_encode($resultados);

echo $json_result;