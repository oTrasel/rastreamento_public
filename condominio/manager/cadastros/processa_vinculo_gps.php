<?php
include('../conexao.php');
session_start();
$sucesso = 'successVinculo';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["selectGPS"]) && $_POST['selectLote'] != 'semLote' && $_POST['cadastroPlaca'] != '') {
        if (isset($_SESSION['id_user'])) {
            $id_user = $_SESSION['id_user'];
        } else {
            $id_user = null;
        }
        list($id_gps, $descr_gps) = explode("|", $_POST["selectGPS"]);
        $lotes = implode('|', $_POST['selectLote']);
        $lotes = $lotes . '|';
        $id_status = 2;
        try {
            //não mexer KRL
            $selectPlaca = $pdo->prepare("EXEC PRC_VerificaPlacasCadastradas @placa = :placa ");
            $selectPlaca->bindParam(':placa', $_POST['cadastroPlaca']);
            $selectPlaca->execute();

            $row = $selectPlaca->rowCount();
            if ($row == 1) {
                $selectPlaca = $pdo->prepare("EXEC PRC_VerificaPlacasCadastradas @placa = :placa ");
                $selectPlaca->bindParam(':placa', $_POST['cadastroPlaca']);
                $selectPlaca->execute();
                $resultPlaca = $selectPlaca->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $resultPlaca = $selectPlaca->fetchAll(PDO::FETCH_ASSOC);
            }

            //não mexer KRL
            if ($resultPlaca[0]['EM_USO'] === '0') {
                try {
                    $id_placa = $resultPlaca[0]['ID_PLACA'];
                    $stmt = $pdo->prepare("EXEC Cadastro_Vinculo_Placa_Gps @id_gps = :id_gps, @id_placa = :id_placa, @id_status = :id_status, @id_user = :id_user, @id_empresa = :id_empresa, @dados = :lotes, @condutor = :condutor, @contato_condutor = :cttCondutor");
                    $stmt->bindParam(':id_gps', $id_gps);
                    $stmt->bindParam(':id_placa', $id_placa);
                    $stmt->bindParam(':id_status', $id_status);
                    $stmt->bindParam(':id_user', $id_user);
                    $stmt->bindParam(':id_empresa', $_SESSION['id_empresa']);
                    $stmt->bindParam(':lotes', $lotes);
                    $stmt->bindParam(':condutor', $_POST['condutorNome']);
                    $stmt->bindParam(':cttCondutor', $_POST['condutorContato']);
                    $stmt->execute();
                    $row_count = $stmt->rowCount();
                    if ($row_count === 1) {
                        $stmt = $pdo->prepare("EXEC VerificaVinculosPlacasGps");
                        $stmt->execute();
                        // Resultados LOTES
                        $lotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        // Vai para o próximo SELECT
                        $stmt->nextRowset();
                        // Resultados GPS
                        $gps = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $gps_count = $stmt->rowCount();
                        // Vai para o próximo SELECT
                        $stmt->nextRowset();
                        // Resultados Placas
                        $placas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $placa_count = $stmt->rowCount();
                        if ($gps_count >= 1 && $placa_count >= 1) {
                            unset($_SESSION['lotes']);
                            unset($_SESSION['gps']);
                            unset($_SESSION['placa']);
                            $_SESSION['lotes'] = $lotes;
                            $_SESSION['gps'] = $gps;
                            $_SESSION['placa'] = $placas;
                            echo $sucesso;
                        } elseif ($gps_count == 0) {
                            unset($_SESSION['lotes']);
                            unset($_SESSION['gps']);
                            unset($_SESSION['placa']);
                            $_SESSION['lotes'] = $lotes;
                            $_SESSION['gps'] =  false;
                            $_SESSION['placa'] = $placas;
                            echo $sucesso;
                        } else {
                            unset($_SESSION['lotes']);
                            unset($_SESSION['gps']);
                            unset($_SESSION['placa']);
                            $_SESSION['lotes'] = $lotes;
                            $_SESSION['gps'] =  $gps;
                            $_SESSION['placa'] = false;
                            echo $sucesso;
                        }
                    } else {
                        echo "errorVinculo";
                    }
                } catch (PDOException $e) {
                    echo $e;
                }
            }else{
                echo 'emUso';
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }
} else {
    echo "errorVinculo3";
}
