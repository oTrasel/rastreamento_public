
$(document).ready(function() {
    $('#desvinculoForm').submit(function(event) {
      event.preventDefault();
  
      // Desabilita o botão de login e atualiza o texto e o estilo
      var desvinculoBT = $('#desvinculoBT');
      desvinculoBT.prop('disabled', true);
      desvinculoBT.html(`
        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
        <span role="status">Parando Rastreamento</span>
      `);
  
      var formData = $(this).serialize();
  
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(response) {
          // Verifica o conteúdo da resposta do servidor
          response = response.trim();
          if(response === 'sucessoVinculo'){
            alert('Rastreamento interrompido com Sucesso!');
            location.reload();
          }else if(response === 'erroDesvinculo'){
            alert('Houve algum erro, por favor faça login novamente.');
          }
  
          desvinculoBT.prop('disabled', false);
          desvinculoBT.html('Parar de Rastrear');
        },
        error: function(xhr, status, error) {
  
          // Reabilita o botão de login e restaura o texto e o estilo originais
          desvinculoBT.prop('disabled', false);
          desvinculoBT.html('Parar de Rastrear');
        }
      });
    });
  });  
  