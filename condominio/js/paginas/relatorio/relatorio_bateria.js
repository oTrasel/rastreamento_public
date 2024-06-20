var tabelaInfos = $('#tableInfos').DataTable({
    paging: false,
    info: false,
    stateSave: true, // Ativa a funcionalidade de salvar o estado
    "language": {
        "lengthMenu": "Display _MENU_ registros por páginas",
        "zeroRecords": "Nada encontrado - Desculpe",
        "info": "Pagina _PAGE_ de _PAGES_",
        "infoEmpty": "Sem registros",
        "infoFiltered": "(filtered from _MAX_ total records)",
        "search": "Filtrar resultados"
    },
    order: [[3, 'desc']]
});

//ajax requisitante informacoes gerais
function requisitaTotal() {
    $.ajax({
        url: '../manager/auxiliares/busca_baterias.php',
        method: 'GET',
        dataType: 'json',
        success: function (responseData) {
            if (responseData) {
                data = responseData;
                renderizaTotal(data);
            } else {
                console.error('Resposta do servidor não é um objeto válido ou não possui a estrutura esperada.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Erro na requisição:', textStatus, errorThrown);
        }
    });
}



function renderizaTotal(responseData) {
    // Limpar a DataTable
    tabelaInfos.clear();

    responseData.forEach(function (row) {
        var rowData = [];

        ['descr_gps', 'VOLTAGEM', 'BATERIA', 'dt_ultima_posicao'].forEach(function (campo) {
            rowData.push(row[campo]);
        });

        tabelaInfos.row.add(rowData);
    });

    // Redesenhar a DataTable
    tabelaInfos.draw();
}





requisitaTotal();

setInterval(function () {
    requisitaTotal();
}, 30000);