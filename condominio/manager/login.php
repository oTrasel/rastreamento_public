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


if (isset($_POST['user']) && strlen($_POST['user']) == 14) {
    $user = $_POST['user'];
    $passwd = $_POST['password'];
    try {
        //LOGIN POR CNPJ/EMPRESA
        $stmt = $pdo->prepare("select id_empresa, razao_social_empresa, cpf_cnpj_empresa, senha_empresa, data_cadastro_empresa, tipo_user from empresa where cpf_cnpj_empresa = :user");
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        // Obter a primeira linha do resultado como um array associativo
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //verifica quantas linhas o select retornou
        $row_count = $stmt->rowCount();
        //verifica se o select retornou algo, caso contrario, exibe erro.
        if ($row_count == -1) {
            if (password_verify($passwd, $row['senha_empresa']) == 1 && $row['tipo_user'] == 4) {
                $stmt = $pdo->prepare("insert into logs_acessos_empresa (id_empresa, id_nivel_log, data_acesso, tipo_user, ip_acesso)
                                        values (:idempresa, 2, getdate(), :idtipouser, :ip)");
                $stmt->bindParam(':idempresa', $row['id_empresa']);
                $stmt->bindParam(':idtipouser', $row['tipo_user']);
                $stmt->bindParam(':ip', $ip);
                $stmt->execute();
                session_start();
                $_SESSION['tipo_user'] = $row['tipo_user'];
                $_SESSION['razao_social'] = $row['razao_social_empresa'];
                $_SESSION['cnpj_empresa'] = $row['cpf_cnpj_empresa'];
                $_SESSION['data_cadastro'] = $row['data_cadastro_empresa'];
                $_SESSION['id_empresa'] = $row['id_empresa'];
                $_SESSION['empresa'] = true;
                echo 'sucesso';
            } else {
                echo 'errorCredenciais';
            }
        } else {
            echo 'errorCredenciais';
        }
    } catch (PDOException $e) {
    }
} else {
    echo 'errorTamanho';
}
