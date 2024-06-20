let drawnShape; // Define drawnShape globalmente
let drawingManager; // Define drawingManager globalmente

function limpaMapa() {
  if (drawnShape) {
    // Remove o círculo, se existir
    if (drawnShape.circle) {
      drawnShape.circle.setMap(null);
      delete drawnShape.circle;
    }

    drawnShape.setMap(null);
    drawnShape = null;
  }
}


function initMap() {

  requisitaLocalServico();
  
  requisitaLocalServico().then(responseData => {
    // Extrai as coordenadas do local do serviço da resposta
    const lat_central = parseFloat(responseData[0]['lat_central']);
    const long_central = parseFloat(responseData[0]['long_central']);
    
    // Cria o mapa usando as coordenadas do local do serviço
    const map = new google.maps.Map(document.getElementById('map'), {
      center: { lat: lat_central, lng: long_central },
      zoom: 15,
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
    });

    // Inicializa o desenhador de formas no mapa
    drawingManager = initDrawing(map, 'polygon');
    const radioPoligono = document.getElementById('btnradioPoligono');
  const radioPonto = document.getElementById('btnradioPonto');

  // Adiciona listeners para os botões de rádio
  radioPoligono.addEventListener('change', function () {
    limpaMapa();
    if (drawingManager) {
      drawingManager.setMap(null);
      drawingManager = null;
    }
    drawingManager = initDrawing(map, 'polygon');
  });

  radioPonto.addEventListener('change', function () {
    limpaMapa();
    if (drawingManager) {
      drawingManager.setMap(null);
      drawingManager = null;
    }
    drawingManager = initDrawing(map, 'marker');
  });

  const deleteButton = document.getElementById('removePoligono');
  deleteButton.addEventListener('click', deleteShape);

  const cancelButton = document.getElementById('cancelar');
  cancelButton.addEventListener('click', deleteShape);

  const nomeInput = document.getElementById('nome');
  const enderecoInput = document.getElementById('endereco');
  const abrirMapaButton = document.getElementById('abrirMapa');
  const cadastrarShapeButton = document.getElementById('cadastrarPoligono');

  nomeInput.addEventListener('input', checkInputs);
  enderecoInput.addEventListener('input', checkInputs);

  function checkInputs() {
    // Verifica se ambos os campos estão preenchidos
    const nomePreenchido = nomeInput.value.trim() !== '';
    const enderecoPreenchido = enderecoInput.value.trim() !== '';

    // Habilita ou desabilita o botão com base nos campos preenchidos e na existência do shape desenhado
    abrirMapaButton.disabled = !(nomePreenchido && enderecoPreenchido);
    cadastrarShapeButton.disabled = !drawnShape;
  }

  function initDrawing(map, mode) {
    limpaMapa(); // Limpa configurações anteriores ao iniciar um novo modo
    const manager = new google.maps.drawing.DrawingManager({
      map: map,
      drawingControl: true,
      drawingControlOptions: {
        position: google.maps.ControlPosition.TOP_LEFT,
        drawingModes: [mode],
      },
      polygonOptions: {
        fillColor: '#F0E68C',
        editable: true,
      },
      markerOptions: {
        // Adicione opções específicas para marcadores, se necessário
      },
      suppressInfoWindows: true,
    });

    google.maps.event.addListener(manager, 'overlaycomplete', function (event) {
      limpaMapa();

      if (mode === 'polygon' && event.type === 'polygon') {
        drawnShape = event.overlay;
        const formattedCoordinates = getFormattedShapeCoordinates(drawnShape);

        // Converta as coordenadas formatadas em uma string JSON
        const jsonString = JSON.stringify(formattedCoordinates);

        // Atribua a string JSON ao valor do input
        const inputCoordenadas = document.getElementById('coordenadas');
        inputCoordenadas.value = jsonString;
      } else if (mode === 'marker' && event.type === 'marker') {
        drawnShape = event.overlay;

        // Remove o marcador anterior, se existir
        if (drawnShape.marker) {
          drawnShape.marker.setMap(null);
          delete drawnShape.marker;
        }

        // Remove o círculo anterior, se existir
        if (drawnShape.circle) {
          drawnShape.circle.setMap(null);
          delete drawnShape.circle;
        }

        // Acessa o valor do input com o ID "raio"
        const raioInput = document.getElementById('raio');
        const raio = parseFloat(raioInput.value);

        // Cria um círculo ao redor do marcador com o raio fornecido
        const circle = new google.maps.Circle({
          map: map,
          center: drawnShape.getPosition(),
          radius: raio,
          fillColor: '#F0E68C',
          editable: false, // Torna o círculo não editável
        });

        // Adiciona o círculo ao drawnShape para que ele também seja removido se necessário
        drawnShape.circle = circle;

        const formattedCoordinates = getFormattedShapeCoordinates(drawnShape);

        // Converta as coordenadas formatadas em uma string JSON
        const jsonString = JSON.stringify(formattedCoordinates);

        // Atribua a string JSON ao valor do input
        const inputCoordenadas = document.getElementById('coordenadas');
        inputCoordenadas.value = jsonString;

      }

      // Chama checkInputs para atualizar o estado do botão cadastrarShape
      checkInputs();
    });

    return manager;
  }


  function deleteShape() {
    if (drawnShape) {
      // Remove o círculo, se existir
      if (drawnShape.circle) {
        drawnShape.circle.setMap(null);
        delete drawnShape.circle;
      }

      drawnShape.setMap(null);
      drawnShape = null;

      checkInputs();
    }
  }


  function getFormattedShapeCoordinates(shape) {
    let coordinates = [];
  
    if (shape instanceof google.maps.Polygon || shape instanceof google.maps.Marker) {
      if (shape instanceof google.maps.Polygon) {
        coordinates = shape.getPath().getArray();
        // Adiciona o ponto de fechamento (última coordenada igual à primeira)
        coordinates.push(coordinates[0]);
      } else if (shape instanceof google.maps.Marker) {
        coordinates = [shape.getPosition()];
      }
  
      const formattedCoordinates = coordinates.map(coord => {
        return { lat: coord.lat(), lng: coord.lng() };
      });
  
      return formattedCoordinates;
    }
  
    return null; // Retornar null se o tipo de forma não for suportado
  }
    

  }).catch(error => {
    console.error(error);
  });
  
}

function requisitaLocalServico() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: '../manager/auxiliares/busca_local_trabalho.php',
      method: 'GET',
      dataType: 'json',
      success: function (responseData) {
        if (responseData) {
          resolve(responseData); // Resolve a promessa com os dados recebidos
        } else {
          reject('Resposta do servidor não é um objeto válido ou não possui a estrutura esperada.');
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        reject('Erro na requisição:' + textStatus + ' ' + errorThrown);
      }
    });
  });
}


/////////////////////////////////////////////////////////
//APENAS PROCESSAMENTO E AJAX E OUTRAS FUNÇÕES AUXILIARES
/////////////////////////////////////////////////////////




$(document).ready(function () {
  $('#cadastro_local_especial').submit(function (event) {
    event.preventDefault();

    // Desabilita o botão de login e atualiza o texto e o estilo
    var cadastrarPoligono = $('#cadastrarPoligono');
    cadastrarPoligono.prop('disabled', true);
    cadastrarPoligono.html(`
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
          alert('Endereço cadastrado com Sucesso!');

          // Limpar os dados do formulário
          $('#cadastro_local_especial')[0].reset();
          // Ocultar o modal
          $('#modalMapaCadastro').modal('hide');
          // Excluir o polígono
          limpaMapa();
          document.getElementById('abrirMapa').disabled = true;
          location.reload();

        } else {
          alert('Houve um erro ao cadastrar endereço, por favor, tente novamente!');
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastrarPoligono.prop('disabled', false);
        cadastrarPoligono.html('Cadastrar Endereço');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao cadastrar endereço, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        cadastrarPoligono.prop('disabled', false);
        cadastrarPoligono.html('Cadastrar Endereço');
      }
    });
  });
});


document.addEventListener('DOMContentLoaded', function () {
  // Obtém referências aos elementos
  var radioPoligono = document.getElementById('btnradioPoligono');
  var radioPonto = document.getElementById('btnradioPonto');
  var raioContainer = document.getElementById('raioContainer');
  var raioInput = document.getElementById('raio');

  // Adiciona um ouvinte de evento de mudança aos rádios
  radioPoligono.addEventListener('change', toggleRaioContainer);
  radioPonto.addEventListener('change', toggleRaioContainer);

  // Inicialmente, verifica o estado dos rádios para ocultar ou exibir o contêiner do raio
  toggleRaioContainer();

  function toggleRaioContainer() {
    // Se o rádio Ponto estiver marcado, exibe o contêiner do raio, caso contrário, oculta
    raioContainer.style.display = radioPonto.checked ? 'block' : 'none';

    // Limpa o valor do input do raio se estiver oculto
    if (raioContainer.style.display === 'none') {
      raioInput.value = '';
    }
  }
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
  order: [[6, 'desc']],
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
          alert('Informações atualizadas com Sucesso!');
          location.reload();

        } else {
          alert('Houve um erro ao atualizar as informações, por favor, tente novamente!');
          location.reload();
        }

        // Reabilita o botão de login e restaura o texto e o estilo originais
        confirmarEdicao.prop('disabled', false);
        confirmarEdicao.html('Confirmar Edição');
      },
      error: function (xhr, status, error) {
        alert('Houve um erro ao atualizar as informações, por favor, tente novamente!');

        // Reabilita o botão de login e restaura o texto e o estilo originais
        confirmarEdicao.prop('disabled', false);
        confirmarEdicao.html('Confirmar Edição');
      }
    });
  });
});