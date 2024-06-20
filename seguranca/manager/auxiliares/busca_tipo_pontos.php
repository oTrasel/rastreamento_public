<?php
include('../manager/conexao.php');
$stmt = $pdo->prepare("select tp.id_tipo_ponto,
tp.tipo_ponto,
CONVERT(VARCHAR, tp.dt_insercao  , 103) + ' ' + CONVERT(VARCHAR, tp.dt_insercao, 108) AS dt_insercao,
	   tp.id_empresa,
	   tp.cor,
	   tp.user_cadastro,
	   us.nome_user,
	   CASE 
	   	when tp.habilitado = 1 then 'Sim'
	   	when tp.habilitado = 0 then 'Não'
	   END as habilitado,
	   count (ee.descr_endereco) as 'qtd_endereco'
from tipo_ponto tp 
join usuario_seguranca us 
on us.id_user = tp.user_cadastro 
left join endereco_especial ee 
on ee.id_tipo_ponto = tp.id_tipo_ponto
where tp.id_empresa = :empresa
and ee.id_local_trabalho = :local_trabalho 
GROUP by tp.tipo_ponto, tp.dt_insercao, tp.id_empresa, tp.cor, tp.user_cadastro, us.nome_user, tp.habilitado, tp.id_tipo_ponto");
$stmt->bindParam(':empresa', $_SESSION['id_empresa']);
$stmt->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$stmt->execute();
$row = $stmt->rowCount();
$tipo_ponto = $stmt->fetchAll(PDO::FETCH_ASSOC);


