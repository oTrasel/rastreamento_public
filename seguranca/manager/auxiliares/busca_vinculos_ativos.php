<?php
include('../manager/conexao.php');
$vinculoAtivo = $pdo->prepare("select vrvs.id_rastreio,
vrvs.id_rastreador,
vrvs.id_veiculo_seguranca,
vrvs.dt_vinculo,
vrvs.user_vinculo,
rs.descr_rastreador,
vs.placa_veiculo + ' - ' + vs.modelo_veiculo as veiculo
from vinculo_rastreador_veiculo_seguranca vrvs
join veiculos_seguranca vs
on vs.id_veiculo = vrvs.id_veiculo_seguranca
join rastreador_seguranca rs
on rs.id_rastreador = vrvs.id_rastreador
where vrvs.id_rastreador not in (select id_rastreador from servicos_ativos)
and vrvs.id_veiculo_seguranca not in (select id_veiculo from servicos_ativos)
and vs.id_empresa = rs.id_empresa
and vs.id_empresa = :id_empresa
and vs.id_local_servico = :local_trabalho");
$vinculoAtivo->bindParam(':id_empresa', $_SESSION['id_empresa']);
$vinculoAtivo->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$vinculoAtivo->execute();
$row_va = $vinculoAtivo->rowCount();
$vinculos = $vinculoAtivo->fetchAll(PDO::FETCH_ASSOC);




?>