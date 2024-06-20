<?php
session_start();
include('manager/conexao.php');
if ($_SESSION['empresa'] == false) {

  //redireciona para a index.
  header('Location: index.php');
  session_destroy();
  exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="src/home.css">
  <link rel="shortcut icon" href="images/favicon.ico" />

  <title>Empresa</title>
</head>

<body>
  <?php
  include('layout.php');
  ?>

  <script src="js/frameworks/jquery-3.6.4.js"></script>
  <script src="js/frameworks/popper.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>