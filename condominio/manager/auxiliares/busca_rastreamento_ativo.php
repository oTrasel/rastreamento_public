<?php
include('../manager/conexao.php');
$vinculo = $pdo->prepare("select vpg.id_vinculo,
pv.descr_placa,
g.descr_gps,
cast(tv.VELOCIDADE as varchar) as 'velocidade',
CONVERT(VARCHAR, vpg.data_entrada, 103) + ' ' + CONVERT(VARCHAR, vpg.data_entrada, 108) AS data ,
COUNT(pvpg.id_lote) as qtd_destinos,
CASE 
	when tv.quadra is null then '-'
	ELSE tv.quadra
END as quadra,
CASE 
	when tv.RUA is null then '-'
	ELSE tv.RUA
END as rua
from vinculo_placa_gps vpg 
join placas_veiculos pv 
on pv.id_placa = vpg.id_placa 
join gps g 
on g.id_gps = vpg.id_gps
join pontos_vinculo_placa_gps pvpg 
on pvpg.id_vinculo = vpg.id_vinculo 
join FULLTRACK.dbo.TB_VEICULOS tv 
on tv.ID_VEICULO_ECJ = g.id_veiculo_ecj 
and tv.ras_vei_equipamento = g.ras_vei_equipamento
where vpg.id_status = 2
and vpg.data_saida is NULL 
and vpg.id_empresa_cadastro = :id_empresa
group by vpg.data_entrada, vpg.id_vinculo, pv.descr_placa , g.descr_gps, VELOCIDADE, tv.quadra, tv.RUA
");
$vinculo->bindParam(':id_empresa', $_SESSION['id_empresa']);
$vinculo->execute();
$vinculo_count = $vinculo->rowCount();
$resultados = $vinculo->fetchAll(PDO::FETCH_ASSOC);
