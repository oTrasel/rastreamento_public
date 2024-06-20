<?php
include('../conexao.php');
session_start();
date_default_timezone_set('America/Sao_Paulo');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    

    //verifica o tipo de serviço
    if (isset($_POST["servicoVeiculo"])) {
        $tipo_sv = 1;
        $id = explode('|', $_POST['selectVeiculo']);
        $veiculo = $id[0];
        $rastreador = $id[1];
    } elseif (isset($_POST["servicoSolo"])) {
        $veiculo = null;
        $tipo_sv = 2;
        $rastreador = $_POST['selectRastreador'];
    }
    //verifica se tem cartão selecionado
    if (isset($_POST['selectCartao']) && $_POST['selectCartao'] === 'semCartao') {
        $cartaoPrograma = null;
    } else {
        $cartaoPrograma = $_POST['selectCartao'];
    }

    try {

        $dt_entrada_desformatada = $_POST['data_inicio_sv'];
        $dt_entrada = date_create($dt_entrada_desformatada);
        $dt_entrada_formatada = date_format($dt_entrada, 'Ymd H:i:s');
        $insert_sv = $pdo->prepare('EXEC CriaServicoAtivoNew 
                                    @id_prestador = :id_prestador,
                                    @descr_servico = :descr_servico,
                                    @dt_entrada_desformatada = :dt_entrada_desformatada,
                                    @id_seguranca_cadastro = :id_seguranca_cadastro,
                                    @tipo_servico = :tipo_servico,
                                    @id_veiculo = :id_veiculo,
                                    @id_cartao_programa = :id_cartao_programa,
                                    @id_rastreador = :id_rastreador,
                                    @id_empresa = :id_empresa,
                                    @local_trabalho = :local_trabalho ');
        $insert_sv->bindParam(':id_prestador', $_POST['selectPrestador']);
        $insert_sv->bindParam(':descr_servico', $_POST['descr_servico']);
        $insert_sv->bindParam(':dt_entrada_desformatada', $dt_entrada_formatada);
        $insert_sv->bindParam(':id_seguranca_cadastro', $_SESSION['id_user']);
        $insert_sv->bindParam(':tipo_servico', $tipo_sv);
        $insert_sv->bindParam(':id_veiculo', $veiculo);
        $insert_sv->bindParam(':id_cartao_programa', $cartaoPrograma);
        $insert_sv->bindParam(':id_rastreador', $rastreador);
        $insert_sv->bindParam(':id_empresa', $_SESSION['id_empresa']);
        $insert_sv->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
        $insert_sv->execute();
        $row = $insert_sv->rowCount();
        if ($row === 1) {
            echo 'sucesso';
        }
    } catch (PDOException $error) {
        echo $error;
    }
}
