<?php
include('../conexao.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selectLote'])) {
        $json['lotes'] = $_POST['selectLote'];

        // Inicialize o array fora do loop
        $loteData = [];

        // Loop através de cada item no JSON
        foreach ($json['lotes'] as $id_lote) {

            $stmt = $pdo->prepare('SELECT tl.NOME_LOTE + \' - \' + tl.QUADRA as lote, tlc.ID_LOTE, tlc.ORDEM, tlc.LATITUDE, tlc.LONGITUDE FROM ALDEIA.dbo.TB_LOTES_COORDENADAS tlc 
            JOIN ALDEIA.dbo.TB_LOTES tl 
            ON tl.id = tlc.ID_LOTE 
            WHERE ID_LOTE = :ID_LOTE');
            $stmt->bindParam(':ID_LOTE', $id_lote, PDO::PARAM_INT);
            $stmt->execute();
            $coordenadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($coordenadas as $coordenada) {
                $loteData[$coordenada['lote']]['pontos'][] = $coordenada;
            }
        
        }
        

        $json_result = json_encode($loteData);

        //$jsonFileName = 'vizualisar.json';
        //file_put_contents($jsonFileName, $json_result);

        echo $json_result; 
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
