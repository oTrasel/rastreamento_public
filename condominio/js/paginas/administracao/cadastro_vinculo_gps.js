
$(document).ready(function () {
  $('#vinculoGPS').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var vinculoBT = $('#vinculoBT');
    vinculoBT.prop('disabled', true);
    vinculoBT.html(`
      <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
      <span role="status">Rastreando</span>
    `);

    var formData = $(this).serialize();

    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: formData,
      success: function (response) {
        // Verifica o conteúdo da resposta do servidor
        response = response.trim();
        if (response === 'successVinculo') {
          alert('Rastreamento Inciado!');
          location.reload();
        } else if (response === 'emUso') {
          alert('Placa já está em um rastreamento ATIVO!');
        } else {
          alert('Houve um Erro ao iniciar o Rastreamento, verifique os campos!');
          location.reload();
        }
        vinculoBT.prop('disabled', false);
        vinculoBT.html('Iniciar Rastreamento');
      },
      error: function (xhr, status, error) {

        // Reabilita o botão de login e restaura o texto e o estilo originais
        vinculoBT.prop('disabled', false);
        vinculoBT.html('Iniciar Rastreamento');
      }
    });

  });



  function desabilitarElementos() {
    var inputPlaca = $("#novaPlaca").val();
    var confirmarDados = $("#confirmarDados");
    var selectGps = $("#gpsSelect").val();
    var selectLote = $("#loteSelect").val();
    var condutor = $("#novoCondutor").val();
    var contato = $("#contatoCondutor").val();
    //REFAZER A LOGICA
    //quando o numero do pedido não for nulo, ele desabilita todos os filtros
    if (inputPlaca != "") {
      if (selectGps != "semGPS" && selectLote.length != 0 && condutor != "" && contato != "") {
        confirmarDados.prop("disabled", false);
      } else {
        confirmarDados.prop("disabled", true);
      }
    } else {
      confirmarDados.prop("disabled", true);
      $("#novaPlaca").prop("disabled", false);
    }
  }
  // Chame a função quando houver alterações nos campos relevantes
  $("#novaPlaca, #gpsSelect, #loteSelect, #novoCondutor, #contatoCondutor").on("input", function () {
    desabilitarElementos();
  });

});





$('#loteSelect').select2({
  theme: 'bootstrap-5',
  width: '100%',
  closeOnSelect: false,
  placeholder: 'Selecione os endereços'
});


$(document).ready(function () {
  $('#mapearRastreamento').on('shown.bs.modal', function () {
    if (!$("#gpsSelect").data('select2')) {
      $("#gpsSelect").select2({
        dropdownParent: $("#mapearRastreamento"),
        placeholder: 'Selecione um Rastreador',
        theme: 'bootstrap-5'
      });
    }
    $('#gpsSelect').val('semGPS').trigger('change');
    $('#gpsSelect').next('.select2-container').find('.select2-selection--single').css('height', '40px');

    if (!$("#loteSelect").data('select2')) {
      $("#loteSelect").select2({
        dropdownParent: $("#mapearRastreamento"),
        placeholder: 'Selecione os Lotes',
        closeOnSelect: false,
        theme: 'bootstrap-5'
      });
    }
    $('#loteSelect').val(null).trigger('change');
    $('#loteSelect').next('.select2-container').find('.select2-selection--single').css('height', '40px');

    if (!$("#placaSelect").data('select2')) {
      $("#placaSelect").select2({
        dropdownParent: $("#mapearRastreamento"),
        placeholder: 'Selecione uma Placa',
        theme: 'bootstrap-5'
      });
    }
    $('#placaSelect').val('semPlaca').trigger('change');
    $('#placaSelect').next('.select2-container').find('.select2-selection--single').css('height', '40px');
  });
});





