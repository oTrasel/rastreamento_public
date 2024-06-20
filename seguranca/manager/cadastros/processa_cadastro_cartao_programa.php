<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    try {
        $dataHoraAtual = new DateTime();
        $dataHoraFormatada = $dataHoraAtual->format('Y-m-d H:i:s');

        $insert_cartao = $pdo->prepare("insert into cartao_programa (descr_cartao, dt_cadastro, habilitado, user_cadastro_cartao, id_empresa, dt_incio_cartao, dt_fim_cartao)
                                            values (:descr_cartao, CONVERT(datetime, :dt_cadastro, 120), 1, :user_cadastro_cartao, :id_empresa, :dt_incio_cartao, :dt_fim_cartao)");
        $insert_cartao->bindParam(':descr_cartao', $_POST['descr_cartao']);
        $insert_cartao->bindParam(':dt_cadastro', $dataHoraFormatada);
        $insert_cartao->bindParam(':user_cadastro_cartao', $_SESSION['id_seguranca']);
        $insert_cartao->bindParam(':id_empresa', $_SESSION['id_empresa']);
        $insert_cartao->bindParam(':dt_incio_cartao', $_POST['inicioHr']);
        $insert_cartao->bindParam(':dt_fim_cartao', $_POST['fimHr']);

        $insert_cartao->execute();
        $row_inser_cartao = $insert_cartao->rowCount();
        if ($row_inser_cartao === 1) {
            $select = $pdo->prepare("SELECT MAX(id_cartao) as id_cartao, descr_cartao, user_cadastro_cartao, dt_cadastro
                             FROM cartao_programa
                             WHERE descr_cartao = :descr_cartao 
                             AND user_cadastro_cartao = :user_cadastro_cartao
                             and id_empresa = :id_empresa
                             AND dt_cadastro = CONVERT(datetime, :dt_cadastro, 120)
                             and dt_incio_cartao = :dt_incio_cartao
                             and dt_fim_cartao = :dt_fim_cartao
                             group by descr_cartao, user_cadastro_cartao, dt_cadastro");
            $select->bindParam(':descr_cartao', $_POST['descr_cartao']);
            $select->bindParam(':user_cadastro_cartao', $_SESSION['id_seguranca']);
            $select->bindParam(':dt_cadastro', $dataHoraFormatada);
            $select->bindParam(':id_empresa', $_SESSION['id_empresa']);
            $select->bindParam(':dt_incio_cartao', $_POST['inicioHr']);
            $select->bindParam(':dt_fim_cartao', $_POST['fimHr']);
            
            $select->execute();

            $result_select = $select->fetch(PDO::FETCH_ASSOC);
            $row_select = $select->rowCount();
            $ordem = 1;
            $idEndereco = $_POST['selectEndEspecial'];
            if ($row_select === -1) {
                foreach ($idEndereco as $ponto) {
                    $insert_pontos = $pdo->prepare("insert into cartao_programa_pontos (id_cartao, id_endereco, ordem)
                                                        values (:id_cartao, :id_endereco, :ordem)");
                    $insert_pontos->bindParam(':id_cartao', $result_select['id_cartao']);
                    $insert_pontos->bindParam(':id_endereco', $ponto);
                    $insert_pontos->bindParam(':ordem', $ordem);
                    $insert_pontos->execute();
                    $ordem++;
                }
                echo 'sucesso';
            }
        }
    } catch (PDOException $error) {
        echo $error;
    }
    

    
}else{
    echo 'erro';
}