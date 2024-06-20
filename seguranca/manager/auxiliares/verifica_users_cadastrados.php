<?php
include('../manager/conexao.php');
$stmt = $pdo->prepare("select id_user, 
cpf_user, 
nome_user, 
login_user, 
CONVERT(VARCHAR, us.dt_cadastro  , 103) + ' ' + CONVERT(VARCHAR, us.dt_cadastro, 108) AS dt_cadastro, 
case 
	when habilitado = 1 then 'Sim'
	else 'Não'
end as habilitado, 
id_funcao_user, 
id_local_trabalho, 
lt.nome_local,
f.descr_funcao,
us.telefone_user
from usuario_seguranca us 
join local_trabalho lt 
on lt.id_local = us.id_local_trabalho 
join funcao f 
on f.id_funcao = us.id_funcao_user 
where us.id_empresa = :id_empresa
and id_user not in (SELECT sa.id_user from servicos_ativos sa )");
$stmt->bindParam(':id_empresa', $_SESSION['id_empresa']);
$stmt->execute();
$row = $stmt->rowCount();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>