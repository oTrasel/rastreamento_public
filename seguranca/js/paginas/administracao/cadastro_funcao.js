$(document).ready(function () {
    $('#cadastro_funcao').submit(function (event) {
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
            alert('Função cadastrada com Sucesso!');
            location.reload();
          } else {
            alert('Houve um erro ao cadastrar a Função, por favor, tente novamente!');
            location.reload();
          }
  
          // Reabilita o botão de login e restaura o texto e o estilo originais
          cadastrarFuncao.prop('disabled', false);
          cadastrarFuncao.html('Cadastrar');
        },
        error: function (xhr, status, error) {
            alert('Houve um erro ao cadastrar a Função, por favor, tente novamente!');
  
          // Reabilita o botão de login e restaura o texto e o estilo originais
          cadastrarFuncao.prop('disabled', false);
          cadastrarFuncao.html('Cadastrar');
        }
      });
    });
  });