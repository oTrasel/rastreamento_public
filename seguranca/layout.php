    <?php
    header("Cache-Control: no-cache, must-revalidate");
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand"><?php echo $_SESSION['nome_user']; ?></a>

            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    if ($_SESSION['id_tipo_user'] == 5) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" aria-current="page" href="/rastreamentos/seguranca/home.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Administração
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownAdmin">
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_funcao.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_funcao.php">Cadastro Função</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_prestador.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_prestador.php">Cadastro Prestador</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_tipo_ponto.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_tipo_ponto.php">Cadastro Tipo Pontos</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_endereco_especial.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_endereco_especial.php">Cadastro Endereço Especial</a></li>
                                <!-- <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_rastreador.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_rastreador.php">Cadastro Rastreador</a></li> -->
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_cartao_programa.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_cartao_programa.php">Cadastro Cartão Programa</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_local_servico.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_local_servico.php">Cadastro Locais de serviço</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_veiculo.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_veiculo.php">Cadastro Veiculos</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_vinculo_gps_veiculo.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/administracao/cadastro_vinculo_gps_veiculo.php">Vinculo GPS/VEICULO</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownRondas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Rondas
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownRondas">
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_servico.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/servicos/cadastro_servico.php">Iniciar/Finalizar Rondas</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownRastreamento" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Rastreamento
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownRastreamento">
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'rastrear_servico.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/rastreamentos/rastrear_servico.php">Rastrear Ronda</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'monitorar_servico.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/rastreamentos/monitorar_servico.php">Monitorar Rastreadores</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownRelatorios" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Relatórios
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownRelatorios">
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'relatorio_ronda.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/relatorios/relatorio_ronda.php">Relatório de Ronda</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'relatorio_velocidade.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/relatorios/relatorio_velocidade.php">Relatório de Velocidades</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'relatorio_telegram.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/relatorios/relatorio_telegram.php">Relatório de Telegram</a></li>
                            </ul>
                        </li>
                    <?php
                    } elseif ($_SESSION['id_tipo_user'] == 6) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" aria-current="page" href="/rastreamentos/seguranca/prestador/home.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownRelatorios" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Ocorrências
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownRelatorios">
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ocorrencia_velocidade.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/prestador/ocorrencia_velocidade.php">Ocorrência de velocidade</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ocorrencia_localizacao.php') ? 'active' : ''; ?>" href="/rastreamentos/seguranca/prestador/ocorrencia_localizacao.php">Ocorrência de localização</a></li>
                            </ul>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>






            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="/rastreamentos/seguranca/manager/logout.php" class="nav-link" style="text-decoration: none;">
                        <button class="btn btn-outline-primary">Logout</button>
                    </a>
                </li>
            </ul>
        </div>
    </nav>