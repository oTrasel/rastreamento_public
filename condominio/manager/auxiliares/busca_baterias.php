<?php
include('../conexao.php');
session_start();
$bateria = $pdo->prepare("select g.descr_gps,
tv.VOLTAGEM,
tv.BATERIA,
CONVERT(VARCHAR, tv.DT_ULT_POSICAO, 103) + ' ' + CONVERT(VARCHAR, tv.DT_ULT_POSICAO, 108) AS 'dt_ultima_posicao'
FROM FULLTRACK.dbo.TB_VEICULOS tv
JOIN gps g ON g.id_veiculo_ecj = tv.ID_VEICULO_ECJ
   AND g.ras_vei_equipamento = tv.ras_vei_equipamento
WHERE g.empresa_proprietaria = :id_empresa
AND g.descr_gps COLLATE Latin1_General_CI_AS = tv.ras_vei_placa COLLATE Latin1_General_CI_AS
and tv.ativo = 1
");
$bateria->bindParam(':id_empresa', $_SESSION['id_empresa']);
$bateria->execute();
$bateria_count = $bateria->rowCount();
$resultados = $bateria->fetchAll(PDO::FETCH_ASSOC);
$json_result = json_encode($resultados);

echo $json_result;