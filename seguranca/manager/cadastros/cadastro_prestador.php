<?PHP
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['selectFuncao'] !== 'semFuncao') {
    try {
        $senha = password_hash($_POST['passwd'], PASSWORD_DEFAULT);
        $local_parts = explode('|',$_POST['selectLocalSv']);
        $id_local = $local_parts[0];

        $insert_prestador = $pdo->prepare('insert into usuario_seguranca (nome_user, cpf_user, login_user, telefone_user, senha_user , id_funcao_user, user_cadastro, id_tipo_user, dt_cadastro, id_empresa, habilitado, id_local_trabalho)
        values (:nome_prestador, :cpf_prestador, :login_prestador, :telefone_prestador, :senha_prestador, :id_funcao_prestador, :user_cadastro_prestador, 6, getdate(), :id_empresa, 1, :id_local )');
        $insert_prestador->bindParam(':nome_prestador', $_POST['nome_prestador']);
        $insert_prestador->bindParam(':cpf_prestador', $_POST['prestador_cpf']);
        $insert_prestador->bindParam(':login_prestador', $_POST['login_prestador']);
        $insert_prestador->bindParam(':telefone_prestador', $_POST['prestador_telefone']);
        $insert_prestador->bindParam(':id_funcao_prestador', $_POST['selectFuncao']);
        $insert_prestador->bindParam(':user_cadastro_prestador', $_SESSION['id_user']);
        $insert_prestador->bindParam(':id_empresa', $_SESSION['id_empresa']);
        $insert_prestador->bindParam(':senha_prestador', $senha);
        $insert_prestador->bindParam(':id_local', $id_local);
        $insert_prestador->execute();
        $row_insert = $insert_prestador->rowCount();
        if ($row_insert === 1) {
            echo 'sucesso';
        }
    } catch (PDOException $error) {
        echo $error;
    }
} else {
    echo 'error';
}
