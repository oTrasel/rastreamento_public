<?php
include('conexao.php');
//verificar o endereço IP acessado quando está atrás de proxy
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}


if (isset($_POST['user'])) {
    $user = $_POST['user'];
    $passwd = $_POST['password'];
    try {
        $stmt = $pdo->prepare("select id_user, id_tipo_user, cpf_user , nome_user , login_user , senha_user, habilitado, id_empresa, id_local_trabalho 
        from usuario_seguranca us 
        where login_user = :login_seguranca");
        $stmt->bindParam(':login_seguranca', $user);
        $stmt->execute();
        // Obter a primeira linha do resultado como um array associativo
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //verifica quantas linhas o select retornou
        $row_count = $stmt->rowCount();
        //verifica se o select retornou algo, caso contrario, exibe erro.
        
        if ($row_count == -1) {
            if (password_verify($passwd, $row['senha_user']) == 1) {
                if ($row['habilitado'] == 1) {
                    session_start();
                    $_SESSION['id_user'] = $row['id_user'];
                    $_SESSION['nome_user'] = $row['nome_user'];
                    $_SESSION['cpf_user'] = $row['cpf_user'];
                    $_SESSION['id_tipo_user'] = $row['id_tipo_user'];
                    $_SESSION['habilitado'] = $row['habilitado'];
                    $_SESSION['id_empresa'] = $row['id_empresa'];
                    $_SESSION['local_trabalho'] = $row['id_local_trabalho'];
                    $_SESSION['seguranca'] = true;
                    echo $row['id_tipo_user'];
                }else {
                    echo 'errorBlock';
                }
            } else {
                echo 'errorCredenciais';
            }
        } else {
            echo 'errorCredenciais';
        }
    } catch (PDOException $e) {
        echo 'errorCredenciais';
    }
}
