let globalMarkers = [];
let map;
let zoomOriginal = 17; // Adicione esta linha para definir o zoom original
let horaAtualElement;
let pontos;
let data;
let poligonosNoMapa = [];
let pontosAreas = [];


function initMap() {
  requisitaLocalServico();
  setInterval(function () {
    requisitarPontos();
    if (hora == true) {
      atualizarHora();
    }
  }, 30000);
  atualizarHora();
}


function requisitaLocalServico() {
  $.ajax({
    url: '../manager/auxiliares/busca_local_trabalho.php',
    method: 'GET',
    dataType: 'json',
    success: function (responseData) {
      console.clear();
      if (responseData ) {
        localServico = responseData; 
        lat_central = parseFloat(localServico[0]['lat_central']);
        long_central = parseFloat(localServico[0]['long_central']);
        requisitarPontos();
        exibirMapaVinculo();
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

function exibirMapaVinculo() {
  map = new google.maps.Map(document.getElementById('mapsVinculos'), {
    center: { lat: lat_central, lng: long_central },
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.HYBRID,
    streetViewControl: true,
    mapTypeControl: true,
    fullscreenControl: true,
    styles: [
      { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
      { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }
    ]
  });

  const container = document.createElement('div');
  container.style.position = 'absolute';
  container.style.top = '10px';
  container.style.right = '10px';
  container.style.display = 'flex';
  container.style.flexDirection = 'column';
  container.style.alignItems = 'end';

  horaAtualElement = document.createElement('div');
  horaAtualElement.id = 'horaAtualElement';
  horaAtualElement.style.padding = '5px';
  horaAtualElement.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
  horaAtualElement.style.color = '#fff';
  horaAtualElement.style.borderRadius = '5px';
  horaAtualElement.style.fontFamily = 'Arial, sans-serif';
  horaAtualElement.style.fontSize = '16px';
  horaAtualElement.style.fontWeight = 'bold';
  horaAtualElement.style.marginTop = '1%';
  horaAtualElement.style.marginRight = '1%';
  horaAtualElement.style.width = '100%';
  container.appendChild(horaAtualElement);

  const botaoElement = document.createElement('button');
  botaoElement.type = 'button';
  botaoElement.className = 'btn btn-primary';
  botaoElement.textContent = 'Opções';
  botaoElement.style.borderRadius = '5px';
  botaoElement.style.marginTop = '1%';
  botaoElement.style.marginRight = '1%';
  botaoElement.style.textAlign = 'left';
  botaoElement.style.display = 'block';

  const modalElement = document.getElementById('opcMarcadores');

  // Adicionar evento de clique para abrir o modal
  botaoElement.addEventListener('click', () => {
    const modal = new bootstrap.Modal(modalElement, { backdrop: false });
    modal.show();
  });

  container.appendChild(modalElement);
  container.appendChild(botaoElement);

  // Adicionar contêiner ao mapa
  map.controls[google.maps.ControlPosition.TOP_RIGHT].push(container);


}

let hora;

function requisitarPontos() {
  $.ajax({
    url: '../manager/auxiliares/monitora_pontos.php',
    method: 'GET',
    dataType: 'json',
    success: function (responseData) {
      console.clear();
      if (responseData && responseData.posicoes_veiculos && Array.isArray(responseData.posicoes_veiculos)) {
        data = responseData; // Atribua a resposta à variável global data
        adicionarMarcadores(data.posicoes_veiculos, data.pontos);
        renderizarResultados(data.posicoes_veiculos);
        hora = true;
        setTimeout(function () {
          atualizarHora();
        }, 500);
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


function atualizarHora() {
  const agora = new Date();
  const dia = agora.getDate().toString().padStart(2, '0');
  const mes = (agora.getMonth() + 1).toString().padStart(2, '0');
  const ano = agora.getFullYear();
  const hora = agora.getHours().toString().padStart(2, '0');
  const minuto = agora.getMinutes().toString().padStart(2, '0');
  const segundo = agora.getSeconds().toString().padStart(2, '0');

  const horaAtualizada = `${dia}/${mes}/${ano} ${hora}:${minuto}:${segundo}`;
  horaAtualElement.innerText = (horaAtualizada).toUpperCase();
  horaGrid.innerText = ("Atualizado Em: " + horaAtualizada).toUpperCase();
}

function adicionarMarcadores(posicoesVeiculos, _pontos) {
  // Verificar se há pontos para exibir
  if (!posicoesVeiculos || posicoesVeiculos.length === 0) {
    console.warn('Sem pontos de veículos para exibir.');
    return;
  }

  pontos = _pontos;
  limparMarcadores(); // Limpar marcadores existentes

  // Variável para rastrear a InfoWindow aberta
  let infoWindowAberta = null;

  // Adicionar evento de clique ao mapa para fechar a InfoWindow
  map.addListener('click', function (event) {
    if (infoWindowAberta) {
      infoWindowAberta.close();
      infoWindowAberta = null;
    }
  });

  posicoesVeiculos.forEach(posicaoVeiculo => {
    const latitude = parseFloat(posicaoVeiculo.LATITUDE);
    const longitude = parseFloat(posicaoVeiculo.LONGITUDE);

    if (!isNaN(latitude) && !isNaN(longitude)) {
      const identificadorUnico = posicaoVeiculo.ras_vei_placa;

      let iconeURL = obterCaminhoImagem(posicaoVeiculo.tipo_veiculo);


      // Criar o marcador
      const marker = new google.maps.Marker({
        position: { lat: latitude, lng: longitude },
        map: map,
        label: {
          text: posicaoVeiculo.prefixo,
          className: "map-label",
        },
        title: posicaoVeiculo.prefixo,
        icon: iconeURL ? {
          url: iconeURL,
          scaledSize: new google.maps.Size(30, 16),
          labelOrigin: new google.maps.Point(15, 26),
        } : criarIconeCor('#FFFF00'),
      });

      // Criar um InfoWindow
      const infoWindow = new google.maps.InfoWindow({
        content: "Velocidade: " + posicaoVeiculo.VELOCIDADE,
      });

      // Adicionar evento de clique ao marcador
      marker.addListener('click', function () {
        // Fechar a InfoWindow anterior, se estiver aberta
        if (infoWindowAberta) {
          infoWindowAberta.close();
        }

        // Abrir o InfoWindow ao clicar no marcador
        infoWindow.setContent("Velocidade: " + posicaoVeiculo.VELOCIDADE + 
                              "<br>" + "Rua: " + posicaoVeiculo.rua + 
                              "<br>" + "Quadra: " + posicaoVeiculo.quadra + 
                              "<br>" + "Lote: " + posicaoVeiculo.lote + 
                              "<br>" + posicaoVeiculo.DT_ULT_POSICAO); // Atualizar conteúdo se necessário
        infoWindow.open(map, marker);

        // Atualizar a InfoWindow aberta
        infoWindowAberta = infoWindow;
      });

      globalMarkers.push(marker); // Adicionar o marcador ao array
    } else {
      console.error('Coordenadas inválidas:', posicaoVeiculo.LATITUDE, posicaoVeiculo.LONGITUDE);
    }
  });
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

function limparMarcadores() {
  if (globalMarkers && Array.isArray(globalMarkers)) {
    globalMarkers.forEach(function (marker) {
      marker.setMap(null);
    });

    globalMarkers = [];
  }
}


function fecharInfoWindowAberta() {
  if (infoWindowAberta) {
    infoWindowAberta.close();
    infoWindowAberta = null;
  }
}



var tabelaInfos = $('#tableInfos').DataTable({
  paging: false,
  info: false,
  stateSave: true, // Ativa a funcionalidade de salvar o estado
  "language": {
    "lengthMenu": "Display _MENU_ registros por páginas",
    "zeroRecords": "Nada encontrado - Desculpe",
    "info": "Pagina _PAGE_ de _PAGES_",
    "infoEmpty": "Sem registros",
    "infoFiltered": "(filtered from _MAX_ total records)",
    "search": "Filtrar resultados"
  },
  order: [[2, 'desc']]
});

function renderizarResultados(posicoesVeiculos) {
  // Limpar a DataTable
  tabelaInfos.clear();

  posicoesVeiculos.forEach(function (row) {
    var rowData = [];

    ['tipo_veiculo', 'ras_vei_placa_prefixo', 'VELOCIDADE', 'ignicao', 'DT_ULT_POSICAO', 'TEMP_PARADO_MIN', 'area', 'rua', 'quadra', 'lote'].forEach(function (campo) {
      // Adiciona a imagem para o campo 'tipo_veiculo'
      if (campo === 'tipo_veiculo') {
        var img = document.createElement('img');
        img.src = obterCaminhoImagem(row[campo], 'preto');
        img.alt = row[campo];
        img.style.filter = 'brightness(0)';
        img.style.width = '20px';

        var modeloSpan = document.createElement('span');
        modeloSpan.textContent = ' ' + row['modelo_veiculo'];

        rowData.push(img.outerHTML + modeloSpan.outerHTML);
      } else {
        // Adiciona outros campos diretamente
        rowData.push(row[campo]);
      }

    });

    tabelaInfos.row.add(rowData);
  });

  // Redesenhar a DataTable
  tabelaInfos.draw();
}




function obterCaminhoImagem(tipoVeiculo) {
  var iconeURL;

  switch (tipoVeiculo.toLowerCase()) {
    case 'picape':
      iconeURL = '../icon_maps/picape.png';
      break;
    case 'carro':
      iconeURL = '../icon_maps/carro.png';
      break;
    case 'motocicleta':
      iconeURL = '../icon_maps/motocicleta.png';
      break;
    case 'ônibus':
      iconeURL = '../icon_maps/onibus.png';
      break;
    case '-':
      iconeURL = '../icon_maps/default.png';
      break;
    default:
      iconeURL = null;
  }

  return iconeURL;
}


//função para desabilitar/habilitar grid

let gridInfos = document.getElementById('gridInfos');
let mapsVinculos = document.getElementById('mapsVinculos');
let ambos = document.getElementById('ambos');
let mapOnly = document.getElementById('mapOnly');
let gridOnly = document.getElementById('gridOnly');
let horaGrid = document.getElementById('horaGrid');
let horaPai = document.getElementById('horaPai');

function onChangeRadio() {
  if (ambos.checked) {
    gridInfos.style.maxHeight = '28%';
    mapsVinculos.style.display = 'block';
    gridInfos.style.display = 'block';
    mapsVinculos.style.height = '68%';
    horaGrid.style.display = 'none';
    horaPai.style.display = 'none';
    map.setZoom(15);
    map.setCenter({ lat: lat_central, lng: long_central });
  }
  if (mapOnly.checked) {
    mapsVinculos.style.display = 'block';
    gridInfos.style.display = 'none';
    mapsVinculos.style.height = '95%';
    horaGrid.style.display = 'none';
    horaPai.style.display = 'none';
    map.setZoom(15);
    map.setCenter({ lat: lat_central, lng: long_central });
  }
  if (gridOnly.checked) {
    horaGrid.style.display = 'block';
    horaPai.style.display = 'flex';
    gridInfos.style.display = 'block';
    mapsVinculos.style.display = 'none';
    gridInfos.style.maxHeight = '95%';
  }
}
ambos.addEventListener('change', onChangeRadio);
mapOnly.addEventListener('change', onChangeRadio);
gridOnly.addEventListener('change', onChangeRadio);


// Definindo a função handleChange
function handleChange(element) {
  if (element.checked) {
    let id = element.id;
    exibirMarcador(id);
  } else {
    let id = element.id;
    removerMarcador(id);
  }
}

function exibirMarcador(id_tipo_ponto) {
  const pontos = data.pontos[id_tipo_ponto];

  // Verifica se pontos é um objeto
  if (typeof pontos === 'object' && pontos !== null) {
    for (const key in pontos) {
      if (pontos.hasOwnProperty(key)) {
        const ponto = pontos[key];
        if (ponto.pontos && Array.isArray(ponto.pontos)) {
          const poligono = new google.maps.Polygon({
            paths: ponto.pontos.map(p => new google.maps.LatLng(p.latitude, p.longitude)),
            strokeColor: ponto.cor,
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: ponto.cor,
            fillOpacity: 0.15,
          });

          poligono.id_tipo_ponto = id_tipo_ponto;  // Adiciona o id_tipo_ponto ao polígono
          poligono.setMap(map);
          poligonosNoMapa.push(poligono);
        }
      }
    }

    // Lógica para marcadores de área
    for (const key in pontos) {
      if (pontos.hasOwnProperty(key)) {
        const ponto = pontos[key];
        const latitude = parseFloat(ponto.latitude);
        const longitude = parseFloat(ponto.longitude);

        if (!isNaN(latitude) && !isNaN(longitude)) {
          if (parseFloat(ponto.raio_ponto_mtrs) >= 0 && (parseFloat(ponto.id_tipo_marcacao) == 1 || parseFloat(ponto.id_tipo_marcacao) == 2)) {
            const marcadoresArea = new google.maps.Circle({
              center: { lat: latitude, lng: longitude },
              radius: parseFloat(ponto.raio_ponto_mtrs),
              map: map,
              title: ponto.nome_endereco,
              strokeColor: ponto.cor,
              strokeOpacity: 1,
              strokeWeight: 2,
              fillColor: ponto.cor,
              fillOpacity: 0.8,
            });

            marcadoresArea.id_tipo_ponto = id_tipo_ponto;  // Adiciona o id_tipo_ponto ao marcador de área
            const infoWindow = new google.maps.InfoWindow({
              content: ponto.nome_endereco,
            });

            marcadoresArea.addListener('click', function (event) {
              fecharInfoWindowAberta();

              infoWindow.setPosition(event.latLng);
              infoWindow.setContent(ponto.nome_endereco);
              infoWindow.open(map);

              infoWindowAberta = infoWindow;
            });

            marcadoresArea.setMap(map);
            pontosAreas.push(marcadoresArea);
          }
        }
      }
    }
  } else {
    console.log('Pontos não é um objeto válido');
  }
}



function removerMarcador(id_tipo_ponto) {
  // Remover polígonos do mapa
  poligonosNoMapa = poligonosNoMapa.filter(poligono => {
    if (poligono.id_tipo_ponto === id_tipo_ponto) {
      poligono.setMap(null);
      return false;  // Remove o polígono da lista
    }
    return true;  // Mantém o polígono na lista
  });

  // Remover marcadores de área do mapa
  pontosAreas = pontosAreas.filter(marcadorArea => {
    if (marcadorArea.id_tipo_ponto === id_tipo_ponto) {
      marcadorArea.setMap(null);
      return false;  // Remove o marcador de área da lista
    }
    return true;  // Mantém o marcador de área na lista
  });
}
