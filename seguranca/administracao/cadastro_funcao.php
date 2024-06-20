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
    <title>Cadastro Função</title>
</head>

<body>
    <?php
    include('../layout.php');
    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro de Função</h3>
                <form id="cadastro_funcao" action="../manager/cadastros/cadastro_funcao.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="funcao_descr" placeholder="Descrição da Função" name="descr_funcao" maxlength="100" required>
                        <label for="funcao_descr">Descrição da Função</label>
                    </div>
                    <button class="btn btn-lg btn-outline-primary mt-3 w-100 " type="submit" id="cadastroBt">Cadastrar</button>
                </form><!-- FIM login-form-->
            </div><!-- FIM itensContent-->
        </div><!-- FIM content-->
    </div><!-- FIM container-->
    <script src="../js/frameworks/jquery-3.6.4.js"></script>
    <script src="../js/frameworks/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/paginas/administracao/cadastro_funcao.js"></script>
</body>


</html>