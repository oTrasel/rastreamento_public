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
    <link rel="stylesheet" href="../css/cadastro_veiculo.css">
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <title>Cadastro Veiculos</title>
</head>

<body>

    <?php
    include('../layout.php');
    include('../manager/auxiliares/verifica_local_servico.php');
    include('../manager/auxiliares/verifica_especie_veiculo.php');

    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro de Veículos</h3>
                <form id="cadastro_veiculo" action="../manager/cadastros/cadastro_veiculo.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="marca" placeholder="Marca do Veículo" name="marca_veiculo" required>
                        <label for="marca">Marca do Veículo</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="modelo" placeholder="Modelo do Veículo" name="modelo_veiculo" required>
                        <label for="modelo">Modelo do Veículo</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="cor" placeholder="Cor do Veículo" name="cor_veiculo" required>
                        <label for="cor">Cor do Veículo</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="placaInput" placeholder="Placa do Veículo" name="placa_veiculo" required oninput="validarPlaca(); this.value = this.value.toUpperCase();" maxlength="7" value="">
                        <label for="placaInput">Placa do Veículo</label>
                        <p id="mensagem" class="mt-2"></p>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <select class="form-select mt-2" name="selectEspecie" id="especie" required>
                            <?php
                            foreach ($especies as $especie) {
                                echo '<option id="' . $especie['descr_especie'] . '" value="' . $especie['id_especie'] . '">' . $especie['descr_especie'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <select class="form-select mt-2" name="selectLocal" id="selectLocal" required>
                            <?php
                            foreach ($locais_trabalho as $locais) {
                                echo '<option id="' . $locais['nome_local'] . '" value="' . $locais['id_local'] . '">' . $locais['nome_local'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button class="btn btn-lg btn-outline-primary mt-4 w-100 " type="submit" id="registerBt">Cadastrar</button>
                </form><!-- FIM login-form-->
            </div><!-- FIM itensContent-->
        </div><!-- FIM content-->
    </div><!-- FIM container-->
    <script src="../js/frameworks/jquery-3.6.4.js"></script>
    <script src="../js/frameworks/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/frameworks/select2.min.js"></script>
    <script src="../js/paginas/administracao/cadastro_veiculo.js"></script>
    <script>
        $(document).ready(function() {

            $('#especie').select2({
                placeholder: 'Selecione a especie',
                allowClear: true
            });
            $('#especie').val(null).trigger('change');

            $('#especie').next('.select2-container').find('.select2-selection--single').css('height', '40px');


            $('#selectLocal').select2({
                placeholder: 'Selecione o Local',
                allowClear: true
            });
            $('#selectLocal').val(null).trigger('change');

            $('#selectLocal').next('.select2-container').find('.select2-selection--single').css('height', '40px');
        });
    </script>
</body>


</html>