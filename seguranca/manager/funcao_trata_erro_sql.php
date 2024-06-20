<?php
function tratarErroSQLServer(PDOException $e) {
    $codigoErroPDO = $e->getCode();

    if ($codigoErroPDO === '0') {
        return "Erro desconhecido: " . $e->getMessage();
    }

    $codigoErroSQLServer = hexdec(substr($codigoErroPDO, -8));

    switch ($codigoErroSQLServer) {
        case 547:
            return "Violação de restrição de chave estrangeira";
        case 2601:
            return "Duplicidade de chave única";
        default:
            return "Erro SQL Server: $codigoErroSQLServer";
    }
}
?>