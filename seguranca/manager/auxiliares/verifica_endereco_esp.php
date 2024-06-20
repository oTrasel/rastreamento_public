<?php
include('../manager/conexao.php');
$enderecos_esp = $pdo->prepare('select id_endereco, descr_endereco, nome_endereco, user_cadastro_endereco, id_tipo_marcacao, raio_ponto_mtrs from endereco_especial 
where id_empresa = :id_empresa
and id_local_trabalho = :local_trabalho');
$enderecos_esp->bindParam(':id_empresa', $_SESSION['id_empresa']);
$enderecos_esp->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$enderecos_esp->execute();
$enderecos = $enderecos_esp->fetchAll(PDO::FETCH_ASSOC);
?>
