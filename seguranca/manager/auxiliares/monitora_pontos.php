<?php
include('../conexao.php');
session_start();

// Consulta para obter informações sobre a posição dos veículos
$buscaPosicao = $pdo->prepare("select rs.id_rastreador,
tv.ras_vei_placa,
CONVERT(VARCHAR, tv.DT_ULT_POSICAO, 103) + ' ' + CONVERT(VARCHAR, tv.DT_ULT_POSICAO, 108) AS DT_ULT_POSICAO,
tv.LATITUDE,
tv.LONGITUDE,
cast(tv.VELOCIDADE as int) as 'VELOCIDADE',
vs.modelo_veiculo,
CASE 
    when tv.AREA is  null or LTRIM(RTRIM(tv.AREA)) = '' then ' - ' 
    ELSE tv.AREA
END as area,
CASE 
    when tv.lote is null or LTRIM(RTRIM(tv.lote)) = '' then ' - ' 
    ELSE tv.lote
END as lote,
CASE 
    when tv.quadra is null or LTRIM(RTRIM(tv.quadra)) = '' then ' - ' 
    ELSE tv.quadra
END as quadra,
CASE 
    when tv.rua is null or LTRIM(RTRIM(tv.rua)) = '' then ' - ' 
    ELSE tv.rua
END as rua,
CASE 
    when tv.local is null  or LTRIM(RTRIM(tv.local)) = '' then ' - '
    ELSE tv.local
END as local,
CASE 
    WHEN vs.id_especie_veiculo = 3 THEN 'Motocicleta'
    WHEN vs.id_especie_veiculo = 2 THEN 'Carro'
    WHEN vs.id_especie_veiculo = 1 THEN 'Picape'
    WHEN vs.id_especie_veiculo = 4 THEN 'Ônibus'
    ELSE '-'
END AS 'tipo_veiculo',
CASE 
    when tv.ignicao = 0 then 'Desligada'
    when tv.ignicao = 1 then 'Ligada'
    when tv.ignicao is null then ' - '
END as ignicao,
cast(DATEDIFF(MI,tv.DT_ULT_POSICAO,GETDATE())as int) TEMP_PARADO_MIN,
CASE 
	when tv.PREFIXO_VEIC is null then tv.ras_vei_placa 
	ELSE tv.PREFIXO_VEIC 
END as 'prefixo',
CASE 
	when tv.PREFIXO_VEIC is null then tv.ras_vei_placa
	ELSE tv.ras_vei_placa +' | '+ tv.PREFIXO_VEIC
END as ras_vei_placa_prefixo
FROM rastreador_seguranca rs
JOIN FULLTRACK.DBO.TB_VEICULOS tv ON tv.ID_VEICULO_ECJ = rs.id_veiculo_ecj 
LEFT JOIN vinculo_rastreador_veiculo_seguranca vrvs ON vrvs.id_rastreador = rs.id_rastreador
LEFT JOIN veiculos_seguranca vs ON vs.id_veiculo = vrvs.id_veiculo_seguranca 
WHERE rs.id_empresa = :id_empresa
and rs.id_local_trabalho = :local_trabalho
AND tv.ATIVO = 1
order by tv.ras_vei_placa asc");
$buscaPosicao->bindParam(':id_empresa', $_SESSION['id_empresa']);
$buscaPosicao->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$buscaPosicao->execute();
$result_gps = $buscaPosicao->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter informações sobre os pontos
$buscaPontos = $pdo->prepare("SELECT ee.id_endereco,
ee.nome_endereco,
ee.id_tipo_marcacao,
ee.id_tipo_ponto,
ee.raio_ponto_mtrs,
pllee.latitude,
pllee.longitude,
pllee.ordem,
tp.cor
FROM endereco_especial ee
JOIN poligono_lat_long_endereco_especial pllee ON pllee.id_endereco = ee.id_endereco
join tipo_ponto tp 
on tp.id_tipo_ponto = ee.id_tipo_ponto 
WHERE ee.id_empresa = :id_empresa
and ee.id_local_trabalho = :local_trabalho 
and ee.id_tipo_ponto is not NULL 
ORDER BY ee.id_endereco, pllee.ordem ASC");
$buscaPontos->bindParam(':id_empresa', $_SESSION['id_empresa']);
$buscaPontos->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$buscaPontos->execute();
$result_pontos = $buscaPontos->fetchAll(PDO::FETCH_ASSOC);

$pontosAgrupados = array();

foreach ($result_pontos as $ponto) {
    $id_endereco = $ponto['id_endereco'];
    $raio_ponto_mtrs = $ponto['raio_ponto_mtrs'];
    $id_tipo_ponto = $ponto['id_tipo_ponto'];

    
    if ($raio_ponto_mtrs == 0) {
        if (!isset($pontosAgrupados[$id_tipo_ponto][$id_endereco])) {
            
            $pontosAgrupados[$id_tipo_ponto][$id_endereco] = array(
                'id_endereco' => $id_endereco,
                'nome_endereco' => $ponto['nome_endereco'],
                'cor' => $ponto['cor'],
                'id_tipo_marcacao' => $ponto['id_tipo_marcacao'],
                'raio_ponto_mtrs' => $raio_ponto_mtrs,
                'pontos' => array(),
            );
        }
        
        $pontosAgrupados[$id_tipo_ponto][$id_endereco]['pontos'][] = array(
            'latitude' => $ponto['latitude'],
            'longitude' => $ponto['longitude'],
            'ordem' => $ponto['ordem'],
        );
    } else {
        
        $pontosAgrupados[$id_tipo_ponto][] = $ponto;
    }
}


foreach ($result_gps as &$row) {
    $row['timestamp'] = date("Y-m-d H:i:s");
}


$json_result = array(
    'posicoes_veiculos' => $result_gps,
    'pontos' => $pontosAgrupados, 
);

$json_result = json_encode($json_result, JSON_PRETTY_PRINT);

echo $json_result;

//$file_path = 'monitora.json';
//file_put_contents($file_path, $json_result . PHP_EOL, FILE_APPEND);



?>

