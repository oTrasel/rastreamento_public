<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        

        $select = explode("|", $_POST['localEditavel']);
        $id_local = $select[0];
        
        if($_POST['id_user'] === $_SESSION['id_user']){
            $_SESSION['local_trabalho'] = $id_local;
        }

        $update = $pdo->prepare('update usuario_seguranca set 
        nome_user = :nome, 
        login_user = :login, 
        cpf_user = :cpf, 
        telefone_user = :telefone, 
        id_funcao_user = :funcao, 
        id_local_trabalho = :local, 
        habilitado  = :habilitado,
        user_ultima_mod = :user_mod,
        dt_ultima_mod = GETDATE()
        where id_user = :id_user
        ');
        $update->bindParam(':nome', $_POST['nomeEditavel']);
        $update->bindParam(':login', $_POST['loginEditavel']);
        $update->bindParam(':cpf', $_POST['cpfEditavel']);
        $update->bindParam(':telefone', $_POST['telefoneEditavel']);
        $update->bindParam(':funcao', $_POST['funcaoEditavel']);
        $update->bindParam(':local', $id_local);
        $update->bindParam(':habilitado', $_POST['habilitado']);
        $update->bindParam(':user_mod', $_SESSION['id_user']);
        $update->bindParam(':id_user', $_POST['id_user']);
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
