let globalMarkers = [];
let globalPolygons = [];
let map;
let data;
let btnVisualizarSelecionado = null;
let originalCenter;
let originalZoom = 15;
let infoWindow;

function initMap() {
  requisitaLocalServico();  
  setInterval(requisitarPontos, 30000);
}

function exibirMapaVinculo() {
  map = new google.maps.Map(document.getElementById('mapsVinculos'), {
    center: { lat: lat_central, lng: long_central },
    zoom: originalZoom,
    mapTypeId: google.maps.MapTypeId.HYBRID,
    streetViewControl: false,
    mapTypeControl: false,
    fullscreenControl: false,
    styles: [
      { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
      { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }
    ]
  });
}

function requisitaLocalServico() {
  $.ajax({
    url: '../manager/auxiliares/busca_local_trabalho.php',
    method: 'GET',
    dataType: 'json',
    success: function (responseData) {
      console.clear();
      if (responseData) {
        localServico = responseData;
        lat_central = parseFloat(localServico[0]['lat_central']);
        long_central = parseFloat(localServico[0]['long_central']);
        originalCenter = { lat: lat_central, lng: long_central };
        requisitarPontos();
        exibirMapaVinculo();
        console.log(lat_central);
      } else {
        console.error('Resposta do servidor não é um objeto válido ou não possui a estrutura esperada.');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      hora = false;
      console.error('Erro na requisição:', textStatus, errorThrown);
    }
  });
}



function requisitarPontos() {
  $.ajax({
    url: '../manager/auxiliares/busca_pontos.php',
    method: 'GET',
    dataType: 'json',
    success: function (responseData) {
      console.clear();
      console.log('Atualizado');

      if (responseData && Array.isArray(responseData.gps)) {
        if (btnVisualizarSelecionado == null) {
          limparMarcadores();
          data = responseData;
          data.gps.forEach(ponto => ponto.isGPS = true);
          adicionarMarcadores(data.gps);
          $(btnVisualizarSelecionado).removeClass('selecionado');
          btnVisualizarSelecionado = null;
          centralizarMapa(originalCenter, originalZoom);
        } else {
          data = responseData;
          data.gps.forEach(ponto => ponto.isGPS = true);
        }
      } else {
        console.error('Resposta do servidor não é um array válido.');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error('Erro na requisição:', textStatus, errorThrown);
    }
  });
}

function centralizarMapa(center, zoom) {
  map.setCenter(center);
  map.setZoom(zoom);
}

$(document).on('click', '.btn-vizualizar', function () {
  const id_servico = parseInt(this.id, 10);

  // Remover a classe 'selecionado' de todos os botões
  $('.btn-vizualizar').removeClass('selecionado');

  // Verificar se o botão clicado não é o mesmo que já estava selecionado
  if (btnVisualizarSelecionado !== this) {
    btnVisualizarSelecionado = this;
    $(this).addClass('selecionado');
    centralizarMapa(originalCenter, originalZoom); // Centralizar no GPS ao clicar no botão
  } else {
    // Se for o mesmo botão, limpar a seleção e exibir todos os marcadores
    btnVisualizarSelecionado = null;
    centralizarMapa(originalCenter, originalZoom); // Voltar à centralização original ao remover a seleção
  }

  console.clear();


  // Sempre limpar marcadores antes de adicionar novos
  limparMarcadores();

  if (btnVisualizarSelecionado) {

    exibirPontosDoServico(id_servico);
  } else {
    console.log('Exibindo todos os pontos GPS.');
    adicionarMarcadores(data.gps);
  }
});


function exibirPontosDoServico(id_servico) {
  if (data && data.gps) {
    const servico = data.gps.find(servico => servico.id_servico == id_servico);
    const gpsFiltrados = data.gps.filter(ponto => ponto.id_servico == id_servico);
    if (servico && servico.pontos) {
      limparMarcadores();

      adicionarMarcadores(gpsFiltrados);
      const primeiroPonto = gpsFiltrados[0];
      const center = new google.maps.LatLng(parseFloat(primeiroPonto.LATITUDE), parseFloat(primeiroPonto.LONGITUDE));
      centralizarMapa(center, 15);

      Object.keys(servico.pontos).forEach(poligonoId => {
        const pontosValidos = servico.pontos[poligonoId];

        if (Array.isArray(pontosValidos)) {
          adicionarMarcadores(pontosValidos, '#FFFF66');
        } else {
          console.warn('O serviço não possui pontos com coordenadas válidas para exibir.');
        }
      });
    } else {
      console.warn('O serviço não possui pontos definidos.');
    }
  } else {
    console.error('Variável data não definida ou não contém a propriedade gps.');
  }
}


function adicionarMarcadores(pontos, corDoMarcador = '#00FF00') {
  if (!Array.isArray(pontos)) {
    console.error('A variável pontos não é um array válido.');
    return;
  }
  const poligonos = [];

  pontos.forEach(ponto => {
    const latitude = parseFloat(ponto.LATITUDE || ponto.latitude);
    const longitude = parseFloat(ponto.LONGITUDE || ponto.longitude);

    if (!isNaN(latitude) && !isNaN(longitude)) {
      const identificadorUnico = ponto.ras_vei_placa || ponto.descr_endereco;

      if (/^#[0-9A-F]{6}$/i.test(corDoMarcador)) {
        if (ponto.raio_ponto_mtrs === "0" && ponto.ordem) {
          poligonos.push({ lat: latitude, lng: longitude, identificadorUnico });

        } else if (ponto.raio_ponto_mtrs > "0") {
          console.log('Criando círculo com raio:', ponto.raio_ponto_mtrs);

          const circle = new google.maps.Circle({
            center: { lat: latitude, lng: longitude },
            radius: parseFloat(ponto.raio_ponto_mtrs),
            map: map,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: corDoMarcador,
            fillOpacity: 0.35,

          });

          // Adicionar evento de mouseover ao círculo
          circle.addListener('mouseover', function (event) {
            infoWindow = new google.maps.InfoWindow({
              content: identificadorUnico,
            });
            infoWindow.setPosition(event.latLng);
            infoWindow.open(map);
          });

          // Adicionar evento de mouseout ao polígono
          circle.addListener('mouseout', function () {
            if (infoWindow) {
              infoWindow.close();
            }
          });


          // Adicionar círculo à lista globalPolygons
          globalPolygons.push(circle);

        } else {
          const marker = new google.maps.Marker({
            position: { lat: latitude, lng: longitude },
            map: map,
            title: identificadorUnico,
            icon: criarIconeCor(corDoMarcador),
          });

          const infoWindow = new google.maps.InfoWindow({
            content: identificadorUnico,
          });

          marker.addListener('click', function () {
            infoWindow.open(map, marker);
          });

          marker.addListener('mouseover', function () {
            infoWindow.open(map, marker);
          });

          marker.addListener('mouseout', function () {
            infoWindow.close();
          });

          globalMarkers.push(marker);
        }
      } else {
        console.error('Cor do marcador inválida:', corDoMarcador);
      }
    } else {
      console.error('Coordenadas inválidas:', ponto.LATITUDE || ponto.latitude, ponto.LONGITUDE || ponto.longitude);
    }
  });

  // Adicione os polígonos ao mapa e à lista globalPolygons
  if (poligonos.length > 0) {
    const identificadorUnico = poligonos[0].identificadorUnico;

    const polygon = new google.maps.Polygon({
      paths: poligonos,
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: corDoMarcador,
      fillOpacity: 0.35,
    });

    // Adicionar evento de mouseover ao polígono
    polygon.addListener('mouseover', function (event) {
      infoWindow = new google.maps.InfoWindow({
        content: identificadorUnico,
      });
      infoWindow.setPosition(event.latLng);
      infoWindow.open(map);
    });

    // Adicionar evento de mouseout ao polígono
    polygon.addListener('mouseout', function () {
      if (infoWindow) {
        infoWindow.close();
      }
    });

    polygon.setMap(map);
    globalPolygons.push(polygon);
  }
}

function criarIconeCor(cor) {
  return {
    path: google.maps.SymbolPath.CIRCLE,
    fillColor: cor,
    fillOpacity: 1,
    strokeColor: '#fff',
    strokeWeight: 1,
    scale: 10,
  };
}

function limparMarcadores(apenasServico = false) {
  // Remove os polígonos do mapa apenas se não for apenasServico
  if (!apenasServico && globalPolygons && Array.isArray(globalPolygons)) {
    globalPolygons.forEach(function (polygon) {
      polygon.setMap(null);
    });

    globalPolygons = [];
  }

  // Remove os marcadores do mapa apenas se não for apenasServico
  if (!apenasServico && globalMarkers && Array.isArray(globalMarkers)) {
    globalMarkers.forEach(function (marker) {
      marker.setMap(null);
    });

    globalMarkers = [];
  }

  console.log('Marcadores e polígonos limpos.');
}
