<?php
session_start();
include('conexao.php');

if (isset($_SESSION['empresa']) || isset($_SESSION['user'])) {
    session_destroy();
    header('Location: ../index.php');
    exit();  // Certifique-se de encerrar o script após redirecionar
}
?>
