<?php
include('../conexao.php');

$lat_long = $pdo->prepare('SELECT
ee.id_endereco, 
ee.descr_endereco, 
ee.user_cadastro_endereco, 
ee.raio_ponto_mtrs,
pll.latitude,
pll.longitude
FROM endereco_especial ee 
JOIN poligono_lat_long_endereco_especial pll ON pll.id_endereco = ee.id_endereco 
WHERE ee.id_endereco = :id_endereco
and ee.id_local_trabalho = :local_trabalho 
ORDER BY 1, 2 DESC ');

$lat_long->bindParam(':id_endereco', $_POST['id_endereco']);
$lat_long->bindParam(':local_trabalho', $_POST['local_trabalho']);
$lat_long->execute();
$lat_long_espc = $lat_long->fetchAll(PDO::FETCH_ASSOC);

// Configurar cabeçalho para JSON
header('Content-Type: application/json');

// Imprimir os dados em formato JSON
echo json_encode($lat_long_espc);
?>
