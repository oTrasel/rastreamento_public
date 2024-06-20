<?php
include('../conexao.php');
session_start();

// Primeiro SELECT (seu código atual)

$rastreador = $pdo->prepare("select rs.id_rastreador,
sa.id_servico,
tv.ras_vei_placa,
tv.DT_ULT_POSICAO,
tv.LATITUDE,
tv.LONGITUDE,
tv.VELOCIDADE
from rastreador_seguranca rs
join servicos_ativos sa
on sa.id_rastreador = rs.id_rastreador 
JOIN FULLTRACK.DBO.TB_VEICULOS tv 
ON tv.ID_VEICULO_ECJ = rs.id_veiculo_ecj 
where rs.id_empresa = :id_empresa
and rs.id_local_trabalho = :local_trabalho");
$rastreador->bindParam(':id_empresa', $_SESSION['id_empresa']);
$rastreador->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$rastreador->execute();
$result_gps = $rastreador->fetchAll(PDO::FETCH_ASSOC);



$pontos = $pdo->prepare("select sa.id_servico,
cpp.id_cartao,
pll.id_endereco,
pll.latitude,
pll.longitude,
pll.raio_ponto_mtrs,
ee.descr_endereco,
pll.ordem
from servicos_ativos sa 
join cartao_programa_pontos cpp
on cpp.id_cartao = sa.id_cartao_programa
join poligono_lat_long_endereco_especial pll  
on pll.id_endereco = cpp.id_endereco 
join cartao_programa cp
on cp.id_cartao = cpp.id_cartao 
join endereco_especial ee 
on ee.id_endereco = pll.id_endereco 
where cp.id_empresa = :id_empresa
and sa.id_local_trabalho = :local_trabalho ");
$pontos->bindParam(':id_empresa', $_SESSION['id_empresa']);
$pontos->bindParam(':local_trabalho', $_SESSION['local_trabalho']);
$pontos->execute();
$pontos_resul = $pontos->fetchAll(PDO::FETCH_ASSOC);


$pontos_por_id = [];
foreach ($pontos_resul as $row) {
    $id_servico = $row['id_servico'];

    // Verifica se o raio é igual a 0 e se já existe um array para esse id_endereco
    if ($row['raio_ponto_mtrs'] == 0 && isset($pontos_por_id[$id_servico][$row['id_endereco']])) {
        // Adiciona ao array existente
        $pontos_por_id[$id_servico][$row['id_endereco']][] = [
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'descr_endereco' => $row['descr_endereco'],
            'raio_ponto_mtrs' => $row['raio_ponto_mtrs'],
            'ordem' => $row['ordem']
        ];
    } else {
        // Cria um novo array
        $pontos_por_id[$id_servico][$row['id_endereco']] = [
            [
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'descr_endereco' => $row['descr_endereco'],
                'raio_ponto_mtrs' => $row['raio_ponto_mtrs'],
                'ordem' => $row['ordem']
            ]
        ];
    }
}

foreach ($result_gps as &$gps) {
    $id_servico = $gps['id_servico'];
    $gps['pontos'] = isset($pontos_por_id[$id_servico]) ? $pontos_por_id[$id_servico] : [];
}

// Adiciona a data e hora ao array final
$json_data = array(
    'gps' => $result_gps,
    'data_geracao' => date('Y-m-d H:i:s')  // Adiciona a data e hora no formato desejado
);

$json_string = json_encode($json_data, JSON_PRETTY_PRINT);

// Caminho para o arquivo de log
//$log_file_path = 'log.json';

// Salva o JSON no arquivo de log
//file_put_contents($log_file_path, $json_string);

echo $json_string;
