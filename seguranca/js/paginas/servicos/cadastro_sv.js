
$(document).ready(function () {
  $('#cadastro_servico').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var cadastroSvVeiculo = $('#iniciarVeiculoBt');
    cadastroSvVeiculo.prop('disabled', true);
    cadastroSvVeiculo.html(`
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
          alert('Ronda cadastrada com Sucesso!');
          location.reload();
        } else {
          alert('Houve um erro ao cadastrar a Ronda, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastroSvVeiculo.prop('disabled', false);
        cadastroSvVeiculo.html('Cadastrar');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao cadastrar a Ronda, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastroSvVeiculo.prop('disabled', false);
        cadastroSvVeiculo.html('Cadastrar');
      }
    });
  });
});




$(document).ready(function () {
  $('#cadastro_servico_solo').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var cadastroSv = $('#iniciarRondaBT');
    cadastroSv.prop('disabled', true);
    cadastroSv.html(`
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
          alert('Ronda cadastrada com Sucesso!');
          location.reload();
        } else {
          alert('Houve um erro ao cadastrar a Ronda, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastroSv.prop('disabled', false);
        cadastroSv.html('Cadastrar');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao cadastrar a Ronda, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastroSv.prop('disabled', false);
        cadastroSv.html('Cadastrar');
      }
    });
  });
});

//Faz com que o input oculto receba o valor do ID do rastreio respectivo

$(document).ready(function () {
  var idDoBotaoClicado;

  // Adicione isso para lidar com o clique nos botões
  $('.btn-finalizar').click(function () {
    // Obter o data-id do botão clicado
    idDoBotaoClicado = $(this).data('id');
  });

  // Adicione isso para lidar com o envio do formulário
  $('#finalizaRonda').submit(function (e) {
    // Atribuir o ID ao campo oculto dentro do modal
    $('#campoOcultoID').val(idDoBotaoClicado);

    // Exibir o modal (se necessário)
    $('#fimRonda').modal('show');


  });
});



//valida se as senhas digitadas são iguais
function validaSenha() {
  var campo1 = document.getElementById('password').value;
  var campo2 = document.getElementById('c_password').value;
  var confirmPassElement = document.getElementById('confirmPass');
  var finalizarRondaBt = document.getElementById("finalizarRondaBt");

  
  // Verificar se os campos estão vazios
  if (campo1 === '' || campo2 === '') {
    confirmPassElement.innerHTML = "";
    confirmPassElement.style.color = "white";
    finalizarRondaBt.disabled = true;
    return;
  }
  
  if (campo1 === campo2) {
    confirmPassElement.innerHTML = "Senhas são iguais!";
    confirmPassElement.style.color = "green";
    finalizarRondaBt.disabled = false;
  } else {
    confirmPassElement.innerHTML = "As senhas são diferentes.";
    confirmPassElement.style.color = "red";
    finalizarRondaBt.disabled = true;
  }


}



$(document).ready(function () {
  $('#finalizaRonda').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var finalizarRondaBt = $('#finalizarRondaBt');
    finalizarRondaBt.prop('disabled', true);
    finalizarRondaBt.html(`
      <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
      <span role="status">Finalizando</span>
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
          alert('Ronda Finalizada com Sucesso!');
          location.reload();
        } else {
          alert('Houve um erro ao Finalizar a Ronda, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        finalizarRondaBt.prop('disabled', false);
        finalizarRondaBt.html('Finalizar Ronda');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao Finalizar a Ronda, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        finalizarRondaBt.prop('disabled', false);
        finalizarRondaBt.html('Finalizar Ronda');
      }
    });
  });
});

//Faz com que o input oculto receba o valor do ID do rastreio respectivo

$(document).ready(function () {
  var idDoBotaoClicado;

  // Adicione isso para lidar com o clique nos botões
  $('.btn-finalizar').click(function () {
    // Obter o data-id do botão clicado
    idDoBotaoClicado = $(this).data('id');
  });

  // Adicione isso para lidar com o envio do formulário
  $('#finalizaRonda').submit(function (e) {
    // Atribuir o ID ao campo oculto dentro do modal
    $('#campoOcultoID').val(idDoBotaoClicado);

    // Exibir o modal (se necessário)
    $('#fimRonda').modal('show');


  });
});






$(document).ready(function() {
  // Evento para detectar a abertura do modal
  $('#modalVeiculo').on('shown.bs.modal', function() {
      setTimeout(function() {
          // Inicialização do primeiro Select2
          $("#prestador").select2({
              dropdownParent: $("#modalVeiculo"),
              placeholder: 'Selecione um Prestador',
              allowClear: true,
              theme: 'bootstrap-5'
          });
          $('#prestador').val(null).trigger('change');
          $('#prestador').next('.select2-container').find('.select2-selection--single').css('height', '40px');


          // Inicialização do segundo Select2
          $("#cartao").select2({
              dropdownParent: $("#modalVeiculo"),
              placeholder: 'Selecione um Cartão Programa',
              allowClear: true,
              theme: 'bootstrap-5'
              // Adicione mais configurações, se necessário
          });
          $('#cartao').val(null).trigger('change');
          $('#cartao').next('.select2-container').find('.select2-selection--single').css('height', '40px');


          $("#veiculo").select2({
            dropdownParent: $("#modalVeiculo"),
            placeholder: 'Selecione um Veiculo',
            allowClear: true,
            theme: 'bootstrap-5'
            // Adicione mais configurações, se necessário
        });
        $('#veiculo').val(null).trigger('change');
        $('#veiculo').next('.select2-container').find('.select2-selection--single').css('height', '40px');

      }, 1);
  });
});


$(document).ready(function() {
  // Evento para detectar a abertura do modal
  $('#modalApe').on('shown.bs.modal', function() {
      setTimeout(function() {
          // Inicialização do primeiro Select2
          $("#slprestador").select2({
              dropdownParent: $("#modalApe"),
              placeholder: 'Selecione um Prestador',
              allowClear: true,
              theme: 'bootstrap-5'
          });
          $('#slprestador').val(null).trigger('change');
          $('#slprestador').next('.select2-container').find('.select2-selection--single').css('height', '40px');


          // Inicialização do segundo Select2
          $("#slcartao").select2({
              dropdownParent: $("#modalApe"),
              placeholder: 'Selecione um Cartão Programa',
              allowClear: true,
              theme: 'bootstrap-5'
              // Adicione mais configurações, se necessário
          });
          $('#slcartao').val(null).trigger('change');
          $('#slcartao').next('.select2-container').find('.select2-selection--single').css('height', '40px');


          $("#slrastreador").select2({
            dropdownParent: $("#modalApe"),
            placeholder: 'Selecione um Rastreador',
            allowClear: true,
            theme: 'bootstrap-5'
            // Adicione mais configurações, se necessário
        });
        $('#slrastreador').val(null).trigger('change');
        $('#slrastreador').next('.select2-container').find('.select2-selection--single').css('height', '40px');

      }, 1);
  });
});



