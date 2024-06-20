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
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/forms_cadastro.css">
    <link rel="stylesheet" href="../css/cadastro_tipo_ponto.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <title>Cadastro Tipos de Pontos</title>
</head>

<body>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/busca_tipo_pontos.php');
    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro de Tipos de Pontos</h3>
                <form id="cadastro_tipo_ponto" action="../manager/cadastros/cadastro_tipo_ponto.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="descrTipo" placeholder="Descrição" name="tipoDescr" maxlength="100" required>
                        <label for="funcao_descr">Descrição</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;">
                            <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">
                                ESCOLHA COR DO PONTO</h6>
                            <div class="form-floating" style="color: gray;">
                                <input type="color" class="form-control" id="corPonto" placeholder="Cor do Ponto" name="pontoCor" required oninput="updateHexColor(this)">
                            </div>
                        </section>
                    </div>
                    <button class="btn btn-lg btn-outline-primary mt-3 w-100 " type="submit" id="cadastroBt">Cadastrar</button>
                </form><!-- FIM login-form-->
            </div><!-- FIM itensContent-->
            <hr style="border: solid 1px black;">
            <button type="button" class="btn btn-outline-secondary mt-1 w-100" data-bs-toggle="modal" data-bs-target="#enderecoCadastrado">
                Verificar Tipos de pontos Cadastrados
            </button>
        </div><!-- FIM content-->
    </div><!-- FIM container-->

    <!-- MODAL DE USERS JA CADASTRADOS -->
    <div class="modal fade" id="enderecoCadastrado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tipos de Pontos Cadastrados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="gridInfos">
                        <table class="table table-striped" id="tableInfos">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" id="tableHead">Descrição</th>
                                    <th scope="col" id="tableHead">Cor</th>
                                    <th scope="col" id="tableHead">Endereços Vinculados</th>
                                    <th scope="col" id="tableHead">Habilitado</th>
                                    <th scope="col" id="tableHead">User Cadastro</th>
                                    <th scope="col" id="tableHead">Data Cadastro</th>
                                    <th scope="col" id="tableHead">Editar</th>
                                </tr>
                            </thead>
                            <tbody id="conteudo">
                                <?php
                                if ($row !== 0) {
                                    foreach ($tipo_ponto as $pontos) {
                                        echo '
                                    <tr>
                                        <td id="row" > ' . $pontos['tipo_ponto'] . '</td>
                                        <td id="row" > <i class="bi bi-square-fill" style="color: ' . $pontos['cor'] . '; font-size: 30px;"></i></td>
                                        <td id="row" > ' . $pontos['qtd_endereco'] . '</td>
                                        <td id="row" > ' . $pontos['habilitado'] . '</td>
                                        <td id="row" > ' . $pontos['nome_user'] . '</td>
                                        <td id="row" > ' . $pontos['dt_insercao'] . '</td>
                                        <td id="row" > <but$userpe="button" class="btn btn-secondary w-100 h-100" data-bs-toggle="modal" data-bs-target="#editarModal" data-id="' . $pontos['id_tipo_ponto'] . '"> <i class="bi bi-pencil-square"></i></button></td>
                                    </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL EDITAR LOCAIS -->
    <form action="../manager/cadastros/processa_alteracao_tipo_ponto.php" method="post" id="formAtualizaInfo">
        <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Tipos de Pontos</h1>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idTipoPonto" value="" name="idPonto">
                        <table class="table table-striped" id="tableEditar">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" id="tableHead">Descrição</th>
                                    <th scope="col" id="tableHead">Cor</th>
                                    <th scope="col" id="tableHead">Endereços Vinculados</th>
                                    <th scope="col" id="tableHead">Habilitado</th>
                                    <th scope="col" id="tableHead">User Cadastro</th>
                                    <th scope="col" id="tableHead">Data Cadastro</th>
                                </tr>
                            </thead>
                            <tbody id="conteudo">
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;">
                                        <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">
                                            Descrição</h6>
                                        <div class="form-floating" style="color: gray;">
                                            <input type="text" class="form-control" id="editavelDescr" placeholder="Descrição" name="descrEditavel">
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;">
                                        <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">
                                            ESCOLHA COR DO PONTO</h6>
                                        <div class="form-floating" style="color: gray;">
                                            <input type="color" class="form-control" id="editavelCor" placeholder="Cor do Ponto" name="corEditavel" required oninput="updateHexColor(this)">
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check w-50" name="habilitado" id="btnHabilitado" autocomplete="off" value="1">
                                        <label class="btn btn-outline-primary" for="btnHabilitado">Habilitado</label>

                                        <input type="radio" class="btn-check w-50" name="habilitado" id="btnDesabilitado" autocomplete="off" value="0">
                                        <label class="btn btn-outline-primary" for="btnDesabilitado">Desabilitado</label>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#userCadastrados">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="confirmarEdicao">Confirmar Edição</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script src="../js/frameworks/jquery-3.6.4.js"></script>
    <script src="../js/frameworks/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/frameworks/jquery.dataTables.min.js"></script>
    <script src="../js/frameworks/dataTables.bootstrap5.min.js"></script>
    <script src="../js/paginas/administracao/cadastro_tipo_ponto.js"></script>
    <script>
        function updateHexColor(input) {
            var hexColor = input.value; // Obtém o valor selecionado no formato padrão RGB
            var hex = rgbToHex(hexColor); // Converte para hexadecimal
            input.value = hex; // Atualiza o valor do input para hexadecimal
        }

        function rgbToHex(rgb) {
            // Extrai os valores de R, G e B do formato RGB
            var values = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
            // Converte os valores para hexadecimal e os formata adequadamente
            function hex(x) {
                return ("0" + parseInt(x).toString(16)).slice(-2);
            }
            var hexColor = "#" + hex(values[1]) + hex(values[2]) + hex(values[3]);
            return hexColor.toUpperCase(); // Retorna o valor hexadecimal em maiúsculas
        }

        $(document).ready(function() {
            // Abrir o modal e preencher os campos
            $('#editarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id = button.data('id'); // Extrair o ID do botão
                document.getElementById('idTipoPonto').value = id;
                // Filtrar os dados correspondentes ao ID selecionado
                var ponto = <?php echo json_encode($tipo_ponto); ?>.find(function(tipo_ponto) {
                    return tipo_ponto.id_tipo_ponto == id;
                });

                // Preencher os campos do modal de edição com os dados do local
                // Preencher os campos do modal de edição com os dados do usuário
                $('#tableEditar tbody').html(`
                    <tr>
                        <td id="row">${ponto.tipo_ponto}</td>
                        <td id="row">${ponto.cor}</td>
                        <td id="row">${ponto.qtd_endereco}</td>
                        <td id="row">${ponto.habilitado}</td>
                        <td id="row">${ponto.nome_user}</td>
                        <td id="row">${ponto.dt_insercao}</td>
                    </tr>
                `);


                $('#editavelDescr').val(ponto.tipo_ponto);
                $('#editavelCor').val(ponto.cor);

               

                if (ponto.habilitado === 'Sim') {
                    $('#btnHabilitado').prop('checked', true);
                } else {
                    $('#btnDesabilitado').prop('checked', true);
                }


            });

        });
    </script>
</body>


</html>