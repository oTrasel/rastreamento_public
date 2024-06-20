<?php
include('../manager/conexao.php');
$cidades = $pdo->prepare('select c.cidade_id, c.nome, c.uf_id, u.sigla  from Cidade c 
                          join UF u 
                          on u.uf_id = c.uf_id ');
$cidades->execute();
$cidades_uf = $cidades->fetchAll(PDO::FETCH_ASSOC);
?>
