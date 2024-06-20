<?php
include('../manager/conexao.php');
$stmt = $pdo->prepare("select id_tipo_ponto,
tipo_ponto,
habilitado
from tipo_ponto tp 
where id_empresa = :empresa");
$stmt->bindParam(':empresa', $_SESSION['id_empresa']);
$stmt->execute();
$row = $stmt->rowCount();
$tipo_ponto = $stmt->fetchAll(PDO::FETCH_ASSOC);



