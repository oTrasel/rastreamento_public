<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
include('../manager/conexao.php');
if ($_SESSION['seguranca'] == false || $_SESSION['id_tipo_user'] !== '5') {

    //redireciona para a index.
    header('Location: ../index.php');
    session_destroy();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../bootstrap/icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../css/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="stylesheet" href="../css/forms_cadastro.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />


    <title>Cadastro Cartão Programa</title>
</head>

<body>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/verifica_endereco_esp.php');
    include('../manager/auxiliares/verifica_cartao_ponto.php');
    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro Cartão Programa</h3>
                <form id="cadastro_cartao" action="../manager/cadastros/processa_cadastro_cartao_programa.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="cartao_descr" placeholder="Descrição do Cartão Programa" name="descr_cartao" maxlength="50" required>
                        <label for="cartao_descr">Descrição do Cartão Programa</label>
                    </div>
                    <button class="btn btn-lg btn-outline-primary mt-3 w-100 mb-3" type="button" id="cadastrarPontos" data-bs-toggle="modal" data-bs-target="#modalPontos">Cadastrar Pontos</button>
                    <button type="button" class="btn btn-outline-primary w-100 mb-2 h-100" data-bs-toggle="modal" data-bs-target="#verificaCartoes"> Verificar Cartões Cadastrados</button>
            </div><!-- FIM itensContent-->
        </div><!-- FIM content-->
    </div><!-- FIM container-->

    <!-- Modal -->
    <div class="modal fade" id="modalPontos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPontos" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Adicionar pontos ao Cartão programa</h1>
                </div>
                <div class="modal-body">
                    <div class="mt-3 d-flex w-100" style="color: gray;">
                        <div class="form-floating w-50 justify-content-center align-items-center">
                            <input type="time" class="form-control" id="hrInicio" placeholder="Horario de Inicio" name="inicioHr" style="width: 99%;" required>
                            <label for="hrInicio">Horario de Inicio</label>
                        </div>
                        <div class="form-floating w-50">
                            <input type="time" class="form-control" id="hrFim" placeholder="Horario de Finalização" name="fimHr" style="width: 100%;" required>
                            <label for="hrFim">Horario de Finalização</label>
                        </div>
                    </div>
                    <div class="form-floating mt-3 d-flex align-items-center w-100" style="color: gray;">
                        <select class="form-select mt-2 w-100" name="selectEndEspecial[]" id="enderecoSelect" multiple>
                            <?php
                            foreach ($enderecos as $endereco) {
                                echo '<option value="' . $endereco['id_endereco'] . '">' . $endereco['id_endereco'] . ' - ' . $endereco['descr_endereco'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="pontosPendentes" id="pontosPendentes" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="cadastraCartao">Cadastrar Cartão</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cartões Existentes-->
    <div class="modal fade" id="verificaCartoes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="verificaCartoes" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cartões Criados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body w-100">
                    <?php
                    if ($row_cp === -1) {
                        echo '<div id="centro">
                                <div id="infoCartao" >';
                        echo '<div class="row mt-2" style="color: black; width: 100%;" id="tituloInfos">
                                                
                                                <div class="col" id="colunas" style="border: 1px solid black;">Numero do Cartão</div>
                                                <div class="col" id="colunas" style="border: 1px solid black;">Nome</div>
                                                <div class="col" id="colunas" style="border: 1px solid black;">Data Cadastro</div>
                                                <div class="col" id="colunas" style="border: 1px solid black;">Habilitado</div>
                                                <div class="col" id="colunas" style="border: 1px solid black;">Quantia de pontos</div>
                                              </div>';

                        $corAtual = 'grey';
                        foreach ($cp as $row) {
                            $corAtual = ($corAtual == 'grey') ? '#363636' : 'grey';

                            echo '<div class="row mt-2" id="tituloInfos" style="color: white; width: 100%; background-color: ' . $corAtual . ';">
                                        <div class="col" id="colunas" style="border: 1px solid black;">' . $row['id_cartao'] . '</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">' . $row['descr_cartao'] . '</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">' . $row['dt_cadastro'] . '</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">' . $row['habilitado'] . '</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">' . $row['qtdPontos'] . '</div>
                                  </div>';
                        }
                        echo '</div> <!-- FIM infosGpsVinculos-->
                        </div><!-- FIM Centro-->';
                    }





                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/frameworks/jquery-3.6.4.js"></script>
    <script src="../js/frameworks/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/frameworks/select2.min.js"></script>
    <script src="../js/paginas/administracao/cadastro_cartao_progama.js"></script>
</body>

</html>