<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
include('../manager/conexao.php');
if ($_SESSION['seguranca'] == false || $_SESSION['id_tipo_user'] !== '6') {

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
    <link rel="shortcut icon" href="../images/favicon.ico"/>
    <title>Painel do Prestador</title>
</head>
<?php
include('../layout.php');
?>

<body>
   

</body>
<script src="../js/frameworks/jquery-3.6.4.js"></script>
<script src="../js/frameworks/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>


</html>