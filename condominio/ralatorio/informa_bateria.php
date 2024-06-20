<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
include('../manager/conexao.php');
if ($_SESSION['empresa'] == false) {

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
    <link rel="stylesheet" href="../src/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../src/home.css">
    <link rel="stylesheet" href="../src/informativo_bateria.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />

    <title>Informativo Baterias</title>
</head>

<body>
    <?php
    include('../layout.php');
    ?>


    <div id="container">
        <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;" id="sectionMonitoramento">
            <div id="tableMonitoramento">
                <table class="table table-striped" id="tableInfos">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" id="tableHead">Rastreador</th>
                            <th scope="col" id="tableHead">Voltagem</th>
                            <th scope="col" id="tableHead">Bateria</th>
                            <th scope="col" id="tableHead">Ultima Posição</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo">
                    </tbody>
                </table>
            </div>
            <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">Informativos de Baterias</h6>

        </section>
    </div>
</body>
<script src="../js/frameworks/jquery-3.6.4.js"></script>
<script src="../js/frameworks/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/frameworks/jquery.dataTables.min.js"></script>
<script src="../js/frameworks/dataTables.bootstrap5.min.js"></script>
<script src="../js/paginas/relatorio/relatorio_bateria.js"></script>

</html>