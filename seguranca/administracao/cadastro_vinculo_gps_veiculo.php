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



    <title>Cadastro vinculo GPS/VEICULO</title>
</head>

<body>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/verifica_vinculo_gps_veiculo.php');
    include('../manager/auxiliares/busca_vinculos_ativos.php');
    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro de Vinculos</h3>

                <div class="form-floating mt-3" style="color: gray;">
                    <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalCadastroVinculo">
                        Cadastrar Vinculo
                    </button>
                </div>
                <?php
                if ($row_va === -1) {
                    echo '<div class="form-floating mt-3" style="color: gray;">
                            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#finalizaVinculo">
                                Verificar Vinculos Ativos
                            </button>
                         </div>';
                }
                ?>

            </div><!-- FIM itensContent-->
        </div><!-- FIM content-->
    </div><!-- FIM container-->




    <!-- Modal Cadastrar vinculos-->
    <div class="modal fade" id="modalCadastroVinculo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"> Cadastrar Vinculo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cadastro_vinculo_veiculo_rastreador" action="../manager/cadastros/cadastro_vinculo_rastreador_veiculo.php" method="post" role="form" style="display: block;">
                    <div class="modal-body">
                        <div class="form-floating mt-3 d-flex align-items-center w-100" style="color: gray;">
                            <select class="form-select mt-2 w-100" name="selectRastreador" id="rastreadorSelect" required>
                                <?php
                                foreach ($rastreadores as $rastreadoresLivres) {
                                    echo '<option id="' . $rastreadoresLivres['id_rastreador'] . '" value="' . $rastreadoresLivres['id_rastreador'] . '">' . $rastreadoresLivres['id_rastreador'] . ' - ' . $rastreadoresLivres['descr_rastreador'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-floating mt-3 d-flex align-items-center w-100" style="color: gray;">
                            <select class="form-select mt-2 w-100" name="selectVeiculo" id="veiculoSelect" required>
                                <?php
                                foreach ($veiculos as $veiculosLivres) {
                                    echo '<option id="' . $veiculosLivres['id_veiculo'] . '|' . $veiculosLivres['placa_veiculo'] . '" value="' . $veiculosLivres['id_veiculo'] . '">' . $veiculosLivres['modelo_veiculo'] . ' - ' . $veiculosLivres['placa_veiculo'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="vinculaGpsVeiculo">Vincular</button>
                    </div>
                </form><!-- FIM-form-->
            </div>
        </div>
    </div>






    <!-- Modal -->
    <div class="modal fade" id="finalizaVinculo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="finalizaVinculo" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="finalizaVinculoForm" action="../manager/cadastros/finaliza_vinculo_rastreador_veiculo.php" method="post" role="form" style="display: block;">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Vinculos Ativos</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mt-3 d-flex align-items-center w-100" style="color: gray;">
                            <select class="form-select mt-2 w-100" name="selectDesvinculo" id="desvinculoSelect" required>
                                <?php
                                foreach ($vinculos as $vinculosAt) {
                                    echo '<option id="' . $vinculosAt['id_rastreio'] . '|' . $vinculosAt['veiculo'] . '" value="' . $vinculosAt['id_rastreio'] . '">' . $vinculosAt['descr_rastreador'] . ' | ' . $vinculosAt['veiculo'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="fimVinculo">Finalizar Vinculo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/frameworks/jquery-3.6.4.js"></script>
    <script src="../js/frameworks/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/frameworks/select2.min.js"></script>
    <script src="../js/paginas/administracao/cadastro_vinculo_gps_veiculo.js"></script>
</body>


</html>