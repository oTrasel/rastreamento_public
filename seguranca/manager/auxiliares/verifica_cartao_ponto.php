<?php
include('../manager/conexao.php');
$cartaoPrograma = $pdo->prepare("select cp.id_cartao, 
cp.descr_cartao, 
CONVERT(VARCHAR, cp.dt_cadastro, 103) + ' ' + CONVERT(VARCHAR, cp.dt_cadastro, 108) AS dt_cadastro, 
CASE 
    when cp.habilitado = 1 then 'Sim'
    ELSE 'Não'
END as habilitado,
count(cpp.id_endereco) as qtdPontos
from cartao_programa cp 
join cartao_programa_pontos cpp 
on cpp.id_cartao = cp.id_cartao
where cp.id_empresa = :id_empresa
and cp.local_trabalho = :local_trabalho 
GROUP by cp.id_cartao, cp.descr_cartao, cp.dt_cadastro, cp.habilitado
order by cp.id_cartao asc");
$cartaoPrograma->bindParam(':id_empresa', $_SESSION['id_empresa']);
$cartaoPrograma->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$cartaoPrograma->execute();
$row_cp = $cartaoPrograma->rowCount();
$cp = $cartaoPrograma->fetchAll(PDO::FETCH_ASSOC);




?>