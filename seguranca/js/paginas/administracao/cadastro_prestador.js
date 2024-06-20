function validaSenha() {
  var campo1 = document.getElementById('password').value;
  var campo2 = document.getElementById('c_password').value;
  var confirmPassElement = document.getElementById('confirmPass');
  var registerBtElement = document.getElementById("registerBt");

  // Verificar se os campos estão vazios
  if (campo1 === '' || campo2 === '') {
    confirmPassElement.innerHTML = "";
    confirmPassElement.style.color = "white";
    registerBtElement.disabled = true;
    return;
  }

  if (campo1 === campo2) {
    confirmPassElement.innerHTML = "Senhas são iguais!";
    confirmPassElement.style.color = "green";
    registerBtElement.disabled = false;
  } else {
    confirmPassElement.innerHTML = "As senhas são diferentes.";
    confirmPassElement.style.color = "red";
    registerBtElement.disabled = true;
  }
}

function VerificaCPF(inputValue) {
  var strCpf = inputValue.replace(/[^\d]/g, ''); // Remover caracteres não numéricos
  var soma = 0;
  var resto;

  if (strCpf === "") {
    limparMensagemCpf();
    return;
  }

  if (strCpf == "00000000000") {
    atualizarMensagemCpf(false);
    return;
  }

  for (var i = 1; i <= 9; i++) {
    soma = soma + parseInt(strCpf.substring(i - 1, i)) * (11 - i);
  }

  resto = soma % 11;

  if (resto == 10 || resto == 11 || resto < 2) {
    resto = 0;
  } else {
    resto = 11 - resto;
  }

  if (resto != parseInt(strCpf.substring(9, 10))) {
    atualizarMensagemCpf(false);
    return;
  }

  soma = 0;

  for (var i = 1; i <= 10; i++) {
    soma = soma + parseInt(strCpf.substring(i - 1, i)) * (12 - i);
  }

  resto = soma % 11;

  if (resto == 10 || resto == 11 || resto < 2) {
    resto = 0;
  } else {
    resto = 11 - resto;
  }

  if (resto != parseInt(strCpf.substring(10, 11))) {
    atualizarMensagemCpf(false);
    return;
  }

  atualizarMensagemCpf(true);
}

function atualizarMensagemCpf(valido) {
  var mensagemElement = document.getElementById("cpfMessage");
  mensagemElement.style.color = valido ? "green" : "red";
  mensagemElement.innerHTML = valido ? "CPF válido" : "CPF inválido";
}

function limparMensagemCpf() {
  var mensagemElement = document.getElementById("cpfMessage");
  mensagemElement.style.color = "";
  mensagemElement.innerHTML = "";
}



function cpfEdita(inputValue) {
  var strCpf = inputValue.replace(/[^\d]/g, ''); // Remover caracteres não numéricos
  var soma = 0;
  var resto;

  if (strCpf === "") {
      limpaMsg();
      return;
  }

  if (strCpf == "00000000000") {
      atualizaMsg(false);
      return;
  }

  for (var i = 1; i <= 9; i++) {
      soma = soma + parseInt(strCpf.substring(i - 1, i)) * (11 - i);
  }

  resto = soma % 11;

  if (resto == 10 || resto == 11 || resto < 2) {
      resto = 0;
  } else {
      resto = 11 - resto;
  }

  if (resto != parseInt(strCpf.substring(9, 10))) {
      atualizaMsg(false);
      return;
  }

  soma = 0;

  for (var i = 1; i <= 10; i++) {
      soma = soma + parseInt(strCpf.substring(i - 1, i)) * (12 - i);
  }

  resto = soma % 11;

  if (resto == 10 || resto == 11 || resto < 2) {
      resto = 0;
  } else {
      resto = 11 - resto;
  }

  if (resto != parseInt(strCpf.substring(10, 11))) {
      atualizaMsg(false);
      return;
  }

  atualizaMsg(true);
}

function atualizaMsg(valido) {
  var mensagemElement = document.getElementById("cpfEditavelMsg");
  mensagemElement.style.color = valido ? "green" : "red";
  mensagemElement.innerHTML = valido ? "CPF válido" : "CPF inválido";
}

function limpaMsg() {
  var mensagemElement = document.getElementById("cpfEditavelMsg");
  mensagemElement.style.color = "";
  mensagemElement.innerHTML = "";
}



$(document).ready(function () {
  $('#cadastro_prestador').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var cadastrarPrestador = $('#registerBt');
    cadastrarPrestador.prop('disabled', true);
    cadastrarPrestador.html(`
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
          alert('Prestador cadastrado com Sucesso!');
          location.reload();
        } else {
          alert('Houve um erro ao cadastrar o Prestador, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastrarPrestador.prop('disabled', false);
        cadastrarPrestador.html('Cadastrar');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao cadastrar o Prestador, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastrarPrestador.prop('disabled', false);
        cadastrarPrestador.html('Cadastrar');
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
  order: [[7, 'desc']],
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
      <span role="status">Atualizando informações</span>
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
          alert('Cadastro Atualizado com Sucesso!');
          location.reload();
        } else {
          alert('Houve um erro ao atualizar o cadastro, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        confirmarEdicao.prop('disabled', false);
        confirmarEdicao.html('Confirmar Edição');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao atualizar o cadastro, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        confirmarEdicao.prop('disabled', false);
        confirmarEdicao.html('Confirmar Edição');
      }
    });
  });
});