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
    <link rel="stylesheet" href="../css/rastrear_sv.css">
    <link rel="shortcut icon" href="../images/favicon.ico"/>

    <title>Cadastro Rastreamentos</title>
</head>

<body>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/verifica_sv_ativos.php');

    ?>

    <div id="container">
        <div id="childrenMap">
            <?php
            if ($row_sv === -1) {
                echo    '<div id="infosRastreios" class="mt-2">';
                echo            '<div id="centro">
                                <div id="infosGpsVinculos">';
                echo                    '<div class="row mt-2" style="color: white; width: 98%;" id="tituloInfosGps">
                                        <div class="col" id="colunas" style="border: 1px solid black;">Vizualizar</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">N° Ronda</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">Veiculo</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">Tipo</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">Cartão</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">Prestador</div>
                                        <div class="col" id="colunas" style="border: 1px solid black;">Data Inicio</div>
                                    </div>';

                $corAtual = 'grey';
                foreach ($servicosAtivos as $row) {
                    $corAtual = ($corAtual == 'grey') ? '#363636' : 'grey';
                    $idServico = $row['id_servico'];

                    echo    '<div class="row mt-2" id="linhas"  style="color: white; width: 98%; background-color: ' . $corAtual . ';">
                            <div class="col" id="colunas" style="border: 1px solid black;"><button type="button" class="btn btn-outline-primary btn-vizualizar" id="' . $idServico . '" data-id="' . $idServico . '"><i class="bi bi-eye"></i></button></div>
                            <div class="col" id="colunas" style="border: 1px solid black;">' . $idServico . '</div>
                            <div class="col" id="colunas" style="border: 1px solid black;">' . $row['Veiculo'] . '</div>
                            <div class="col" id="colunas" style="border: 1px solid black;">' . $row['descr_tipo'] . '</div>
                            <div class="col" id="colunas" style="border: 1px solid black;">' . $row['Cartao'] . '</div>
                            <div class="col" id="colunas" style="border: 1px solid black;">' . $row['nome_user'] . '</div>
                            <div class="col" id="colunas" style="border: 1px solid black;">' . $row['data'] . '</div>
                        </div>';
                }

                echo            '</div> <!-- FIM infosGpsVinculos-->
                        </div><!-- FIM Centro-->
                    </div>';

                echo    '<div class="mt-2" id="mapsVinculos"></div>';
            } else {
                echo '<script> alert("Nenhuma Ronda Ativa."); window.location.href="../servicos/cadastro_servico.php";</script>';
                exit;
            }
            ?>
        </div>
    </div>

</body>
<script src="../js/frameworks/jquery-3.6.4.js"></script>
<script src="../js/frameworks/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/paginas/rastreamentos/rastrear_sv.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=&libraries=drawing&callback=initMap" async defer></script>

</html>