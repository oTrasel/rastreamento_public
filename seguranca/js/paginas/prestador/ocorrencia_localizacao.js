var tabelaInfos = $('#tableInfos').DataTable({
    paging: false,
    info: false,
    "language": {
        "lengthMenu": "Display _MENU_ registros por páginas",
        "zeroRecords": "Sem Ocorrências para Justificar!",
        "info": "Pagina _PAGE_ de _PAGES_",
        "infoEmpty": "Sem registros",
        "infoFiltered": "(filtered from _MAX_ total records)",
        "search": "Filtrar resultados"
    },
    order: [[2, 'desc']],
    columnDefs: [{ "orderable": false, "targets": -1 }] 
});

var tabelaInfos = $('#tableJustificativa').DataTable({
    paging: false,
    info: false,
    "language": {
        "lengthMenu": "Display _MENU_ registros por páginas",
        "zeroRecords": "Nenhuma Ocorrência Justificada!",
        "info": "Pagina _PAGE_ de _PAGES_",
        "infoEmpty": "Sem registros",
        "infoFiltered": "(filtered from _MAX_ total records)",
        "search": "Filtrar resultados"
    },
    order: [[5, 'desc']]
});




document.addEventListener('DOMContentLoaded', function () {
    let checkboxes = document.querySelectorAll('input[type="checkbox"]');
    let btnJustificar = document.getElementById('btnJustificar');

    function onChangeCheck() {
        let checked = false;
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                checked = true;
            }
        });

        btnJustificar.disabled = !checked;
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', onChangeCheck);
    });
});


$(document).ready(function () {
    $('#formJustificativa').submit(function (event) {
        event.preventDefault();

        // Desabilita o botão de login e atualiza o texto e o estilo
        var confirmarJustificativa = $('#confirmarJustificativa');
        confirmarJustificativa.prop('disabled', true);
        confirmarJustificativa.html(`
        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
        <span role="status">justificando</span>
      `);

        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                // Verifica o conteúdo da resposta do servidor
                response = response.trim();
                if (response === 'sucesso') {
                    alert('Ocorrência(s) Justificada(s)');
                    location.reload();
                } else {
                    alert('Houve um erro ao cadastrar Justificativa, por favor, tente novamente!');
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                alert('Houve um erro ao cadastrar Justificativa, por favor, tente novamente!');

                // Reabilita o botão de login e restaura o texto e o estilo originais
                confirmarJustificativa.prop('disabled', false);
                confirmarJustificativa.html('Confirmar Justificativa');
            }
        });
    });
});
