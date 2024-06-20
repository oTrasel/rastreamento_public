<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    try {
        $dataHoraAtual = new DateTime();
        $dataHoraFormatada = $dataHoraAtual->format('Y-m-d H:i:s');
        $raio_ponto = $_POST['raio_ponto'];

        $selectLocal = explode("|", $_POST['servicoLocal']);
        $idLocalSv = $selectLocal[0];

        $selectPonto = explode("|", $_POST['pontoTipo']);
        $idTipoPonto = $selectPonto[0];

        if ($raio_ponto < 10) {
            $tipo = 2;
            $raio = 0;
        } else {
            $tipo = 1;
            $raio = $raio_ponto;
        }
        
        
        $insert_end_espc = $pdo->prepare("insert into endereco_especial (descr_endereco, nome_endereco, dt_cadastro_endereco, user_cadastro_endereco, id_tipo_marcacao, raio_ponto_mtrs, id_empresa, id_local_trabalho, id_tipo_ponto, habilitado)
                                values (:descr_endereco, :nome_endereco, CONVERT(datetime, :dt_cadastro, 120), :id_seguranca, :id_tipo_marcacao, :raio_ponto_mtrs, :id_empresa, :id_local_trabalho, :id_tipo_ponto, 1)");
        $insert_end_espc->bindParam(':id_seguranca', $_SESSION['id_user']);
        $insert_end_espc->bindParam(':nome_endereco', $_POST['nome_endereco']);
        $insert_end_espc->bindParam(':descr_endereco', $_POST['endereco_local']);
        $insert_end_espc->bindParam(':dt_cadastro', $dataHoraFormatada);
        $insert_end_espc->bindParam(':id_tipo_marcacao', $tipo);
        $insert_end_espc->bindParam(':raio_ponto_mtrs', $raio);
        $insert_end_espc->bindParam(':id_empresa', $_SESSION['id_empresa']);
        $insert_end_espc->bindParam(':id_tipo_ponto', $idTipoPonto);
        $insert_end_espc->bindParam(':id_local_trabalho', $idLocalSv);
        $insert_end_espc->execute();
        $row_count = $insert_end_espc->rowCount();
        if($row_count === 1){
            $select_end_espc = $pdo->prepare("select id_endereco, descr_endereco, nome_endereco, dt_cadastro_endereco, user_cadastro_endereco from endereco_especial 
                                   where descr_endereco = :descr_endereco 
                                   and nome_endereco = :nome_endereco 
                                   and dt_cadastro_endereco = CONVERT(datetime, :dt_cadastro, 120) 
                                   and user_cadastro_endereco = :id_seguranca
                                   and id_tipo_marcacao = :id_tipo_marcacao
                                   and raio_ponto_mtrs = :raio_ponto_mtrs
                                   and id_empresa = :id_empresa");
            $select_end_espc->bindParam(':id_seguranca', $_SESSION['id_user']);
            $select_end_espc->bindParam(':nome_endereco', $_POST['nome_endereco']);
            $select_end_espc->bindParam(':descr_endereco', $_POST['endereco_local']);
            $select_end_espc->bindParam(':dt_cadastro', $dataHoraFormatada);
            $select_end_espc->bindParam(':id_tipo_marcacao', $tipo);
            $select_end_espc->bindParam(':raio_ponto_mtrs', $raio);
            $select_end_espc->bindParam(':id_empresa', $_SESSION['id_empresa']);
            $select_end_espc->execute();
            $result_select = $select_end_espc->fetch(PDO::FETCH_ASSOC);
            $row_select = $select_end_espc->rowCount();
            if($row_select === -1){
                $coordenadasArray = json_decode($_POST["json_coordenadas"], true);
                $ordem = 1;

                foreach ($coordenadasArray as $coordinate) {
                    if (isset($coordinate['lat'], $coordinate['lng'])) {
                        $latitude = $coordinate['lat'];
                        $longitude = $coordinate['lng'];
                       
                        // Prepara a instrução SQL
                        $insert_coord = $pdo->prepare("insert into poligono_lat_long_endereco_especial (id_endereco, latitude, longitude, ordem, id_tipo_marcacao, raio_ponto_mtrs)
                                               values (:id_endereco, :latitude, :longitude, :ordem, :id_tipo_marcacao, :raio_ponto_mtrs)");
    
                        // Substitui os parâmetros na instrução SQL
                        $insert_coord->bindParam(':id_endereco', $result_select['id_endereco']);
                        $insert_coord->bindParam(':latitude', $latitude);
                        $insert_coord->bindParam(':longitude', $longitude);
                        $insert_coord->bindParam(':ordem', $ordem);
                        $insert_coord->bindParam(':id_tipo_marcacao', $tipo);
                        $insert_coord->bindParam(':raio_ponto_mtrs', $raio);
                        // Executa a instrução SQL
                        $insert_coord->execute();
                        $ordem++;
                        
                    } else {
                        echo "Latitude ou longitude não definidas nas coordenadas.";
                    }
                }

            }

        }
        echo 'sucesso';
    } catch (PDOException $error) {
        echo $error;
    }

}else{
    echo 'error';
}
?>