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
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../bootstrap/icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="stylesheet" href="../css/forms_cadastro.css">
    <link rel="stylesheet" href=" ../css/servico_ativo.css">
    <link rel="shortcut icon" href="../images/favicon.ico"/>
    <title>Cadastro Serviços</title>
</head>

<body>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/verifica_sv_ativos.php');

    if ($row_veiculos === 0) {
        echo '<script>alert("Para iniciar um Serviço com Veiculo, é necessário vincular um GPS A UM VEICULO");</script>';
    }


    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro de Rondas</h3>
                <div class="form-floating mt-3" style="color: gray;">
                    <button type="button" class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#modalVeiculo">
                        Iniciar Nova Ronda com Veiculo
                    </button>
                </div>
                <div class="form-floating mt-3" style="color: gray;">
                    <button type="button" class="btn btn-primary w-75 mb-2" data-bs-toggle="modal" data-bs-target="#modalApe">
                        Iniciar Nova Ronda
                    </button>
                </div>
            </div><!-- FIM itensContent-->
        </div><!-- FIM content-->
    </div><!-- FIM container-->
    <!-- Button trigger modal -->




    <?php
    if ($row_sv === -1) {
        echo '<div id="centro">
                <div id="infosGpsVinculos">';
        echo '<div class="row mt-2" style="color: white; width: 90%;" id="tituloInfosGps">
                <div class="col" id="colunas" style="border: 1px solid black;">Finalizar Ronda</div>
                <div class="col" id="colunas" style="border: 1px solid black;">N° Ronda</div>
                <div class="col" id="colunas" style="border: 1px solid black;">Veiculo</div>
                <div class="col" id="colunas" style="border: 1px solid black;">Tipo de Ronda</div>
                <div class="col" id="colunas" style="border: 1px solid black;">Cartão Programa</div>
                <div class="col" id="colunas" style="border: 1px solid black;">Prestador</div>
                <div class="col" id="colunas" style="border: 1px solid black;">Data Inicio</div>
              </div>';

        $corAtual = 'grey';
        foreach ($servicosAtivos as $row) {
            $corAtual = ($corAtual == 'grey') ? '#363636' : 'grey';
            $idServico = $row['id_servico'];

            echo '<div class="row mt-2" style="color: white; width: 90%; background-color: ' . $corAtual . ';">
                    <div class="col" style="border: 1px solid black;">
                        <button type="button" class="btn btn-outline-danger btn-finalizar" data-id="' . $idServico . '" data-bs-toggle="modal" data-bs-target="#fimRonda">Finalizar<i class="bi bi-person-x-fill ms-2"></i></button>
                    </div>
                    <div class="col" style="border: 1px solid black;">' . $idServico . '</div>
                    <div class="col" style="border: 1px solid black;">' . $row['Veiculo'] . '</div>
                    <div class="col" style="border: 1px solid black;">' . $row['descr_tipo'] . '</div>
                    <div class="col" style="border: 1px solid black;">' . $row['Cartao'] . '</div>
                    <div class="col" style="border: 1px solid black;">' . $row['nome_user'] . '</div>
                    <div class="col" style="border: 1px solid black;">' . $row['data'] . '</div>
                  </div>';
        }

        echo '</div> <!-- FIM infosGpsVinculos-->
              </div><!-- FIM Centro-->';
    }

    ?>






    <!-- Modal veiculo-->
    <div class="modal fade" id="modalVeiculo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalVeiculo" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cadastrar Ronda com Veiculo</h1>
                </div>
                <form id="cadastro_servico" action="../manager/cadastros/cadastro_servicos.php" method="post" role="form" style="display: block;">
                    <input type="hidden" name="servicoVeiculo">
                    <div class="modal-body">
                        <div class="form-floating mt-3" style="color: gray;">
                            <input type="text" class="form-control" id="descrServico" placeholder="Descrição do Serviço" name="descr_servico" required>
                            <label for="descrServico">Descrição da Ronda</label>
                        </div>
                        <div class="form-floating mt-3" style="color: gray;">
                            <select class="form-select mt-2" name="selectPrestador" id="prestador" required>
                                </option>
                                <?php
                                foreach ($prestador as $prestadores) {
                                    echo '<option id="' . $prestadores['id_user'] . $prestadores['nome_user'] . '" value="' . $prestadores['id_user'] . '">' . $prestadores['nome_user'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-floating mt-3" style="color: gray;">
                            <select class="form-select mt-2" name="selectCartao" id="cartao" required>
                                <option id="semCartao" value="semCartao"> -> Ronda Livre <- </option>
                                        <?php
                                        foreach ($cartaoPrograma as $cartao) {
                                            echo '<option id="' . $cartao['id_cartao'] . $cartao['descr_cartao'] . '" value="' . $cartao['id_cartao'] . '">' . $cartao['descr_cartao'] . '</option>';
                                        }
                                        ?>
                            </select>
                        </div>

                        <div class="form-floating mt-3" style="color: gray;">
                            <select class="form-select mt-2" name="selectVeiculo" id="veiculo" required>
                                <?php
                                foreach ($veiculos as $veiculo) {
                                    echo '<option id="' . $veiculo['veiculo'] . $veiculo['veiculo'] . '" value="' . $veiculo['id_veiculo_seguranca'] . "|" . $veiculo['id_rastreador'] . '">' . $veiculo['veiculo'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-floating mt-3" style="color: gray;">
                            <input type="datetime-local" class="form-control" id="dtiniServico" placeholder="Data Inicial do Serviço" name="data_inicio_sv" required>
                            <label for="dtiniServico">Data prevista de incio</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="iniciarVeiculoBt">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal a pé-->
    <div class="modal fade" id="modalApe" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalApe" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cadastrar Ronda</h1>
                </div>
                <form id="cadastro_servico_solo" action="../manager/cadastros/cadastro_servicos.php" method="post" role="form" style="display: block;">
                    <input type="hidden" name="servicoSolo">
                    <div class="modal-body">
                        <div class="form-floating mt-3" style="color: gray;">
                            <input type="text" class="form-control" id="descrRonda" placeholder="Descrição do Serviço" name="descr_servico" required>
                            <label for="descrServico">Descrição da Ronda</label>
                        </div>
                        <div class="form-floating mt-3" style="color: gray;">
                            <select class="form-select mt-2" name="selectPrestador" id="slprestador" required>
                                <?php
                                foreach ($prestador as $prestadores) {
                                    echo '<option id="' . $prestadores['id_user'] . $prestadores['nome_user'] . '" value="' . $prestadores['id_user'] . '">' . $prestadores['nome_user'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-floating mt-3" style="color: gray;">
                            <select class="form-select mt-2" name="selectCartao" id="slcartao" required>
                                <option id="semCartao" value="semCartao"> -> Ronda Livre <- </option>
                                        <?php
                                        foreach ($cartaoPrograma as $cartao) {
                                            echo '<option id="' . $cartao['id_cartao'] . $cartao['descr_cartao'] . '" value="' . $cartao['id_cartao'] . '">' . $cartao['descr_cartao'] . '</option>';
                                        }
                                        ?>
                            </select>
                        </div>

                        <div class="form-floating mt-3" style="color: gray;">
                            <select class="form-select mt-2" name="selectRastreador" id="slrastreador" required>
                                <?php
                                foreach ($rastreadores as $rastreador) {
                                    echo '<option id="' . $rastreador['id_rastreador'] . $rastreador['descr_rastreador'] . '" value="' . $rastreador['id_rastreador'] . '">' . $rastreador['descr_rastreador'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-floating mt-3" style="color: gray;">
                            <input type="datetime-local" class="form-control" id="dtiniRonda" placeholder="Data Inicial do Serviço" name="data_inicio_sv" required>
                            <label for="dtiniRonda">Data prevista de incio</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="iniciarRondaBT">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--MODAL FINALIZAR-->
    <div class="modal fade" id="fimRonda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Finalização de Ronda</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="finalizaRonda" action="../manager/cadastros/finalizar_sv.php" method="post" role="form" style="display: block;">
                    <div class="modal-body">
                        <span style="background-color: yellow; color: red; text-align: center; font-weight: bold; font-size: 93%;"><i class="bi bi-exclamation-triangle"></i>DIGITE SUA SENHA PARA FINALIZAR A RONDA SELECIONADA<i class="bi bi-exclamation-triangle"></i></span>
                        <input type="hidden" id="campoOcultoID" value="" name="idServico">

                        <div class="form-floating mt-3" style="color: gray;">
                            <input onkeyup="validaSenha()" type="password" class="form-control" name="passwd" id="password" placeholder="Senha" required>
                            <label for="password">Senha</label>
                        </div>
                        <div class="form-floating mt-3" style="color: gray;">
                            <input onkeyup="validaSenha()" type="password" class="form-control" id="c_password" placeholder="Confirmar Senha" required>
                            <label for="c_password">Confirmar Senha</label>
                            <p id="confirmPass" class="w-100" style="color: white;"></p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelarFinalizacao">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="finalizarRondaBt">Finalizar Ronda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</body>
<script src="../js/frameworks/jquery-3.6.4.js"></script>
<script src="../js/frameworks/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/paginas/servicos/cadastro_sv.js"></script>
<script src="../js/frameworks/select2.min.js"></script>

</html>