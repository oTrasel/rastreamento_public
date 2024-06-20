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
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="stylesheet" href="../css/cadastro_local_sv.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <title>Cadastro Locais de Serviço</title>
</head>

<body>
    <style>

    </style>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/verifica_cidades_uf.php');
    include('../manager/auxiliares/verifica_local_servico.php');
    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Local de Trabalho</h3>
                <form id="cadastro_local_sv" action="../manager/cadastros/cadastro_local_sv.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <select class="form-select mt-2" name="selectCidade" id="cidadeUF" required>
                            <?php
                            foreach ($cidades_uf as $cidade) {
                                echo '<option id="' . $cidade['cidade_id'] . '|' . $cidade['uf_id'] . '" value="' . $cidade['cidade_id'] . '|' . $cidade['uf_id'] . '">' . $cidade['sigla'] . ' - ' . $cidade['nome'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="nome" placeholder="Nome do Local" name="nome_local" required>
                        <label for="nome">Nome do Local</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="supervisor" placeholder="Surpevisor do Local" name="supervisor_local" required>
                        <label for="supervisor">Surpevisor do Local</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="tel" class="form-control" id="telefone" name="telefone_supervisor" placeholder="Telefone" maxlength="15" pattern="\(\d{2}\)\s*\d{5}-\d{4}" required>
                        <label for="telefone">Telefone</label>
                    </div>
                    <button class="btn btn-lg btn-outline-primary mt-3 w-100 " type="button" data-bs-toggle="modal" data-bs-target="#confirmarMapa">Selecionar o Local</button>

            </div><!-- FIM itensContent-->
            <?php
            if ($row !== 0) {
            ?>
                <hr style="border: solid 1px black;">
                <button type="button" class="btn btn-outline-secondary mt-1 w-100" data-bs-toggle="modal" data-bs-target="#locaisCadastrados">
                    Verificar Locais Cadastrados
                </button>
            <?php } ?>

        </div><!-- FIM content-->

    </div><!-- FIM container-->

    <!-- MODAL PARA SELECIONAR O LOCAL NO MAPA -->
    <div class="modal fade" id="confirmarMapa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Selecione o Centro do Local de Serviço</h5>
                </div>
                <div class="modal-body">
                    <input type="text" id="coordenadas" name="coordenadas" value="" hidden>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating">
                                <input id="address" type="text" class="form-control h-100" placeholder="Digite o endereço">
                                <label for="address">Digite o endereço</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-primary h-100" type="button" onclick="searchAddress()">Pesquisar</button>
                        </div>
                    </div>
                    <div class="mt-2" id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="cadastroBt" class="btn btn-primary" disabled>Cadastrar Local</button>
                </div>
                </form> <!--FINAL FORMULARIO DE CADASTRO DE VINCULO-->
            </div>
        </div>
    </div>




    <!-- MODAL DE LOCAIS JA CADASTRADOS -->
    <div class="modal fade" id="locaisCadastrados" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Locais Cadastrados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="gridInfos">
                        <table class="table table-striped" id="tableInfos">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" id="tableHead">Local</th>
                                    <th scope="col" id="tableHead">UF/Cidade</th>
                                    <th scope="col" id="tableHead">Data Cadastro</th>
                                    <th scope="col" id="tableHead">User Cadastro</th>
                                    <th scope="col" id="tableHead">Supervisor</th>
                                    <th scope="col" id="tableHead">Contato</th>
                                    <th scope="col" id="tableHead">Editar</th>
                                </tr>
                            </thead>
                            <tbody id="conteudo">
                                <?php
                                if ($row !== 0) {
                                    $index = 0;
                                    foreach ($locais_trabalho as $local) {
                                        $index++;
                                        echo '
                                    <tr>
                                        <td id="row" > ' . $local['nome_local'] . '</td>
                                        <td id="row" > ' . $local['cidade'] . '</td>
                                        <td id="row" > ' . $local['dt_cadastro'] . '</td>
                                        <td id="row" > ' . $local['user_cadastro_local'] . '</td>
                                        <td id="row" > ' . $local['supervisor_local'] . '</td>
                                        <td id="row" > ' . $local['contato_supervisor'] . '</td>
                                        <td id="row" > <button type="button" class="btn btn-secondary w-100 h-100" data-bs-toggle="modal" data-bs-target="#editarModal" data-id="' . $local['id_local'] . '"> <i class="bi bi-pencil-square"></i></button></td>
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
    <form action="../manager/cadastros/processa_alteracao_local_sv.php" method="post" id="formAtualizaInfo">
    <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Local de Serviço</h1>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="lat_central" value="" name="lat_centro">
                    <input type="hidden" id="long_central" value="" name="long_centro">
                    <input type="hidden" id="id_local" value="" name="id_loc">
                    <table class="table table-striped" id="tableEditar">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" id="tableHead">Local</th>
                                <th scope="col" id="tableHead">UF/Cidade</th>
                                <th scope="col" id="tableHead">Data Cadastro</th>
                                <th scope="col" id="tableHead">User Cadastro</th>
                                <th scope="col" id="tableHead">Supervisor</th>
                                <th scope="col" id="tableHead">Contato</th>
                            </tr>
                        </thead>
                        <tbody id="conteudo">
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating " style="color: gray;">
                                <select class="form-select " name="selectCidade" id="cidadeUFEditavel" required>
                                    <?php
                                    foreach ($cidades_uf as $cidade) {
                                        echo '<option id="' . $cidade['cidade_id'] . '|' . $cidade['uf_id'] . '" value="' . $cidade['cidade_id'] . '|' . $cidade['uf_id'] . '">' . $cidade['sigla'] . ' - ' . $cidade['nome'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-floating mt-3" style="color: gray;">
                                <input type="text" class="form-control" id="nomeEditavel" placeholder="Nome do Local" name="nome_local" required>
                                <label for="nome">Nome do Local</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating " style="color: gray;">
                                <input type="text" class="form-control" id="supervisorEditavel" placeholder="Supervisor do Local" name="supervisor_local" required>
                                <label for="supervisor">Supervisor do Local</label>
                            </div>
                            <div class="form-floating mt-3" style="color: gray;">
                                <input type="tel" class="form-control" id="telefone2" name="telefone_supervisor" placeholder="Telefone" maxlength="15" pattern="\(\d{2}\)\s*\d{5}-\d{4}" required>
                                <label for="telefone2">Telefone</label>
                            </div>
                        </div>
                    </div>



                    <div class="mt-2" id="map2" style="height: 400px; width: 100%; border-radius: 10px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#locaisCadastrados">Cancelar</button>
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
    <script src="../js/paginas/administracao/cadastro_local_sv.js"></script>
    <script src="../js/frameworks/select2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=&libraries=drawing&callback=initMap" async defer></script>
    <script>
        //SCRIPT NO QUAL MASCARÁ O CAMPO DE TELEFONE
        const tel = document.getElementById('telefone') // Seletor do campo de telefone

        tel.addEventListener('keypress', (e) => mascaraTelefone(e.target.value)) // Dispara quando digitado no campo
        tel.addEventListener('change', (e) => mascaraTelefone(e.target.value)) // Dispara quando autocompletado o campo

        const mascaraTelefone = (valor) => {
            valor = valor.replace(/\D/g, "")
            valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2")
            valor = valor.replace(/(\d)(\d{4})$/, "$1-$2")
            tel.value = valor // Insere o(s) valor(es) no campo
        }


        const tel2 = document.getElementById('telefone2'); // Corrigindo o seletor para o campo de telefone

        tel2.addEventListener('keypress', (e) => mascaraTelefone2(e.target.value)); // Usando mascaraTelefone2
        tel2.addEventListener('change', (e) => mascaraTelefone2(e.target.value)); // Usando mascaraTelefone2

        const mascaraTelefone2 = (valor) => {
            valor = valor.replace(/\D/g, "");
            valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
            valor = valor.replace(/(\d)(\d{4})$/, "$1-$2");
            tel2.value = valor;
        }


        // Aplicar o Select2 ao elemento de seleção
        $(document).ready(function() {

            $('#cidadeUF').select2({
                placeholder: 'Selecione uma cidade', // Substitua pelo texto desejado
                allowClear: true // Adiciona um "x" para limpar a seleção
            });
            $('#cidadeUF').val(null).trigger('change');
            // Definir a altura diretamente no contêiner do Select2
            $('#cidadeUF').next('.select2-container').find('.select2-selection--single').css('height', '40px');
        });



        $(document).ready(function() {
            // Inicializar o mapa e o marcador
            var mapa;
            var marcador;

            function inicializarMapa(lat, lng) {
                var coordenadas = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                };

                mapa = new google.maps.Map(document.getElementById('map2'), {
                    center: coordenadas,
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.HYBRID,
                    streetViewControl: false,
                    mapTypeControl: false,
                    fullscreenControl: false,
                    styles: [{
                        featureType: 'poi',
                        stylers: [{
                            visibility: 'off'
                        }],
                    }, ],
                });

                marcador = new google.maps.Marker({
                    position: coordenadas,
                    map: mapa,
                    draggable: true
                });

                // Evento para atualizar as coordenadas quando o marcador for movido
                google.maps.event.addListener(marcador, 'dragend', function(event) {
                    var novaPosicao = this.getPosition();

                    // Atualizar as coordenadas no formulário
                    document.getElementById('lat_central').value = novaPosicao.lat();
                    document.getElementById('long_central').value = novaPosicao.lng();
                });
            }

            // Abrir o modal e preencher os campos
            $('#editarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id = button.data('id'); // Extrair o ID do botão
                document.getElementById('id_local').value = id;
                // Filtrar os dados do local correspondentes ao ID selecionado
                var local = <?php echo json_encode($locais_trabalho); ?>.find(function(local) {
                    return local.id_local == id;
                });

                // Preencher os campos do modal de edição com os dados do local
                $('#tableEditar tbody').html(`
        <tr>
            <td id="row">${local.nome_local}</td>
            <td id="row">${local.cidade}</td>
            <td id="row">${local.dt_cadastro}</td>
            <td id="row">${local.user_cadastro_local}</td>
            <td id="row">${local.supervisor_local}</td>
            <td id="row">${local.contato_supervisor}</td>
        </tr>
    `);

                // Preencher os valores dos inputs com os dados do local
                $('#cidadeUFEditavel').val(local.cidade_id + '|' + local.uf_id);
                $('#nomeEditavel').val(local.nome_local);
                $('#supervisorEditavel').val(local.supervisor_local);
                $('#telefone2').val(local.contato_supervisor);

                // Atualizar apenas a posição do marcador
                if (!mapa) {
                    inicializarMapa(local.lat_central, local.long_central);
                } else {
                    var coordenadas = new google.maps.LatLng(local.lat_central, local.long_central);
                    marcador.setPosition(coordenadas);
                    mapa.setCenter(coordenadas);
                }

                // Atualizar as coordenadas do marcador no formulário
                document.getElementById('lat_central').value = local.lat_central;
                document.getElementById('long_central').value = local.long_central;
            });

        });
    </script>
</body>


</html>