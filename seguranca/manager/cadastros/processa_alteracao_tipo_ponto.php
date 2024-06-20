<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {

        $cor = strtoupper($_POST['corEditavel']);

        $update = $pdo->prepare('update tipo_ponto  set 
        tipo_ponto  = :nome_ponto, 
        cor = :cor, 
        habilitado  = :habilitado,
        user_ultima_mod = :user_mod,
        dt_ultima_mod = GETDATE()
        where id_tipo_ponto  = :id_ponto
        ');
        $update->bindParam(':nome_ponto', $_POST['descrEditavel']);
        $update->bindParam(':cor', $cor);
        $update->bindParam(':habilitado', $_POST['habilitado']);
        $update->bindParam(':user_mod', $_SESSION['id_user']);
        $update->bindParam(':id_ponto', $_POST['idPonto']);
        $update->execute();
        $row_count = $update->rowCount();
        if ($row_count === 1) {
            echo 'sucesso';
        }
    } catch (PDOException $error) {
        echo $error;
    }
} else {
    echo 'error';
}
