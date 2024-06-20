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
    <link rel="stylesheet" href="../css/forms_cadastro.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <title>Cadastro_rastreador</title>
</head>

<body>
    <?php
    include('../layout.php');
    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro Rastreador</h3>
                <form id="cadastro-user-form" action="#" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="nm_serie" placeholder="Número de Série" name="nmr_serie" required>
                        <label for="nm_serie">Número de Série</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="marca" placeholder="Marca do Rastreador" name="marca_rastreador" required>
                        <label for="marca">Marca do Rastreador</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="modelo" placeholder="Modelo do Rastreador" name="modelo_rastreador" required>
                        <label for="modelo">Modelo do Rastreador</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="date" class="form-control" id="data" placeholder="Data de Aquisição" name="data_aquisicao" required>
                        <label for="data">Data de Aquisição</label>
                    </div>
                    <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;">
                        <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">
                            TOPOLOGIA</h6>
                        <div class="form-floating" style="color: gray;">
                            <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check w-50" name="networkType" id="btnradio1_2G" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="btnradio1_2G">2G</label>

                                <input type="radio" class="btn-check w-50" name="networkType" id="btnradio2_5G" autocomplete="off">
                                <label class="btn btn-outline-primary" for="btnradio2_5G">5G</label>
                            </div>
                        </div>
                    </section>
                    <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;">
                        <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">
                            INSTALAÇÃO</h6>
                        <div class="form-floating" style="color: gray;">
                            <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check w-50" name="connectionType" id="btnradio1_Fixo" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="btnradio1_Fixo">Fixo</label>

                                <input type="radio" class="btn-check w-50" name="connectionType" id="btnradio2_Movel" autocomplete="off">
                                <label class="btn btn-outline-primary" for="btnradio2_Movel">Móvel</label>
                            </div>
                        </div>
                    </section>
                    <button class="btn btn-lg btn-outline-primary mt-3 w-100 " type="submit" id="registerBt">Cadastrar</button>
                </form><!-- FIM login-form-->
            </div><!-- FIM itensContent-->
        </div><!-- FIM content-->
    </div><!-- FIM container-->
    <script src="../js/frameworks/jquery-3.6.4.js"></script>
    <script src="../js/frameworks/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>