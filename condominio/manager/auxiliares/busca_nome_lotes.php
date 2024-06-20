<?php
include('../conexao.php');
session_start();
$lotes = $pdo->prepare("select DISTINCT  rv.id_vinculo,
pvpg.id_lote,
pvpg.descr_lote,
g.descr_gps,
pv.descr_placa,
CASE 
	when pvpg.dt_entrada is null then 'Sem Entrada'
	ELSE CONVERT(VARCHAR, pvpg.dt_entrada, 103) + ' ' + CONVERT(VARCHAR, pvpg.dt_entrada, 108)
END as 'dt_entrada',
CASE 
	when pvpg.dt_saida is null then '-'
	ELSE CONVERT(VARCHAR, pvpg.dt_saida, 103) + ' ' + CONVERT(VARCHAR, pvpg.dt_saida, 108)
END as 'dt_saida'
from rastreador_vinculado rv
join pontos_vinculo_placa_gps pvpg 
on pvpg.id_vinculo = rv.id_vinculo 
join ALDEIA.dbo.TB_LOTES_COORDENADAS tlc 
on tlc.ID_LOTE = pvpg.id_lote 
JOIN gps g 
on g.id_gps = rv.id_gps
join placas_veiculos pv 
on pv.id_placa = rv.id_placa 
where g.empresa_proprietaria = :empresa
order by 1 asc
");
$lotes->bindParam(':empresa', $_SESSION['id_empresa']);
$lotes->execute();
$lotes_count = $lotes->rowCount();
$resultados = $lotes->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
$json_result = json_encode($resultados);

echo $json_result;