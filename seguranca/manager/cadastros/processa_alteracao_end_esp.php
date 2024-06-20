<?php
include('../conexao.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $coordenadasArray = json_decode($_POST["editadosPontos"]);



        $selectLocal = explode("|", $_POST['localEditavel']);
        $idLocalSv = $selectLocal[0];

        $selectPonto = explode("|", $_POST['pontoEditavel']);
        $idTipoPonto = $selectPonto[0];

        if (!isset($_POST['raioEditavel'])) {
            $raio = 0;
            $tipoMarcacao = 2;
        } else {
            $tipoMarcacao = 1;
            $raio = $_POST['raioEditavel'];
        }

        $atualiza = $pdo->prepare("EXEC editaEnderecoEspecial @id_user = :id_user, 
                                                              @id_endereco = :id_endereco, 
                                                              @id_empresa = :id_empresa, 
                                                              @id_tipo_ponto = :id_tipo_ponto, 
                                                              @id_local_trabalho = :id_local_trabalho, 
                                                              @habilitado = :habilitado,
                                                              @raio_ponto = :raio_ponto,
                                                              @descr_endereco = :descr_endereco,
                                                              @nome_endereco = :nome_endereco");
        $atualiza->bindParam(':id_user', $_SESSION['id_user']);
        $atualiza->bindParam(':id_endereco', $_POST['enderecoId']);
        $atualiza->bindParam(':id_empresa', $_SESSION['id_empresa']);
        $atualiza->bindParam(':id_tipo_ponto', $idTipoPonto);
        $atualiza->bindParam(':id_local_trabalho', $idLocalSv);
        $atualiza->bindParam(':habilitado', $_POST['habilitado']);
        $atualiza->bindParam(':raio_ponto', $raio);
        $atualiza->bindParam(':descr_endereco', $_POST['descrEditavel']);
        $atualiza->bindParam(':nome_endereco', $_POST['nomeEditavel']);
        $atualiza->execute();
        $row = $atualiza->rowCount();
        if ($row === 1) {
            $ordem = 1;
            foreach ($coordenadasArray as $coordinate) {

                $latitude = $coordinate->lat;
                $longitude = $coordinate->lng;

                // Prepara a instrução SQL
                $insert_coord = $pdo->prepare("insert into poligono_lat_long_endereco_especial (id_endereco, latitude, longitude, ordem, id_tipo_marcacao, raio_ponto_mtrs)
                    values (:id_endereco, :latitude, :longitude, :ordem, :id_tipo_marcacao, :raio_ponto_mtrs)");

                // Substitui os parâmetros na instrução SQL
                $insert_coord->bindParam(':id_endereco', $_POST['enderecoId']);
                $insert_coord->bindParam(':latitude', $latitude);
                $insert_coord->bindParam(':longitude', $longitude);
                $insert_coord->bindParam(':ordem', $ordem);
                $insert_coord->bindParam(':id_tipo_marcacao', $tipoMarcacao);
                $insert_coord->bindParam(':raio_ponto_mtrs', $raio);
                // Executa a instrução SQL
                $insert_coord->execute();
                $ordem++;
            }
        }
        echo 'sucesso';
    } catch (PDOException $e) {
        echo $e;
    }
} else {
    echo 'error';
}
