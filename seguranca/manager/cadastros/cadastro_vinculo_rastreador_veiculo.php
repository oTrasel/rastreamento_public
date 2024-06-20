<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST"){
    try{
        $insertVinculo = $pdo->prepare('insert into vinculo_rastreador_veiculo_seguranca (id_rastreador, id_veiculo_seguranca, dt_vinculo, user_vinculo)
        values(:id_rastreador, :id_veiculo_seguranca, getdate(), :user_vinculo)');
        $insertVinculo->bindParam(':id_rastreador', $_POST['selectRastreador']);
        $insertVinculo->bindParam(':id_veiculo_seguranca', $_POST['selectVeiculo']);
        $insertVinculo->bindParam(':user_vinculo',  $_SESSION['id_seguranca']);
        $insertVinculo->execute();
        $row = $insertVinculo->rowCount();
        if($row === 1){
            echo 'sucesso';
        }else{
            echo "error";
        }
    }catch(PDOException $e){
        echo $e;
    }

}else{
    echo "error";
}

?>