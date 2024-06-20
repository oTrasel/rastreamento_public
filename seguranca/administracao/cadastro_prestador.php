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
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/forms_cadastro.css">
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="stylesheet" href="../css/cadastro_prestador.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <title>Cadastro Prestador</title>
</head>

<body>
    <?php
    include('../layout.php');
    include('../manager/auxiliares/verifica_funcao.php');
    include('../manager/auxiliares/verifica_local_servico.php');
    include('../manager/auxiliares/verifica_users_cadastrados.php');
    ?>
    <div id="container">
        <div id="content">
            <div id="itensContent">
                <h3 style="color: white">Cadastro de Prestador</h3>
                <form id="cadastro_prestador" action="../manager/cadastros/cadastro_prestador.php" method="post" role="form" style="display: block;">
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="nome" placeholder="Nome" name="nome_prestador" required>
                        <label for="nome">Nome</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="text" class="form-control" id="nome_usuario" placeholder="Login" name="login_prestador" required>
                        <label for="nome_usuario">Login</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="number" class="form-control" id="cpfInput" placeholder="CPF" name="prestador_cpf" required minlength="11" maxlength="11" oninput="VerificaCPF(this.value)">
                        <label for="cpfInput">CPF</label>
                        <p id="cpfMessage"></p>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input type="tel" class="form-control" id="telefone_prestador" name="prestador_telefone" placeholder="Telefone" maxlength="15" pattern="\(\d{2}\)\s*\d{5}-\d{4}" required>
                        <label for="telefone_prestador">Telefone</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <select class="form-select mt-2" name="selectFuncao" id="funcaoSelect" required>
                            <?php
                            foreach ($funcao as $funcao_prest) {
                                echo '<option id="' . $funcao_prest['descr_funcao'] . '" value="' . $funcao_prest['id_funcao'] . '">' . $funcao_prest['descr_funcao'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <select class="form-select mt-2" name="selectLocalSv" id="localSelect" required>
                            <?php
                            foreach ($locais_trabalho as $locais) {
                                echo '<option id="' . $locais['nome_local'] . '" value="' . $locais['id_local'] . "|" . $locais['nome_local'] . '">' . $locais['nome_local'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input onkeyup="validaSenha()" type="password" class="form-control" name="passwd" id="password" placeholder="Senha" required>
                        <label for="password">Senha</label>
                    </div>
                    <div class="form-floating mt-3" style="color: gray;">
                        <input onkeyup="validaSenha()" type="password" class="form-control" id="c_password" placeholder="Confirmar Senha" required>
                        <label for="c_password">Confirmar Senha</label>
                        <p id="confirmPass" style="color: white;"></p>
                    </div>
                    <button class="btn btn-lg btn-outline-primary mt-1 w-100 " type="submit" id="registerBt" disabled>Cadastrar</button>
                </form><!-- FIM login-form-->
            </div><!-- FIM itensContent-->
            <hr style="border: solid 1px black;">
            <button type="button" class="btn btn-outline-secondary mt-1 w-100" data-bs-toggle="modal" data-bs-target="#userCadastrados">
                Verificar Usuários Cadastrados
            </button>
        </div><!-- FIM content-->
    </div><!-- FIM container-->

    <!-- MODAL DE USERS JA CADASTRADOS -->
    <div class="modal fade" id="userCadastrados" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Usuários Cadastrados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="gridInfos">
                        <table class="table table-striped" id="tableInfos">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" id="tableHead">Nome</th>
                                    <th scope="col" id="tableHead">Login</th>
                                    <th scope="col" id="tableHead">CPF</th>
                                    <th scope="col" id="tableHead">Telefone</th>
                                    <th scope="col" id="tableHead">Função</th>
                                    <th scope="col" id="tableHead">Local</th>
                                    <th scope="col" id="tableHead">Ativo</th>
                                    <th scope="col" id="tableHead">Data Cadastro</th>
                                    <th scope="col" id="tableHead">Editar</th>
                                </tr>
                            </thead>
                            <tbody id="conteudo">
                                <?php
                                if ($row !== 0) {
                                    foreach ($users as $user) {
                                        echo '
                                    <tr>
                                        <td id="row" > ' . $user['nome_user'] . '</td>
                                        <td id="row" > ' . $user['login_user'] . '</td>
                                        <td id="row" > ' . $user['cpf_user'] . '</td>
                                        <td id="row" > ' . $user['telefone_user'] . '</td>
                                        <td id="row" > ' . $user['descr_funcao'] . '</td>
                                        <td id="row" > ' . $user['nome_local'] . '</td>
                                        <td id="row" > ' . $user['habilitado'] . '</td>
                                        <td id="row" > ' . $user['dt_cadastro'] . '</td>
                                        <td id="row" > <but$userpe="button" class="btn btn-secondary w-100 h-100" data-bs-toggle="modal" data-bs-target="#editarModal" data-id="' . $user['id_user'] . '"> <i class="bi bi-pencil-square"></i></button></td>
                                    </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL EDITAR LOCAIS -->
    <form action="../manager/cadastros/processa_alteracao_usuario.php" method="post" id="formAtualizaInfo">
        <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-theme="dark" style="color: white;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Usuário</h1>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_usuario" value="" name="id_user">
                        <table class="table table-striped" id="tableEditar">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" id="tableHead">Nome</th>
                                    <th scope="col" id="tableHead">Login</th>
                                    <th scope="col" id="tableHead">CPF</th>
                                    <th scope="col" id="tableHead">Telefone</th>
                                    <th scope="col" id="tableHead">Função</th>
                                    <th scope="col" id="tableHead">Local</th>
                                    <th scope="col" id="tableHead">Ativo</th>
                                    <th scope="col" id="tableHead">Data Cadastro</th>
                                </tr>
                            </thead>
                            <tbody id="conteudo">
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <input type="text" class="form-control" id="editavelNome" placeholder="Nome" name="nomeEditavel" required>
                                    <label for="editavelNome">Nome</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <input type="text" class="form-control" id="editavelLogin" placeholder="Login" name="loginEditavel" required>
                                    <label for="editavelLogin">Login</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <input type="text" class="form-control" id="editavelCpf" placeholder="CPF" name="cpfEditavel" required minlength="11" maxlength="11" oninput="cpfEdita(this.value)">
                                    <label for="editavelCpf">CPF</label>
                                    <p id="cpfEditavelMsg"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <input type="tel" class="form-control" id="editavelTelefone" name="telefoneEditavel" placeholder="Telefone" maxlength="15" pattern="\(\d{2}\)\s*\d{5}-\d{4}" required>
                                    <label for="editavelTelefone">Telefone</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <select class="form-select mt-2" name="funcaoEditavel" id="editavelFuncao" required>
                                        <?php
                                        foreach ($funcao as $funcao_prest) {
                                            echo '<option id="' . $funcao_prest['descr_funcao'] . '" value="' . $funcao_prest['id_funcao'] . '">' . $funcao_prest['descr_funcao'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <select class="form-select mt-2" name="localEditavel" id="editavelLocal" required>
                                        <?php
                                        foreach ($locais_trabalho as $locais) {
                                            echo '<option id="' . $locais['nome_local'] . '" value="' . $locais['id_local'] . "|" . $locais['nome_local'] . '">' . $locais['nome_local'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating mt-3" style="color: gray;">
                                    <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check w-50" name="habilitado" id="btnHabilitado" autocomplete="off" value="1">
                                        <label class="btn btn-outline-primary" for="btnHabilitado">Habilitado</label>

                                        <input type="radio" class="btn-check w-50" name="habilitado" id="btnDesabilitado" autocomplete="off" value="0">
                                        <label class="btn btn-outline-primary" for="btnDesabilitado">Desabilitado</label>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#userCadastrados">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="confirmarEdicao">Confirmar Edição</button>
                    </div>
                </div>
            </div>
        </div>
    </form>




    <script src="../js/frameworks/jquery-3.6.4.js"></script>
    <script src="../js/frameworks/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/frameworks/jquery.dataTables.min.js"></script>
    <script src="../js/frameworks/dataTables.bootstrap5.min.js"></script>
    <script src="../js/paginas/administracao/cadastro_prestador.js"></script>
    <script src="../js/frameworks/select2.min.js"></script>
    <script>
        //SCRIPT NO QUAL MASCARÁ O CAMPO DE TELEFONE
        const tel = document.getElementById('telefone_prestador') // Seletor do campo de telefone

        tel.addEventListener('keypress', (e) => mascaraTelefone(e.target.value)) // Dispara quando digitado no campo
        tel.addEventListener('change', (e) => mascaraTelefone(e.target.value)) // Dispara quando autocompletado o campo

        const mascaraTelefone = (valor) => {
            valor = valor.replace(/\D/g, "")
            valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2")
            valor = valor.replace(/(\d)(\d{4})$/, "$1-$2")
            tel.value = valor // Insere o(s) valor(es) no campo
        }

        const telEditavel = document.getElementById('editavelTelefone'); // Seletor do campo de telefone

        telEditavel.addEventListener('keypress', (e) => mascaraTelefoneEditavel(e.target.value)); // Dispara quando digitado no campo
        telEditavel.addEventListener('change', (e) => mascaraTelefoneEditavel(e.target.value)); // Dispara quando autocompletado o campo

        const mascaraTelefoneEditavel = (valor) => {
            valor = valor.replace(/\D/g, "");
            valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
            valor = valor.replace(/(\d)(\d{4})$/, "$1-$2");
            telEditavel.value = valor; // Insere o(s) valor(es) no campo
        };


        $(document).ready(function() {

            $('#funcaoSelect').select2({
                placeholder: 'Selecione uma Função', // Substitua pelo texto desejado
                allowClear: true // Adiciona um "x" para limpar a seleção
            });
            $('#funcaoSelect').val(null).trigger('change');
            // Definir a altura diretamente no contêiner do Select2
            $('#funcaoSelect').next('.select2-container').find('.select2-selection--single').css('height', '40px');
        });

        $(document).ready(function() {

            $('#localSelect').select2({
                placeholder: 'Selecione um Local de Serviço', // Substitua pelo texto desejado
                allowClear: true // Adiciona um "x" para limpar a seleção
            });
            $('#localSelect').val(null).trigger('change');
            // Definir a altura diretamente no contêiner do Select2
            $('#localSelect').next('.select2-container').find('.select2-selection--single').css('height', '40px');
        });





        $(document).ready(function() {
            // Abrir o modal e preencher os campos
            $('#editarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id = button.data('id'); // Extrair o ID do botão
                document.getElementById('id_usuario').value = id;
                // Filtrar os dados correspondentes ao ID selecionado
                var user = <?php echo json_encode($users); ?>.find(function(users) {
                    return users.id_user == id;
                });

                // Preencher os campos do modal de edição com os dados do local
                // Preencher os campos do modal de edição com os dados do usuário
                $('#tableEditar tbody').html(`
                    <tr>
                        <td id="row">${user.nome_user}</td>
                        <td id="row">${user.login_user}</td>
                        <td id="row">${user.cpf_user}</td>
                        <td id="row">${user.telefone_user}</td>
                        <td id="row">${user.descr_funcao}</td>
                        <td id="row">${user.nome_local}</td>
                        <td id="row">${user.habilitado}</td>
                        <td id="row">${user.dt_cadastro}</td>
                    </tr>
                `);


                $('#editavelNome').val(user.nome_user);
                $('#editavelLogin').val(user.login_user);
                $('#editavelCpf').val(user.cpf_user);
                $('#editavelTelefone').val(user.telefone_user);

                $('#editavelFuncao option').each(function() {
                    if ($(this).text() === user.descr_funcao) {
                        $(this).prop('selected', true);
                    }
                });

                // Selecionar a opção correta para o campo de local
                $('#editavelLocal option').each(function() {
                    if ($(this).val() === user.id_local_trabalho + '|' + user.nome_local) {
                        $(this).prop('selected', true);
                        
                    }
                });



                if (user.habilitado === 'Sim') {
                    $('#btnHabilitado').prop('checked', true);
                } else {
                    $('#btnDesabilitado').prop('checked', true);
                }


            });

        });
    </script>
</body>


</html>