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
    <link rel="stylesheet" href="../css/cadastro_end_especial.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />

    <title>Cadastro Endereço Especial</title>
</head>

<body>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/busca_endereco_editavel.php');
    include('../manager/auxiliares/verifica_local_servico.php');
    include('../manager/auxiliares/busca_tipo_pontos_cadastrados.php');
    ?>

    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro de Local Especial</h3>
                <form id="cadastro_local_especial" action="../manager/cadastros/cadastro_end_especial.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="nome" placeholder="Nome do Endereço" name="nome_endereco" required>
                        <label for="nome">Nome do Endereço</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="endereco" placeholder="Endereço" name="endereco_local" required>
                        <label for="endereco">Endereço</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <select class="form-select mt-2" name="pontoTipo" id="tipoPonto" required>
                            <?php
                            foreach ($tipo_ponto as $pontos) {
                                echo '<option id="' . $pontos['tipo_ponto'] . '" value="' . $pontos['id_tipo_ponto'] . "|" . $pontos['tipo_ponto'] . '">' . $pontos['tipo_ponto'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <select class="form-select mt-2" name="servicoLocal" id="localServico" required>
                            <?php
                            foreach ($locais_trabalho as $locais) {
                                echo '<option id="' . $locais['nome_local'] . '" value="' . $locais['id_local'] . "|" . $locais['nome_local'] . '">' . $locais['nome_local'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;">
                        <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">
                            TIPO MARCAÇÃO</h6>
                        <div class="form-floating" style="color: gray;">
                            <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check w-50" name="networkType" id="btnradioPoligono" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="btnradioPoligono">Poligonal</label>

                                <input type="radio" class="btn-check w-50" name="networkType" id="btnradioPonto" autocomplete="off">
                                <label class="btn btn-outline-primary" for="btnradioPonto">Pontos</label>
                            </div>
                        </div>
                    </section>
                    <div class="form-floating mt-3" style="color: gray;" id="raioContainer">
                        <input type="number" class="form-control" id="raio" placeholder="Escolha o Raio do Ponto em METROS" name="raio_ponto" min="10">
                        <label for="raio">Escolha o Raio do Ponto em METROS</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <button class="btn btn-lg btn-outline-primary w-100 " type="button" id="abrirMapa" data-bs-toggle="modal" data-bs-target="#modalMapaCadastro" disabled>Localizar no Mapa</button><!--Botão Abrir modal Mapa-->
                    </div>
            </div><!-- FIM itensContent-->
            <hr style="border: solid 1px black;">
            <button type="button" class="btn btn-outline-secondary mt-1 w-100" data-bs-toggle="modal" data-bs-target="#enderecoCadastrado">
                Verificar Endereços Cadastrados
            </button>
        </div><!-- FIM content-->
    </div><!-- FIM container-->

    <!-- Modal aonde contem o mapa para cadastro do Poligono -->
    <div class="modal fade" id="modalMapaCadastro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalMapaCadastro" aria-hidden="true" data-bs-theme="dark" style="color: white;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Localize o Endereço Especial</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="map" style="height: 600px;"></div>
                    <input type="text" hidden id="coordenadas" name="json_coordenadas">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelar">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="removePoligono">Remover Poligono Desenhado</button>
                    <button type="submit" class="btn btn-primary" id="cadastrarPoligono">Cadastrar Endereço</button>
                </div>
                </form><!-- FIM login-form-->
            </div>
        </div>
    </div>



    <!-- MODAL DE USERS JA CADASTRADOS -->
    <div class="modal fade" id="enderecoCadastrado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Endereços Cadastrados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="gridInfos">
                        <table class="table table-striped" id="tableInfos">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" id="tableHead">Descrição</th>
                                    <th scope="col" id="tableHead">Tipo</th>
                                    <th scope="col" id="tableHead">Raio</th>
                                    <th scope="col" id="tableHead">Tipo Endereço</th>
                                    <th scope="col" id="tableHead">Local</th>
                                    <th scope="col" id="tableHead">User Cadastro</th>
                                    <th scope="col" id="tableHead">Data Cadastro</th>
                                    <th scope="col" id="tableHead">Editar</th>
                                </tr>
                            </thead>
                            <tbody id="conteudo">
                                <?php
                                if ($row !== 0) {
                                    foreach ($endereco as $enderecos) {
                                        echo '
                                    <tr>
                                        <td id="row" > ' . $enderecos['descr_endereco'] . '</td>
                                        <td id="row" > ' . $enderecos['descr_marcacao'] . '</td>
                                        <td id="row" > ' . $enderecos['raio_ponto_mtrs'] . '</td>
                                        <td id="row" > ' . $enderecos['tipo_ponto'] . '</td>
                                        <td id="row" > ' . $enderecos['nome_local'] . '</td>
                                        <td id="row" > ' . $enderecos['nome_user'] . '</td>
                                        <td id="row" > ' . $enderecos['dt_cadastro'] . '</td>
                                        <td id="row" > <but$userpe="button" class="btn btn-secondary w-100 h-100" data-bs-toggle="modal" data-bs-target="#editarModal" data-id="' . $enderecos['id_endereco'] . '"> <i class="bi bi-pencil-square"></i></button></td>
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
    <form action="../manager/cadastros/processa_alteracao_end_esp.php" method="post" id="formAtualizaInfo">
        <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Endereço</h1>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idEndereco" value="" name="enderecoId">
                        <input type="hidden" id="pontosEditados" value="" name="editadosPontos">
                        <table class="table table-striped" id="tableEditar">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" id="tableHead">Descrição</th>
                                    <th scope="col" id="tableHead">Tipo</th>
                                    <th scope="col" id="tableHead">Raio</th>
                                    <th scope="col" id="tableHead">Tipo Endereço</th>
                                    <th scope="col" id="tableHead">Local</th>
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
                                    <input type="text" class="form-control" id="editavelNome" placeholder="Nome" name="nomeEditavel" required>
                                    <label for="editavelNome">Nome Endereço</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <input type="text" class="form-control" id="editavelDescr" placeholder="Descrição" name="descrEditavel" required>
                                    <label for="editavelDescr">Descrição</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <input type="number" class="form-control" id="editavelRaio" placeholder="Raio do Ponto" name="raioEditavel" required min="10">
                                    <label for="editavelRaio">Raio do Ponto</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <select class="form-select mt-2" name="localEditavel" id="editavelLocal" required>
                                        <?php
                                        foreach ($locais_trabalho as $locais) {
                                            echo '<option id="' . $locais['nome_local'] . '" value="' . $locais['id_local'] . "|" . $locais['nome_local'] . '">' . $locais['nome_local'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <select class="form-select mt-2" name="pontoEditavel" id="editavelPonto" required>
                                        <?php
                                        foreach ($tipo_ponto as $pontos) {
                                            echo '<option id="' . $pontos['tipo_ponto'] . '" value="' . $pontos['id_tipo_ponto'] . "|" . $pontos['tipo_ponto'] . '">' . $pontos['tipo_ponto'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check w-50 h-100" name="habilitado" id="btnHabilitado" autocomplete="off" value="1">
                                        <label class="btn btn-outline-primary" for="btnHabilitado">Habilitado</label>

                                        <input type="radio" class="btn-check w-50 h-100" name="habilitado" id="btnDesabilitado" autocomplete="off" value="0">
                                        <label class="btn btn-outline-primary" for="btnDesabilitado">Desabilitado</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="mapEditavel" style="height: 400px;" class="mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#enderecoCadastrado">Cancelar</button>
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
    <script src="../js/paginas/administracao/cadastro_endereco_esp.js"></script>
    <script src="../js/frameworks/select2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=&libraries=drawing&callback=initMap" async defer></script>
    <script>
        $(document).ready(function() {

            $('#localServico').select2({
                placeholder: 'Selecione um Local de Serviço', // Substitua pelo texto desejado
                allowClear: true // Adiciona um "x" para limpar a seleção
            });
            $('#localServico').val(null).trigger('change');
            // Definir a altura diretamente no contêiner do Select2
            $('#localServico').next('.select2-container').find('.select2-selection--single').css('height', '40px');
        });

        $(document).ready(function() {

            $('#tipoPonto').select2({
                placeholder: 'Selecione um tipo de Ponto', // Substitua pelo texto desejado
                allowClear: true // Adiciona um "x" para limpar a seleção
            });
            $('#tipoPonto').val(null).trigger('change');
            // Definir a altura diretamente no contêiner do Select2
            $('#tipoPonto').next('.select2-container').find('.select2-selection--single').css('height', '40px');
        });


        $(document).ready(function() {

            var mapa; // Variável para armazenar o mapa
            var marcador; // Variável para armazenar o marcador
            var circulo; // Variável para armazenar o círculo


            function criarOuAtualizarCirculo(mapa, coordenadas, raio) {
                if (circulo) {
                    // Se o círculo já existe, apenas atualize seu raio
                    circulo.setRadius(raio);
                } else {
                    // Se o círculo não existe, crie um novo círculo
                    circulo = new google.maps.Circle({
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: '#FF0000',
                        fillOpacity: 0.35,
                        map: mapa,
                        center: coordenadas,
                        radius: raio // Raio do círculo em metros
                    });
                }
            }

            // Abrir o modal e preencher os campos
            $('#editarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id = button.data('id'); // Extrair o ID do botão
                document.getElementById('idEndereco').value = id;


                // Filtrar os dados correspondentes ao ID selecionado
                var endereco = <?php echo json_encode($endereco); ?>.find(function(endereco) {
                    return endereco.id_endereco == id;
                });

                // Filtrar os pontos correspondentes ao ID selecionado
                var pontosEndereco = <?php echo json_encode($pontosResult); ?>.filter(function(ponto) {
                    return ponto.id_endereco == id;
                });

                $('#tableEditar tbody').html(`
                    <tr>
                        <td id="row">${endereco.descr_endereco}</td>
                        <td id="row">${endereco.descr_marcacao}</td>
                        <td id="row">${endereco.raio_ponto_mtrs}</td>
                        <td id="row">${endereco.tipo_ponto}</td>
                        <td id="row">${endereco.nome_local}</td>
                        <td id="row">${endereco.nome_user}</td>
                        <td id="row">${endereco.dt_cadastro}</td>
                    </tr>
                `);

                $('#editavelNome').val(endereco.nome_endereco);
                $('#editavelDescr').val(endereco.descr_endereco);
                $('#editavelRaio').val(endereco.raio_ponto_mtrs);

                $('#editavelPonto option').each(function() {
                    if ($(this).val() === endereco.id_tipo_ponto + '|' + endereco.tipo_ponto) {
                        $(this).prop('selected', true);
                    }
                });
                $('#editavelLocal option').each(function() {
                    if ($(this).val() === endereco.id_local + '|' + endereco.nome_local) {
                        $(this).prop('selected', true);
                    }
                });

                if (endereco.habilitado === 'Sim') {
                    $('#btnHabilitado').prop('checked', true);
                } else {
                    $('#btnDesabilitado').prop('checked', true);
                }

                if (endereco.id_tipo_marcacao == 2) {
                    $('#editavelRaio').prop('disabled', true);
                    inicializarMapPoligonos(pontosEndereco);

                } else if (endereco.id_tipo_marcacao == 1) {
                    if (circulo) {
                        circulo = null;
                    }
                    pontosEndereco.forEach(function(ponto) {
                        incializarMapPontos(ponto);
                    });
                    $('#editavelRaio').prop('disabled', false);
                }

                $('#editavelRaio').on('change', function() {
                    var novoRaio = parseInt($(this).val());
                    if (!isNaN(novoRaio)) {
                        criarOuAtualizarCirculo(mapa, coordenadas, novoRaio);
                    } else {
                        console.log('Por favor, insira um valor numérico válido para o raio.');
                    }
                });

            });

            function incializarMapPontos(pontos) {
                var coordenadas = {
                    lat: parseFloat(pontos.latitude),
                    lng: parseFloat(pontos.longitude)
                };

                var mapa = new google.maps.Map(document.getElementById('mapEditavel'), {
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
                    }],
                });

                // Adicionar marcador
                var marcador = new google.maps.Marker({
                    position: coordenadas,
                    map: mapa,
                    draggable: true
                });
                atualizarPontoEditado(coordenadas);

                criarOuAtualizarCirculo(mapa, coordenadas, parseInt(pontos.raio_ponto_mtrs));

                function atualizarPontoEditado(novasCoordenadas) {
                    // Converter as coordenadas para o formato adequado
                    var pontosJson = JSON.stringify([{
                        lat: novasCoordenadas.lat.toString(),
                        lng: novasCoordenadas.lng.toString()
                    }]);
                    document.getElementById('pontosEditados').value = pontosJson;
                }

                // Adicionar evento de arrasto ao marcador
                marcador.addListener('dragend', function(event) {
                    // Obter novas coordenadas do marcador
                    var novasCoordenadas = {
                        lat: event.latLng.lat(),
                        lng: event.latLng.lng()
                    };

                    // Atualizar posição do círculo
                    if (circulo) {
                        circulo.setCenter(novasCoordenadas);
                    }

                    atualizarPontoEditado(novasCoordenadas);
                });
            }

            function inicializarMapPoligonos(pontos) {
                // Converter pontos para um formato adequado para o polígono
                var pontosPoligono = [];
                pontos.forEach(function(ponto) {
                    pontosPoligono.push({
                        lat: parseFloat(ponto.latitude),
                        lng: parseFloat(ponto.longitude)
                    });
                });

                var coordenadas = {
                    lat: parseFloat(pontos[0].latitude), // Usando a primeira latitude como centro do mapa
                    lng: parseFloat(pontos[0].longitude) // Usando a primeira longitude como centro do mapa
                };

                var mapa = new google.maps.Map(document.getElementById('mapEditavel'), {
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
                    }],
                });

                // Criar polígono com os pontos fornecidos
                var poligono = new google.maps.Polygon({
                    paths: pontosPoligono,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35,
                    editable: true
                });
                poligono.setMap(mapa);
                atualizarPontoEditado();

                function atualizarPontoEditado() {
                    var pontosJson = JSON.stringify(poligono.getPath().getArray());
                    document.getElementById('pontosEditados').value = pontosJson;
                }

                // Adicionar listener para o evento de modificação de ponto do polígono
                google.maps.event.addListener(poligono.getPath(), 'set_at', function() {
                    atualizarPontoEditado();
                });

                google.maps.event.addListener(poligono.getPath(), 'insert_at', function() {
                    atualizarPontoEditado();
                });

                google.maps.event.addListener(poligono.getPath(), 'remove_at', function() {
                    atualizarPontoEditado();
                });
            }



        });
    </script>
</body>


</html>