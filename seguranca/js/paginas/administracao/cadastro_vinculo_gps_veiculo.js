$(document).ready(function() {
  // Evento para detectar a abertura do modal
  $('#modalCadastroVinculo').on('shown.bs.modal', function() {
      setTimeout(function() {
          // Inicialização do primeiro Select2
          $("#rastreadorSelect").select2({
              dropdownParent: $("#modalCadastroVinculo"),
              placeholder: 'Selecione um Rastreador',
              allowClear: true,
              theme: 'bootstrap-5'
          });
          $('#rastreadorSelect').val(null).trigger('change');
          $('#rastreadorSelect').next('.select2-container').find('.select2-selection--single').css('height', '40px');

          // Inicialização do segundo Select2
          $("#veiculoSelect").select2({
              dropdownParent: $("#modalCadastroVinculo"),
              placeholder: 'Selecione um Veiculo',
              allowClear: true,
              theme: 'bootstrap-5'
              // Adicione mais configurações, se necessário
          });
          $('#veiculoSelect').val(null).trigger('change');
          $('#veiculoSelect').next('.select2-container').find('.select2-selection--single').css('height', '40px');

          // Adicione mais inicializações de Select2 conforme necessário para outros elementos

      }, 1);
  });
});

$(document).ready(function() {
  // Evento para detectar a abertura do modal
  $('#finalizaVinculo').on('shown.bs.modal', function() {
      setTimeout(function() {
          // Inicialização do primeiro Select2
          $("#desvinculoSelect").select2({
              dropdownParent: $("#finalizaVinculo"),
              placeholder: 'Selecione um Vinculo',
              allowClear: true,
              theme: 'bootstrap-5'
          });
          $('#desvinculoSelect').val(null).trigger('change');
          $('#desvinculoSelect').next('.select2-container').find('.select2-selection--single').css('height', '40px');


      }, 1);
  });
});


$(document).ready(function () {
    $('#cadastro_vinculo_veiculo_rastreador').submit(function (event) {
      event.preventDefault();
  
      // Desabilita o botão de login e atualiza o texto e o estilo
      var vinculaGpsVeiculo = $('#vinculaGpsVeiculo');
      vinculaGpsVeiculo.prop('disabled', true);
      vinculaGpsVeiculo.html(`
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
            alert('Vinculo Criado com Sucesso!');
            location.reload();
          } else {
            alert('Houve um erro ao cadastrar o Vinculo, por favor, tente novamente!');
            location.reload();
          }
  
          // Reabilita o botão de login e restaura o texto e o estilo originais
          vinculaGpsVeiculo.prop('disabled', false);
          vinculaGpsVeiculo.html('Cadastrar');
        },
        error: function (xhr, status, error) {
            alert('Houve um erro ao cadastrar o Vinculo, por favor, tente novamente!');
  
          // Reabilita o botão de login e restaura o texto e o estilo originais
          vinculaGpsVeiculo.prop('disabled', false);
          vinculaGpsVeiculo.html('Cadastrar');
        }
      });
    });
  });



  
$(document).ready(function () {
  $('#finalizaVinculoForm').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var fimVinculo = $('#fimVinculo');
    fimVinculo.prop('disabled', true);
    fimVinculo.html(`
      <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
      <span role="status">Desvinculando</span>
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
          alert('Vinculo Finalizado com Sucesso!');
          location.reload();
        } else {
          alert('Houve um erro ao finalizar o Vinculo, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        fimVinculo.prop('disabled', false);
        fimVinculo.html('Finalizar Vinculo');
      },
      error: function (xhr, status, error) {
          alert('Houve um erro ao finalizar o Vinculo, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        fimVinculo.prop('disabled', false);
        fimVinculo.html('Finalizar Vinculo');
      }
    });
  });
});