<?php
header("Cache-Control: no-cache, must-revalidate");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand"><?php echo $_SESSION['razao_social'];?></a>

        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" aria-current="page" href="/condominio/home.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownRastreamento" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administração
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownRastreamento">
                        <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'cadastro_vinculo_gps.php') ? 'active' : ''; ?>" href="/condominio/administracao/cadastro_vinculo_gps.php">Monitorar entradas</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownRastreamento" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Relatórios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownRastreamento">
                        <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'informa_bateria.php') ? 'active' : ''; ?>" href="/condominio/ralatorio/informa_bateria.php">Informativo Bateria</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        
                    </ul>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="/condominio/manager/logout.php" class="nav-link" style="text-decoration: none;">
                    <button class="btn btn-outline-primary">Logout</button>
                </a>
            </li>
        </ul>
    </div>
</nav>