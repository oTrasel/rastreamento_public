<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {

        foreach ($_POST['selecoes'] as $ocorrencias) {
            $sqlOcorrencia = $pdo->prepare("update TB_OCORRENCIA 
                                            SET JUSTIFICATICA = :justificativa, 
                                                ID_USUARIO = :ID_USUARIO,
                                                USUARIO = :USUARIO,
                                                DT_HR_JUSTIFICATIVA = GETDATE() 
                                            WHERE ID_OCORRENCIA = :ID_OCORRENCIA ; ");
            $sqlOcorrencia->bindParam(':justificativa', $_POST['motivoJustificativa']);
            $sqlOcorrencia->bindParam(':ID_USUARIO', $_SESSION['id_user']);
            $sqlOcorrencia->bindParam(':USUARIO', $_SESSION['nome_user']);
            $sqlOcorrencia->bindParam(':ID_OCORRENCIA', $ocorrencias);
            $sqlOcorrencia->execute();

        }
        echo 'sucesso';
    } catch (PDOException $e) {
        echo $e;
    }
} else {
    echo 'error';
}
