$('#enderecoSelect').select2({
    theme: 'bootstrap-5',
    width: '100%',
    closeOnSelect: false,
    placeholder: 'Selecione os endereços'

});

document.addEventListener("DOMContentLoaded", function () {
    var btnCadastraCartao = document.getElementById("cadastraCartao");
    var hrInicio = document.getElementById("hrInicio");
    var hrFim = document.getElementById("hrFim");

    function validarHoras() {
        var inicio = new Date("2000-01-01T" + hrInicio.value + ":00");
        var fim = new Date("2000-01-01T" + hrFim.value + ":00");

        if (fim <= inicio) {
            alert("A hora final deve ser maior que a hora de início.");
            btnCadastraCartao.disabled = true;
        } else {
            btnCadastraCartao.disabled = false;
        }
    }

    hrInicio.addEventListener("change", validarHoras);
    hrFim.addEventListener("change", validarHoras);
});

$(document).ready(function () {
    $('#cadastro_cartao').submit(function (event) {
      event.preventDefault();
  
      // Desabilita o botão de login e atualiza o texto e o estilo
      var cadastrarCartao = $('#cadastraCartao');
      cadastrarCartao.prop('disabled', true);
      cadastrarCartao.html(`
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
            alert('Cartão Cadastrado com Sucesso!');
            document.getElementById("cadastro_cartao").reset();
  
            // Reabilita o botão de login e restaura o texto e o estilo originais
            cadastrarCartao.prop('disabled', false);
            cadastrarCartao.html('Cadastrar');
  
            // Aguarda 500 milissegundos antes de fechar o modal e limpar o select
            setTimeout(function () {
              $('#modalPontos').modal('hide');
              $('.modal-backdrop').hide();
              $('#enderecoSelect').empty();
            }, 500);
          } else {
            alert('Houve um erro ao cadastrar o Cartão, por favor, tente novamente!');
            location.reload();
          }
        },
        error: function (xhr, status, error) {
          alert('Houve um erro ao cadastrar o Cartão, por favor, tente novamente!');
  
          // Reabilita o botão de login e restaura o texto e o estilo originais
          cadastrarCartao.prop('disabled', false);
          cadastrarCartao.html('Cadastrar');
        }
      });
    });
  });
  