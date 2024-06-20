<?php
include('../manager/conexao.php');
$stmt = $pdo->prepare("select ee.id_endereco,
ee.descr_endereco,
ee.nome_endereco,
ee.user_cadastro_endereco,
us.nome_user,
ee.id_tipo_marcacao,
tm.descr_marcacao,
ee.raio_ponto_mtrs,
ee.id_empresa,
ee.id_tipo_ponto,
tp.tipo_ponto,
ee.id_local_trabalho,
lt.nome_local,
case 
	when ee.habilitado = 1 then 'Sim'
	else 'Não'
end as habilitado,
CONVERT(VARCHAR, ee.dt_cadastro_endereco , 103) + ' ' + CONVERT(VARCHAR, ee.dt_cadastro_endereco, 108) AS dt_cadastro
from endereco_especial ee 
left join tipo_ponto tp 
on tp.id_tipo_ponto = ee.id_tipo_ponto
join tipo_marcacao tm 
on tm.id_marcacao = ee.id_tipo_marcacao 
join usuario_seguranca us 
on us.id_user = ee.user_cadastro_endereco
join local_trabalho lt 
on lt.id_local = ee.id_local_trabalho 
where ee.id_empresa = :empresa
");
$stmt->bindParam(':empresa', $_SESSION['id_empresa']);
$stmt->execute();
$row = $stmt->rowCount();
$endereco = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pontosEndereco = $pdo->prepare("select pllee.id_endereco,
pllee.latitude,
pllee.longitude,
pllee.ordem,
pllee.id_tipo_marcacao,
pllee.raio_ponto_mtrs
from poligono_lat_long_endereco_especial pllee 
join endereco_especial ee 
on ee.id_endereco = pllee.id_endereco 
where ee.id_empresa = :empresa
order by 1, 4");
$pontosEndereco->bindParam(':empresa', $_SESSION['id_empresa']);
$pontosEndereco->execute();
$row = $pontosEndereco->rowCount();
$pontosResult = $pontosEndereco->fetchAll(PDO::FETCH_ASSOC);