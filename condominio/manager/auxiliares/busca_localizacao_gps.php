<?php
include('../conexao.php');
session_start();

// Primeiro SELECT (seu código atual)

$stmt = $pdo->prepare("select rv.id_vinculo,
g.descr_gps as 'ras_vei_placa',
tv.DT_ULT_POSICAO,
tv.LATITUDE,
tv.LONGITUDE,
tv.VELOCIDADE,
tv.IGNICAO,
tv.BATERIA,
CASE 
	when tv.quadra is null then '-'
	ELSE tv.quadra
END as quadra,
CASE 
	when tv.RUA is null then '-'
	ELSE tv.RUA
END as rua,
g.cor
FROM gps g 
JOIN rastreador_vinculado rv ON rv.id_gps = g.id_gps 
JOIN FULLTRACK.DBO.TB_VEICULOS tv ON tv.ID_VEICULO_ECJ = g.id_veiculo_ecj
where g.empresa_proprietaria = :empresa");
$stmt->bindParam(':empresa', $_SESSION['id_empresa']);
$stmt->execute();
$result_gps = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Segundo SELECT (novo)
$stmt_poligono = $pdo->prepare("select rv.id_vinculo,
                         pvpg.id_lote,
                         tlc.LATITUDE,
                         tlc.LONGITUDE,
                         g.cor,
                         pvpg.descr_lote,
                         tlc.ORDEM 
                         from rastreador_vinculado rv
                         join pontos_vinculo_placa_gps pvpg 
                         on pvpg.id_vinculo = rv.id_vinculo 
                         join ALDEIA.dbo.TB_LOTES_COORDENADAS tlc 
                         on tlc.ID_LOTE = pvpg.id_lote 
                         JOIN gps g 
                         on g.id_gps = rv.id_gps
                         where g.empresa_proprietaria = :empresa
                         order by 1, 7 asc");
$stmt_poligono->bindParam(':empresa', $_SESSION['id_empresa']);
$stmt_poligono->execute();
$result_poligono = $stmt_poligono->fetchAll(PDO::FETCH_ASSOC);

// Organizar os polígonos por id_vinculo
// Organizar os polígonos por id_vinculo e ID de lote
$poligonos_por_id = [];
foreach ($result_poligono as $poligono) {
    $id_vinculo = $poligono['id_vinculo'];
    $id_lote = $poligono['id_lote'];
    
    if (!isset($poligonos_por_id[$id_vinculo])) {
        $poligonos_por_id[$id_vinculo] = [];
    }
    
    if (!isset($poligonos_por_id[$id_vinculo][$id_lote])) {
        $poligonos_por_id[$id_vinculo][$id_lote] = [
            'pontos' => [],
            'cor' => $poligono['cor'],
            'descr_lote' => $poligono['descr_lote']
        ];
    }

    $poligonos_por_id[$id_vinculo][$id_lote]['pontos'][] = [
        'latitude' => $poligono['LATITUDE'],
        'longitude' => $poligono['LONGITUDE'],
        'ordem' => $poligono['ORDEM']
    ];
}

// Adicionar os polígonos ao resultado do primeiro SELECT
foreach ($result_gps as &$gps) {
    $id_vinculo = $gps['id_vinculo'];
    $gps['poligono'] = isset($poligonos_por_id[$id_vinculo]) ? $poligonos_por_id[$id_vinculo] : [];
}


// Retorne os resultados como JSON
header('Content-Type: application/json');
$json_result = json_encode(array('gps' => $result_gps));

//$file_path = 'monitora.json';
//file_put_contents($file_path, $json_result . PHP_EOL, FILE_APPEND);
echo $json_result;



?>
