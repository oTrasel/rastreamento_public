function validarPlaca() {
    var regex = /[A-Z]{3}[0-9][0-9A-Z][0-9]{2}/;
    var placa = document.getElementById('placaInput').value;
    var mensagemElement = document.getElementById('mensagem');
    var registerBt = document.getElementById('registerBt');

    // Verificar se o campo de entrada está vazio
    if (placa.trim() === "") {
        mensagemElement.style.display = 'none'; // Oculta a mensagem
        return;
    }

    // Exibe a mensagem se o campo não estiver vazio
    mensagemElement.style.display = 'block';

    if (placa.match(regex)) {
        // Placa válida
        mensagemElement.textContent = 'Placa válida';
        mensagemElement.classList.add('placa-valida');
        registerBt.removeAttribute('disabled');
        mensagemElement.classList.remove('placa-invalida');
    } else {
        // Placa inválida
        mensagemElement.textContent = 'Placa inválida';
        mensagemElement.textContent = 'Placa inválida';
        mensagemElement.classList.add('placa-invalida');
        mensagemElement.classList.remove('placa-valida');
        registerBt.setAttribute('disabled', 'disabled');
    }
}


$(document).ready(function () {
    $('#cadastro_veiculo').submit(function (event) {
      event.preventDefault();
  
      // Desabilita o botão de login e atualiza o texto e o estilo
      var cadastrarVeiculo = $('#registerBt');
      cadastrarVeiculo.prop('disabled', true);
      cadastrarVeiculo.html(`
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
            alert('Veiculo de serviço cadastrado com Sucesso!');
            location.reload();
          } else {
            alert('Houve um erro ao cadastrar o Veiculo de serviço, por favor, tente novamente!');
            location.reload();
          }
  
          // Reabilita o botão de login e restaura o texto e o estilo originais
          cadastrarVeiculo.prop('disabled', false);
          cadastrarVeiculo.html('Cadastrar');
        },
        error: function (xhr, status, error) {
            alert('Houve um erro ao cadastrar o Veiculo de serviço, por favor, tente novamente!');
  
          // Reabilita o botão de login e restaura o texto e o estilo originais
          cadastrarVeiculo.prop('disabled', false);
          cadastrarVeiculo.html('Cadastrar');
        }
      });
    });
  });