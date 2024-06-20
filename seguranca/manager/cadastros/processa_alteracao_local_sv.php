<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {

        $select = explode("|", $_POST['selectCidade']);
        $id_cidade = $select[0];
        $id_uf = $select[1];

        $update = $pdo->prepare('update local_trabalho set nome_local = :nome_local,
        uf_id = :uf_id,
        cidade_id = :cidade_id,
        supervisor_local = :supervisor_local,
        contato_supervisor = :contato_supervisor,
        lat_central = :lat,
        long_central = :long,
        user_ultima_mod = :user_cadastro_local,
        dt_ultima_mod = getdate()
        where id_local = :id_loc');
        $update->bindParam(':nome_local', $_POST['nome_local']);
        $update->bindParam(':uf_id', $id_uf);
        $update->bindParam(':cidade_id', $id_cidade);
        $update->bindParam(':supervisor_local', $_POST['supervisor_local']);
        $update->bindParam(':contato_supervisor', $_POST['telefone_supervisor']);
        $update->bindParam(':user_cadastro_local', $_SESSION['id_user']);
        $update->bindParam(':lat', $_POST['lat_centro']);
        $update->bindParam(':long', $_POST['long_centro']);
        $update->bindParam(':id_loc', $_POST['id_loc']);
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
