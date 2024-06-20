<?php
include('../manager/conexao.php');
$ocorrenciaLoc = $pdo->prepare("select ID_ALERTA, PLACA, 
RAS_VEI_ID, CONVERT(VARCHAR, DT_ALERTA , 103) + ' ' + CONVERT(VARCHAR, DT_ALERTA, 108) as DT_OCORRENCIA, 
ALERTA_TIPO,  ID_EMPRESA from TB_ALERTA 
where ID_EMPRESA = :ID_EMPRESA 
AND JUSTIFICATIVA is NULL 	 
AND ID_USUARIO is NULL 
AND DT_HR_JUSTIFICATIVA is NULL");
$ocorrenciaLoc->bindParam(':ID_EMPRESA', $_SESSION['id_empresa']);
$ocorrenciaLoc->execute();
$row = $ocorrenciaLoc->rowCount();
$ocrLoc = $ocorrenciaLoc->fetchAll(PDO::FETCH_ASSOC);

// $selectJustificadas = $pdo->prepare("select ID_OCORRENCIA, PLACA, 
// RAS_VEI_ID, CONVERT(VARCHAR, DT_OCORRENCIA, 103) + ' ' + CONVERT(VARCHAR, DT_OCORRENCIA, 108) as DT_OCORRENCIA, 
// OCORRENCIA,  ID_EMPRESA, cast(VELOCIDADE as varchar) as VELOCIDADE, JUSTIFICATICA, 
// CONVERT(VARCHAR, DT_HR_JUSTIFICATIVA, 103) + ' ' + CONVERT(VARCHAR, DT_HR_JUSTIFICATIVA, 108) as DT_HR_JUSTIFICATIVA from TB_OCORRENCIA 
// where TB_OCORRENCIA.ID_EMPRESA = :ID_EMPRESA 
// AND ID_USUARIO = :ID_USUARIO ");

// $selectJustificadas->bindParam(':ID_EMPRESA', $_SESSION['id_empresa']);
// $selectJustificadas->bindParam(':ID_USUARIO', $_SESSION['id_user']);
// $selectJustificadas->execute();
// $rowRegistro = $selectJustificadas->rowCount();
// $registradas = $selectJustificadas->fetchAll(PDO::FETCH_ASSOC);