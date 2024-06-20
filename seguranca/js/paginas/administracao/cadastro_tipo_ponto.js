$(document).ready(function () {
  $('#cadastro_tipo_ponto').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var cadastrarFuncao = $('#cadastroBt');
    cadastrarFuncao.prop('disabled', true);
    cadastrarFuncao.html(`
        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
        <span role="status">Cadastrando</span>
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
          alert('Tipo de Ponto cadastrado com sucesso!');
          location.reload();
        } else {
          alert('Houve um erro ao cadastrar o tipo do ponto, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastrarFuncao.prop('disabled', false);
        cadastrarFuncao.html('Cadastrar');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao cadastrar o tipo do ponto, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastrarFuncao.prop('disabled', false);
        cadastrarFuncao.html('Cadastrar');
      }
    });
  });
});


$('#tableInfos').DataTable({
  paging: false,
  info: false, // Ativa a funcionalidade de salvar o estado
  "language": {
    "lengthMenu": "Display _MENU_ registros por páginas",
    "zeroRecords": "Nada encontrado - Desculpe",
    "info": "Pagina _PAGE_ de _PAGES_",
    "infoEmpty": "Sem registros",
    "infoFiltered": "(filtered from _MAX_ total records)",
    "search": "Filtrar resultados"
  },
  order: [[5, 'desc']],
  columnDefs: [{ "orderable": false, "targets": -1 }]
});



$(document).ready(function () {
  $('#formAtualizaInfo').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var confirmarEdicao = $('#confirmarEdicao');
    confirmarEdicao.prop('disabled', true);
    confirmarEdicao.html(`
        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
        <span role="status">Atualizando Informações</span>
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
          alert('Informações atualizadas com Sucesso!');
          location.reload();

        } else {
          alert('Houve um erro ao atualizar as informações, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        confirmarEdicao.prop('disabled', false);
        confirmarEdicao.html('Confirmar Edição');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao atualizar as informações, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        confirmarEdicao.prop('disabled', false);
        confirmarEdicao.html('Confirmar Edição');
      }
    });
  });
});