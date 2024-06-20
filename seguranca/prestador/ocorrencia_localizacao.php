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
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/ocorrencia_local.css">
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <title>Ocorrência de Localização</title>
</head>
<?php
include('../layout.php');
include('../manager/auxiliares/busca_ocorrencia_localizacao.php');
?>

<body>

    <div class="container">
        <div class="children">

            <div class="ocrSemJustificativa">
                <form action="../manager/cadastros/processa_justificativa_loc.php" method="post" id="formJustificativa">
                    <h6>Ocorrências de Localização sem JUSTIFICATIVA</h6>
                    <table class="table table-striped" id="tableInfos">
                        <thead class="thead-dark">

                            <tr>
                                <th scope="col" id="tableHead">ID</th>
                                <th scope="col" id="tableHead">Placa</th>
                                <th scope="col" id="tableHead">Data Ocorrência</th>
                                <th scope="col" id="tableHead">Ocorrência</th>
                                <th scope="col" id="tableHead"><button type="button" class="btn btn-primary" data-bs-toggle="modal" id="btnJustificar" data-bs-target="#justificarModal" disabled>Justificar</button></th>
                            </tr>
                        </thead>
                        <tbody id="conteudo">

                            <?php
                            if ($row !== 0) {
                                $index = 0;
                                foreach ($ocrLoc as $ocr) {
                                    $index++;
                                    echo '
                                    <tr>
                                        <td>' . $ocr['ID_ALERTA'] . '</td>
                                        <td>' . $ocr['PLACA'] . '</td>
                                        <td>' . $ocr['DT_OCORRENCIA'] . '</td>
                                        <td>' . $ocr['ALERTA_TIPO'] . '</td>
                                        <td><div class="form-check form-switch checkBoxs"><input class="form-check-input inputCheck" type="checkbox" role="switch" id="justCheckbox_' . $index . '" value="' . $ocr['ID_ALERTA'] . '" name="selecoes[]"></div> </td>
                                    </tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                    <div class="modal fade" id="justificarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: black;">Justificar Ocorrências</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea name="motivoJustificativa" id="justificativas" cols="58" rows="10" maxlength="5000" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary" id="confirmarJustificativa">Confirmar Justificativa</button>

                                </div>

                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <?php
            // <div class="ocrJustificadas">
            //     <h6>Ocorrência de Localização Justificadas</h6>
            //     <table class="table table-striped" id="tableJustificativa">
            //         <thead class="thead-dark">
            //             <tr>
            //                 <th scope="col" id="tableHead">ID</th>
            //                 <th scope="col" id="tableHead">Placa</th>
            //                 <th scope="col" id="tableHead">Data Ocorrência</th>
            //                 <th scope="col" id="tableHead">Ocorrência</th>
            //                 <th scope="col" id="tableHead">Justificativa</th>
            //                 <th scope="col" id="tableHead">Data Justificativa</th>
            //             </tr>
            //         </thead>
            //         <tbody id="conteudo">
            //             if ($rowRegistro !== 0) {
            //                 foreach ($registradas as $justificativas) {
            //                     echo '
            //                         <tr>
            //                             <td>' . $justificativas['ID_OCORRENCIA'] . '</td>
            //                             <td>' . $justificativas['PLACA'] . '</td>
            //                             <td>' . $justificativas['DT_OCORRENCIA'] . '</td>
            //                             <td>' . $justificativas['OCORRENCIA'] . '</td>
            //                             <td>' . $justificativas['JUSTIFICATICA'] . '</td>
            //                             <td>' . $justificativas['DT_HR_JUSTIFICATIVA'] . '</td>
            //                         </tr>';
            //                 }
            //             }
            //         </tbody>
            //     </table>
            // </div>
            ?>
        </div>
    </div>

</body>
<script src="../js/frameworks/jquery-3.6.4.js"></script>
<script src="../js/frameworks/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/frameworks/jquery.dataTables.min.js"></script>
<script src="../js/frameworks/dataTables.bootstrap5.min.js"></script>
<script src="../js/paginas/prestador/ocorrencia_localizacao.js"></script>


</html>