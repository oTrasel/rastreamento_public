<?php
include('../manager/conexao.php');
$funcao_prestador = $pdo->prepare('select id_funcao, descr_funcao from funcao 
                                    where id_empresa = :id_empresa');
$funcao_prestador->bindParam(':id_empresa', $_SESSION['id_empresa']);
$funcao_prestador->execute();
$funcao = $funcao_prestador->fetchAll(PDO::FETCH_ASSOC);
?>