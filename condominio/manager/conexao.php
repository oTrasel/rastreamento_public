<?php

$ip = '192.168.0.182';
$user = 'sa';
$nome = 'c2eGEO';
$senha = 'aaaaaaaaa';


// Cria a string de conexão com o SQL Server
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
$dsn = "sqlsrv:Server=$ip;Database=$nome";
    // Cria a conexão
try{
    $pdo = new PDO($dsn, $user, $senha);
}catch(PDOException $e){
    // echo $e;
    echo ("<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Erro ao conectar com o Bando de Dados, verifique situação com o Administrador do Sistema!')
        window.location.href='index.php';
        </SCRIPT>");
}


?>
