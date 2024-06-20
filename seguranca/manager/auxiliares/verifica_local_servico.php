<?php
include('../manager/conexao.php');
$stmt = $pdo->prepare("select 
lt.id_local, 
lt.nome_local, 
lt.supervisor_local, 
us.nome_user AS user_cadastro_local, 
lt.contato_supervisor, 
CONVERT(VARCHAR, lt.dt_cadastro  , 103) + ' ' + CONVERT(VARCHAR, lt.dt_cadastro, 108) AS dt_cadastro, 
u.sigla + ' - ' + c.nome AS cidade,
c.cidade_id,
c.uf_id,
CASE 
    WHEN lt.id_local IN (SELECT us.id_local_trabalho FROM usuario_seguranca us) or
         lt.id_local IN (SELECT rs.id_local_trabalho FROM rastreador_seguranca rs) or
         lt.id_local IN (SELECT ee.id_local_trabalho FROM endereco_especial ee) or
         lt.id_local IN (SELECT vs.id_local_servico FROM veiculos_seguranca vs) or
         lt.id_local IN (SELECT s.id_local_trabalho FROM servicos s) or
         lt.id_local IN (SELECT sa.id_local_trabalho FROM servicos_ativos sa) THEN 0
    ELSE 1
END AS exclusao,
lt.lat_central,
lt.long_central
FROM 
local_trabalho lt
JOIN 
Cidade c ON c.cidade_id = lt.cidade_id 
JOIN 
UF u ON u.uf_id = lt.uf_id AND u.uf_id = c.uf_id 
JOIN 
usuario_seguranca us ON us.id_user = lt.user_cadastro_local 
WHERE 
lt.id_empresa = :id_empresa;");
$stmt->bindParam(':id_empresa', $_SESSION['id_empresa']);
$stmt->execute();
$row = $stmt->rowCount();
$locais_trabalho = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>