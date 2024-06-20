<?php
include('conexao.php');
session_start();

$sucesso = 'sucessoVinculo';
if(isset($_POST['idDesvinculo'])){
    
    try{
        $stmt = $pdo->prepare ("EXEC DesvincularPlacaGPS @id_vinculo = :id_vinculo");
        $stmt->bindParam(':id_vinculo', $_POST['idDesvinculo']);
        $stmt->execute();
        $row_count = $stmt->rowCount();
        if($row_count === 1){
            try{
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
    
                // Verifica se tem placas cadastradas e se tem GPS disponível
                if ($gps_count >= 1 && $placa_count >= 1) {
    
                    unset($_SESSION['lotes']);
                    unset($_SESSION['gps']);
                    unset($_SESSION['placa']);
            
                    $_SESSION['lotes'] = $lotes;
                    $_SESSION['gps'] = $gps;
                    $_SESSION['placa'] = $placas;
                    echo $sucesso;
            
            }elseif ($gps_count == 0) {
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
    
                
            }catch(PDOException $e){
                
                echo 'erroDesvinculo';
            }
        }else{
            echo 'erroDesvinculo';
        }

    }catch(PDOException $e){
        echo 'erroDesvinculo';
    }

}else{
    echo 'erroDesvinculo'; 
}

?>