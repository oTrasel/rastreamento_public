<?php
if(isset($_SESSION['id_user'])){
    session_destroy();
}else{
    session_start();
}
ini_set('default_charset','UTF-8');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="src/normalize.css">
    <link rel="stylesheet" href="src/index.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/frameworks/jquery.min.js"></script>
    <script src="js/frameworks/html5shiv_3.7.3.min.js"></script>
    <link rel="shortcut icon" href="images/favicon.ico" />
    <script src="js/paginas/index.js"></script>
    <title>Mapeamento de Areas</title>
</head>
<body>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <i class="bi bi-person-circle" style="font-size: 60px; color: lightgrey;"></i>
                <form id="login-form" action="manager/login.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="usuario" placeholder="CPF/CNPJ" name="user" required maxlength="14" minlength="11">
                        <label for="usuario">CPF/CNPJ</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="password" class="form-control" id="senha" placeholder="Senha" name="password" required>
                        <label for="senha">Senha</label>
                    </div>
                    <button class="btn btn-lg btn-outline-primary mt-3 w-100" type="submit" id="login-button">Login</button>
                </form><!-- FIM login-form-->
            </div><!-- FIM itensContent-->
        </div><!-- FIM content-->
    </div><!-- FIM container-->
</body>
</html>
