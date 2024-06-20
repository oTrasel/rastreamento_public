<?php
include('../manager/conexao.php');
$ocorrenciaVel = $pdo->prepare("select ID_OCORRENCIA, PLACA, 
                                RAS_VEI_ID, CONVERT(VARCHAR, DT_OCORRENCIA, 103) + ' ' + CONVERT(VARCHAR, DT_OCORRENCIA, 108) as DT_OCORRENCIA, 
                                OCORRENCIA,  ID_EMPRESA, cast(VELOCIDADE as varchar) as VELOCIDADE from TB_OCORRENCIA 
where TB_OCORRENCIA.ID_EMPRESA = :ID_EMPRESA 
AND JUSTIFICATICA is NULL 	 
AND ID_USUARIO is NULL 
AND DT_HR_JUSTIFICATIVA is NULL");
$ocorrenciaVel->bindParam(':ID_EMPRESA', $_SESSION['id_empresa']);
$ocorrenciaVel->execute();
$row = $ocorrenciaVel->rowCount();
$ocrVel = $ocorrenciaVel->fetchAll(PDO::FETCH_ASSOC);

$selectJustificadas = $pdo->prepare("select ID_OCORRENCIA, PLACA, 
RAS_VEI_ID, CONVERT(VARCHAR, DT_OCORRENCIA, 103) + ' ' + CONVERT(VARCHAR, DT_OCORRENCIA, 108) as DT_OCORRENCIA, 
OCORRENCIA,  ID_EMPRESA, cast(VELOCIDADE as varchar) as VELOCIDADE, JUSTIFICATICA, 
CONVERT(VARCHAR, DT_HR_JUSTIFICATIVA, 103) + ' ' + CONVERT(VARCHAR, DT_HR_JUSTIFICATIVA, 108) as DT_HR_JUSTIFICATIVA from TB_OCORRENCIA 
where TB_OCORRENCIA.ID_EMPRESA = :ID_EMPRESA 
AND ID_USUARIO = :ID_USUARIO ");

$selectJustificadas->bindParam(':ID_EMPRESA', $_SESSION['id_empresa']);
$selectJustificadas->bindParam(':ID_USUARIO', $_SESSION['id_user']);
$selectJustificadas->execute();
$rowRegistro = $selectJustificadas->rowCount();
$registradas = $selectJustificadas->fetchAll(PDO::FETCH_ASSOC);