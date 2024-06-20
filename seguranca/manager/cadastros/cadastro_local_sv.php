<?php
include('../conexao.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['selectCidade'] !== 'semLocal') {
    try {
        $select = explode("|", $_POST['selectCidade']);
        $id_cidade = $select[0];
        $id_uf = $select[1];
        $coordenadasArray = json_decode($_POST["coordenadas"], true);
        $latitude = $coordenadasArray["lat"];
        $longitude =  $coordenadasArray["long"];
        
        $insert_local = $pdo->prepare('insert into local_trabalho (nome_local, uf_id, cidade_id, supervisor_local, contato_supervisor, dt_cadastro, user_cadastro_local, id_empresa, lat_central, long_central)
        values(:nome_local, :uf_id, :cidade_id, :supervisor_local, :contato_supervisor, getdate(), :user_cadastro_local, :id_empresa, :lat, :long )');
        $insert_local->bindParam(':nome_local', $_POST['nome_local']);
        $insert_local->bindParam(':uf_id', $id_uf);
        $insert_local->bindParam(':cidade_id', $id_cidade);
        $insert_local->bindParam(':supervisor_local', $_POST['supervisor_local']);
        $insert_local->bindParam(':contato_supervisor', $_POST['telefone_supervisor']);
        $insert_local->bindParam(':user_cadastro_local', $_SESSION['id_user']);
        $insert_local->bindParam(':id_empresa', $_SESSION['id_empresa']);
        $insert_local->bindParam(':lat', $latitude );
        $insert_local->bindParam(':long', $longitude);
        $insert_local->execute();
        $row_count = $insert_local->rowCount();
        if($row_count === 1){
            echo 'sucesso';
        }
        
    } catch (PDOException $error) {
        echo $error;
    }
}else{
    echo 'error';
}