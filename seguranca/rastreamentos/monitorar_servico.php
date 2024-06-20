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
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/monitorar_sv.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <title>Cadastro Rastreamentos</title>
</head>


<body>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/busca_opc_marcadores.php');

    ?>

    <div id="container">
        <div id="childrenMap">
            <div id="geral">
                <div class="form-floating" style="color: gray;" id="divTipoDisposicao">
                    <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="maptype" id="ambos" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="ambos">Mapa e Grid</label>

                        <input type="radio" class="btn-check" name="maptype" id="mapOnly" autocomplete="off">
                        <label class="btn btn-outline-primary" for="mapOnly">Apenas Mapa</label>

                        <input type="radio" class="btn-check" name="maptype" id="gridOnly" autocomplete="off">
                        <label class="btn btn-outline-primary" for="gridOnly">Apenas Grid</label>
                    </div>
                </div>
                <div id="horaPai">
                    <div id="horaGrid">
                    </div>
                </div>
                <div id="mapsVinculos"></div>
                <div id="gridInfos">
                    <table class="table table-striped" id="tableInfos">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" id="tableHead">Tipo</th>
                                <th scope="col" id="tableHead">Placa | Prefixo</th>
                                <th scope="col" id="tableHead">(km/h)</th>
                                <th scope="col" id="tableHead">Ignição</th>
                                <th scope="col" id="tableHead">Última Posição</th>
                                <th scope="col" id="tableHead">Parado (Min)</th>
                                <th scope="col" id="tableHead">Área</th>
                                <th scope="col" id="tableHead">Rua</th>
                                <th scope="col" id="tableHead">Quadra</th>
                                <th scope="col" id="tableHead">Lote</th>
                            </tr>
                        </thead>
                        <tbody id="conteudo"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <style>
        #marcadorDiv{
            display: inline-block;
            overflow-y: scroll;
            width: 100%;
            max-height: 430px;
        }
        .lbCheckbox{
            width: 300px;
            margin: 3px;
        }
    </style>

    <div class="modal fade" id="opcMarcadores" tabindex="-1" aria-labelledby="opcExibir" aria-hidden="true" data-bs-theme="dark" style="color: white;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="opcExibirLabel">Configuração de Marcadores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div id="marcadorDiv">
                        <?php
                        if ($row != 0) {
                            foreach ($marcadores as $marc) {
                                echo '<input type="checkbox" class="btn-check exibirCheckbox" id="' . $marc['id_tipo_ponto'] . '" autocomplete="off" onchange="handleChange(this)">';
                                echo '<label class="btn btn-outline-primary lbCheckbox" for="' . $marc['id_tipo_ponto'] . '">' . $marc['tipo_ponto'] . '</label>';
                            }
                        }
                        ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="../js/frameworks/jquery-3.7.0.js"></script>
<script src="../js/frameworks/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/frameworks/jquery.dataTables.min.js"></script>
<script src="../js/frameworks/dataTables.bootstrap5.min.js"></script>
<script src="../js/paginas/rastreamentos/monitorar_sv.js?v=1"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=&libraries=drawing&callback=initMap" async defer></script>


</html>