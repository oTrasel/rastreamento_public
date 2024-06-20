<?php
include('../conexao.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['selectEspecie'] !== 'semEspecie' && $_POST['selectLocal'] !== 'semLocal') {

    try{

        $insert_veiculo = $pdo->prepare('insert into veiculos_seguranca (modelo_veiculo, placa_veiculo, cor_veiculo, id_especie_veiculo, id_local_servico, dt_cadastro_veiculo, user_cadastro_veiculo, marca_veiculo, id_empresa)
        values(:modelo_veiculo, :placa_veiculo, :cor_veiculo, :id_especie_veiculo, :id_local_servico, getdate(), :user_cadastro_veiculo, :marca_veiculo, :id_empresa)');
        $insert_veiculo->bindParam(':modelo_veiculo', $_POST['modelo_veiculo']);
        $insert_veiculo->bindParam(':placa_veiculo', $_POST['placa_veiculo']);
        $insert_veiculo->bindParam(':cor_veiculo', $_POST['cor_veiculo']);
        $insert_veiculo->bindParam(':id_especie_veiculo', $_POST['selectEspecie']);
        $insert_veiculo->bindParam(':id_local_servico', $_POST['selectLocal']);
        $insert_veiculo->bindParam(':user_cadastro_veiculo', $_SESSION['id_seguranca']);
        $insert_veiculo->bindParam(':marca_veiculo', $_POST['marca_veiculo']);
        $insert_veiculo->bindParam(':id_empresa', $_SESSION['id_empresa']);
        
        $insert_veiculo->execute();
        $row_insert = $insert_veiculo->rowCount();
        if($row_insert === 1){
            echo 'sucesso';
        }


    } catch (PDOException $error) {
        echo $error;
    }
}else{
    echo 'error';
}
?>
