//EXIBIR MAPA

var map;
var marker;
var geocoder; // Variável global para o serviço de geocodificação
var lat;
var lng;
var coordenadas;
var jsonCoordenadas;

function verificaValores() {
  if (document.getElementById("coordenadas").value !== "") {
    document.getElementById("cadastroBt").disabled = false;
  } else {
    document.getElementById("cadastroBt").disabled = true;
  }
}



function initMap() {

  var centro = { lat: -15.894586302388522, lng: -47.836302929687484 };


  var options = {
    zoom: 5,
    center: centro,
    mapTypeId: google.maps.MapTypeId.HYBRID,
    streetViewControl: false,
    mapTypeControl: false,
    fullscreenControl: false,
    styles: [
      {
        featureType: 'poi',
        stylers: [{ visibility: 'off' }],
      },
    ],
  };


  map = new google.maps.Map(document.getElementById('map'), options);


  map.addListener('click', function (event) {
    placeMarker(event.latLng);
  });

  geocoder = new google.maps.Geocoder();
}


function placeMarker(location) {
  // Remover marcador anterior, se existir
  if (marker) {
    marker.setMap(null);
  }

  // Criar novo marcador
  marker = new google.maps.Marker({
    position: location,
    map: map
  });

  // Definir as variáveis globais de latitude e longitude
  lat = location.lat();
  lng = location.lng();
  parseJson(lat, lng);
}

function searchAddress() {
  var address = document.getElementById('address').value;

  // Fazer geocodificação do endereço
  geocoder.geocode({ 'address': address }, function (results, status) {
    if (status === 'OK') {
      // Remover marcador anterior, se existir
      if (marker) {
        marker.setMap(null);
      }

      map.setCenter(results[0].geometry.location); // Centralizar mapa no resultado
      marker = new google.maps.Marker({
        map: map,
        position: results[0].geometry.location
      });

      // Definir as variáveis globais de latitude e longitude
      lat = results[0].geometry.location.lat();
      lng = results[0].geometry.location.lng();
      parseJson(lat, lng);
    }
  });
}

function parseJson(lat, long) {
  if (lat && long) {

    coordenadas = {
      "lat": lat,
      "long": long
    };

    jsonCoordenadas = JSON.stringify(coordenadas);
    document.getElementById("coordenadas").value = jsonCoordenadas;
    verificaValores();
  }

}



$(document).ready(function () {
  $('#cadastro_local_sv').submit(function (event) {
    event.preventDefault();
    let form = document.getElementById('cadastro_local_sv');

    // Desabilita o botão de login e atualiza o texto e o estilo
    var cadastrarLocal = $('#cadastroBt');
    cadastrarLocal.prop('disabled', true);
    cadastrarLocal.html(`
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
          alert('Local de serviço cadastrado com Sucesso!');
          location.reload();

        } else {
          alert('Houve um erro ao cadastrar o Local de serviço, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastrarLocal.prop('disabled', false);
        cadastrarLocal.html('Cadastrar Local');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao cadastrar o Local de serviço, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastrarLocal.prop('disabled', false);
        cadastrarLocal.html('Cadastrar Local');
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
  order: [[2, 'desc']],
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
          alert('Local de serviço atualizado com Sucesso!');
          location.reload();

        } else {
          alert('Houve um erro ao atualizar o Local de serviço, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        confirmarEdicao.prop('disabled', false);
        confirmarEdicao.html('Confirmar Edição');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao atualizar o Local de serviço, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        confirmarEdicao.prop('disabled', false);
        confirmarEdicao.html('Confirmar Edição');
      }
    });
  });
});