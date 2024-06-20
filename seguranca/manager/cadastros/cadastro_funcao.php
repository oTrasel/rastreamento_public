<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['descr_funcao'])) {
    try{
        $stmt = $pdo->prepare("insert INTO funcao (descr_funcao, dt_cadastro, user_cadastro_funcao, id_empresa)
                              values (:descr_funcao, getdate(), :user_cadastro_funcao, :empresa)");
        $stmt->bindParam(':descr_funcao', $_POST['descr_funcao']);
        $stmt->bindParam(':user_cadastro_funcao', $_SESSION['id_seguranca']);
        $stmt->bindParam(':empresa', $_SESSION['id_empresa']);
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