<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try{

        $deleteVinculo = $pdo->prepare("delete from vinculo_rastreador_veiculo_seguranca
        where id_rastreio = :id_rastreio");
        $deleteVinculo->bindParam(':id_rastreio', $_POST['selectDesvinculo']);
        $deleteVinculo->execute();
        $row = $deleteVinculo->rowCount();
        if($row === 1){
            echo 'sucesso';
        }else{
            echo 'error';
        }

    }catch(PDOException $e){
        echo $e;
    }
}else{
    echo 'error';
}


?>