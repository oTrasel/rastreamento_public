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
    <link rel="stylesheet" href="../css/relatorio.css">
    <link rel="shortcut icon" href="../images/favicon.ico"/>
    <title>Relatório de Rondas</title>
</head>

<body>
    <?php
    include('../layout.php');
    ?>
    <iframe src="https://app.powerbi.com/view?r=eyJrIjoiZWQ4YjA5YzUtZDQ2Yy00YWZhLTlhMWItZTg2YTNmOWQxZGNkIiwidCI6IjNiZTY1ZDA5LWZhZTAtNGI4NS04N2I3LWE4YjQ3YTNhMGMyMCJ9" width="100%" height="100%" frameborder="0"></iframe>
</body>
<script src="../js/frameworks/jquery-3.6.4.js"></script>
<script src="../js/frameworks/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>

</html>