<?php
if (isset($_SESSION['id_user'])) {
    session_destroy();
} else {
    session_start();
}
ini_set('default_charset', 'UTF-8');
header("Cache-Control: no-cache, must-revalidate");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="shortcut icon" href="images/favicon.ico" />
    <title>Painel Segurança</title>
</head>

<body>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <i class="bi bi-person-circle" style="font-size: 60px; color: lightgrey;"></i>
                <h4 id="homeTitle">Painel Segurança</h4>
                <form id="login-form" action="manager/login.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="usuario" placeholder="Usuário" name="user" required maxlength="14">
                        <label for="usuario">Usuário</label>
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
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/frameworks/jquery.min.js"></script>
<script src="js/frameworks/html5shiv_3.7.3.min.js"></script>
<script src="js/paginas/index.js"></script>
</html>