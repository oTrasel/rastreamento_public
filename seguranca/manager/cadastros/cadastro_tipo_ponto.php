<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipoDescr'])) {
    try{
        $stmt = $pdo->prepare("insert INTO tipo_ponto (tipo_ponto, dt_insercao, id_empresa, cor, user_cadastro, habilitado)
        values(:tipo, getdate(), :empresa, :cor, :user, 1)");
        $stmt->bindParam(':tipo', $_POST['tipoDescr']);
        $stmt->bindParam(':empresa', $_SESSION['id_empresa']);
        $stmt->bindParam(':cor', $_POST['pontoCor']);
        $stmt->bindParam(':user', $_SESSION['id_user']);
        $stmt->execute();
        $row = $stmt->rowCount();
        if($row === 1){
            echo 'sucesso';
        }
    }catch(PDOException $erro){
        echo $erro;
    }
}else{
    echo 'error';
}



?>