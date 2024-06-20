<?php

include('../conexao.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['seguranca'] === true) {

    try {
        $passwd = $_POST['passwd'];
        $selectUser = $pdo->prepare("select id_user, id_tipo_user, cpf_user , nome_user , login_user , senha_user , habilitado, id_empresa
        from usuario_seguranca us
        where id_user = :id_seguranca
        and cpf_user = :cpf_seguranca
        and nome_user = :nome_seguranca
        and habilitado = :habilitado
        and id_empresa = :id_empresa  ");
        $selectUser->bindParam(':id_seguranca', $_SESSION['id_user']);
        $selectUser->bindParam(':cpf_seguranca', $_SESSION['cpf_user']);
        $selectUser->bindParam(':nome_seguranca', $_SESSION['nome_user']);
        $selectUser->bindParam(':habilitado', $_SESSION['habilitado']);
        $selectUser->bindParam(':id_empresa', $_SESSION['id_empresa']);
        $selectUser->execute();
        $row_select = $selectUser->fetch(PDO::FETCH_ASSOC);
        $row_count_sl = $selectUser->rowCount();
        if ($row_count_sl == -1) {
            if (password_verify($passwd, $row_select['senha_user']) == 1) {
                try{
                    $deleteSvAtivo = $pdo->prepare('EXEC FinalizaRonda @id_empresa = :id_empresa, @id_servico = :id_servico, @id_user_finalizacao = :id_user_finalizacao');
                    $deleteSvAtivo->bindParam(':id_empresa', $_SESSION['id_empresa']);
                    $deleteSvAtivo->bindParam(':id_servico', $_POST['idServico']);
                    $deleteSvAtivo->bindParam(':id_user_finalizacao', $_SESSION['id_user']);
                    $deleteSvAtivo->execute();
                    $row_delete = $deleteSvAtivo->rowCount();
                if($row_delete === 1){
                    echo 'sucesso';
                }
                }catch(PDOException $e){
                    echo $e;
                }
                
            } else {
                echo 'error';
            }
        }
    } catch (PDOException $e) {
        echo $e;
    }
} else {
    echo 'error';
}
