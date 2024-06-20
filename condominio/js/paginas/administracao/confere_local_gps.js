
$(document).ready(function () {
    function carregarDadosEExibirMapa() {
        var selectLote = $("#loteSelect").val();

        $.ajax({
            url: "../manager/auxiliares/busca_infos_confirmar_vinculo.php",
            type: "POST",
            data: {
                selectLote: selectLote
            },
            success: function (response) {
                var lotesCoordenadas = JSON.parse(response);
                exibirPoligono(lotesCoordenadas);
            },
            error: function (error) {
                console.log("Erro na solicitação AJAX:", error);
            }
        });
    }

    $("#confirmarDados").click(function () {
        carregarDadosEExibirMapa();
    });
});


function initMap() {
    exibirMapaVinculo();
}

var mapa;
var poligonosNoMapa = [];

function inicializarMapa() {

    var mapOptions = {
        center: { lat: -16.612511, lng: -49.187390 },
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.HYBRID,
        streetViewControl: false,
        mapTypeControl: false,
        fullscreenControl: true,
        styles: [
            { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
            { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }
        ],
        gestureHandling: 'greedy'
    };

    mapa = new google.maps.Map(document.getElementById('confereMapa'), mapOptions);

    mapa.addListener('click', function (event) {
        mapa.setOptions({
            gestureHandling: 'greedy'
        });
    });
}


function exibirPoligono(lotesCoordenadas) {
    if (!mapa) {
        inicializarMapa();
    }

    // Array para armazenar as InfoWindows
    let infoWindows = [];

    if (lotesCoordenadas && typeof lotesCoordenadas === 'object') {
        // Limpe os polígonos existentes no mapa antes de adicionar novos
        poligonosNoMapa.forEach(function (poligono) {
            poligono.setMap(null);
        });
        poligonosNoMapa = [];

        // Feche as InfoWindows existentes
        infoWindows.forEach(function (infoWindow) {
            infoWindow.close();
        });
        infoWindows = []; // Limpe a lista de InfoWindows

        Object.keys(lotesCoordenadas).forEach(function (chave) {
            const poligonoData = lotesCoordenadas[chave];

            if (poligonoData.pontos && Array.isArray(poligonoData.pontos)) {
                const pontos = poligonoData.pontos.map(p => new google.maps.LatLng(p.LATITUDE, p.LONGITUDE));

                // Crie o polígono usando as coordenadas fornecidas
                const poligono = new google.maps.Polygon({
                    paths: pontos,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.15,
                });

                // Adicione o polígono ao mapa e à lista de polígonos
                poligono.setMap(mapa);
                poligonosNoMapa.push(poligono);

                // Calcula o centro do polígono
                const centroPoligono = pontos.reduce((acc, ponto) => {
                    acc.lat += ponto.lat();
                    acc.lng += ponto.lng();
                    return acc;
                }, { lat: 0, lng: 0 });
                centroPoligono.lat /= pontos.length;
                centroPoligono.lng /= pontos.length;

                // Crie uma InfoWindow para exibir os dados do polígono
                const infoWindow = new google.maps.InfoWindow({
                    content: chave,
                });

                // Abra a InfoWindow no centro do polígono
                infoWindow.setPosition(centroPoligono);
                infoWindow.open(mapa);

                infoWindows.push(infoWindow);

                // Adicione um listener de clique para a abertura da InfoWindow
                poligono.addListener('click', function (event) {
                    infoWindow.open(mapa, event.latLng);
                });
            }
        });
    }

    $('#modalMapa').modal('show');

    $('#modalMapa').on('shown.bs.modal', function () {
        if (mapa) {
            var bounds = new google.maps.LatLngBounds();
            pontosCopy.forEach(function (ponto) {
                bounds.extend(ponto);
            });

            if (mapa.getZoom() < mapOptions.zoom) {
                mapa.fitBounds(bounds);
            }
        }
    });
}



/////////////// DAQUI PRA CIMA, EXIBE O MAPA NA CONFIRMAÇÃO DE CADASTRO DE VINCULO

/////////////// DAQUI PRA BAIXO, EXIBE O MAPA CASO TENHA VINCULO ATIVO
// Definição global da variável tableEndereco
var tableEndereco;

//renderiza tabela nome lotes
$(document).ready(function () {
    // Inicialização da DataTable
    tableEndereco = $('#tableEndereco').DataTable({
        dom: '<"pull-left"f><"pull-right"l>tip',
        paging: false,
        info: false,
        language: {
            lengthMenu: "Display _MENU_ registros por páginas",
            zeroRecords: "Nada encontrado - Desculpe",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "Sem registros",
            infoFiltered: "(filtered from _MAX_ total records)",
            search: "Filtrar resultados"
        }
    });

    // Adicionando a classe personalizada ao campo de pesquisa
    $('.dataTables_filter input').addClass('custom-search-class');

    // Função para renderizar resultados
    // function renderizarResultados(responseData) {
    //     // Limpar a DataTable
    //     tableEndereco.clear();

    //     responseData.forEach(function (row) {
    //         var rowData = [];

    //         ['descr_placa', 'descr_gps', 'descr_lote'].forEach(function (campo) {
    //             rowData.push(row[campo]);
    //         });

    //         tableEndereco.row.add(rowData);
    //     });

    //     // Redesenhar a DataTable
    //     tableEndereco.draw();
    // }
});

//ajax requisitante nome dos lotes
function requisitaCondutor() {
    $.ajax({
        url: '../manager/auxiliares/busca_dados_condutor.php',
        method: 'GET',
        dataType: 'json',
        success: function (responseData) {
            if (responseData) {
                data = responseData;
                renderizarResultados(responseData);
            } else {
                console.error('Resposta do servidor não é um objeto válido ou não possui a estrutura esperada.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Erro na requisição:', textStatus, errorThrown);
        }
    });
}

function requisitaNomeLotes() {
    $.ajax({
        url: '../manager/auxiliares/busca_nome_lotes.php',
        method: 'GET',
        dataType: 'json',
        success: function (responseData) {
            if (responseData) {
                data = responseData;
                $(document).on('click', '.btn-primary', function() {
                    // Obter o ID do botão clicado
                    var id = $(this).attr('data-id');
                    
                    // Filtrar os dados para encontrar apenas os itens com o ID correspondente
                    var dadosFiltrados = responseData.filter(function(row) {
                        return row.id_vinculo == id;
                    });
                
                    // Renderizar os itens filtrados na tabela
                    renderizaEntradas(dadosFiltrados);
                });
            } else {
                console.error('Resposta do servidor não é um objeto válido ou não possui a estrutura esperada.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Erro na requisição:', textStatus, errorThrown);
        }
    });
}


//renderiza tabela geral
function renderizarResultados(responseData) {
    // Limpar a DataTable
    tableEndereco.clear();

    responseData.forEach(function (row) {
        var rowData = [];

        ['descr_placa', 'condutor', 'contato_condutor'].forEach(function (campo) {

            rowData.push(row[campo]);

        });

        tableEndereco.row.add(rowData);
    });

    // Redesenhar a DataTable
    tableEndereco.draw();
}

// Função para renderizar as entradas na tabela
function renderizaEntradas(responseData) {
    // Limpar a DataTable
    tableEntradasEnderecos.clear();

    responseData.forEach(function(row) {
        var rowData = [];

        ['descr_placa', 'descr_gps', 'descr_lote', 'dt_entrada', 'dt_saida'].forEach(function(campo) {
            rowData.push(row[campo]);
        });

        tableEntradasEnderecos.row.add(rowData);
    });

    // Redesenhar a DataTable
    tableEntradasEnderecos.draw();
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

var tableEntradasEnderecos = $('#entradaEnderecos').DataTable({
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
    order: [[3, 'desc']]
});


var tableDesvinculo = $('#tableDesvinculo').DataTable({
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

//ajax requisitante informacoes gerais
function requisitaTotal() {
    $.ajax({
        url: '../manager/auxiliares/busca_info_geral.php',
        method: 'GET',
        dataType: 'json',
        success: function (responseData) {
            if (responseData) {
                data = responseData;
                renderizaTotal(data);
                renderizaDesnvinculo(data)
            } else {
                console.error('Resposta do servidor não é um objeto válido ou não possui a estrutura esperada.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Erro na requisição:', textStatus, errorThrown);
        }
    });
}



function renderizaTotal(responseData) {
    // Limpar a DataTable
    tabelaInfos.clear();

    responseData.forEach(function (row) {
        var rowData = [];

        // Adiciona o botão na primeira coluna
        var botao = '<button type="button" class="btn btn-primary w-100 h-100 btn-verificar" id="' + row['id_vinculo'] + '"> <i class="bi bi-search"></i></button>';
        
        // Verifica se o botão está selecionado
        if ($('#' + row['id_vinculo']).hasClass('btn-selecionado')) {
            botao = '<button type="button" class="btn btn-primary w-100 h-100 btn-verificar btn-selecionado" id="' + row['id_vinculo'] + '"> <i class="bi bi-search"></i></button>';
        }
        var botaoEnderecos = '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#entradasEndereco" data-id="' + row['id_vinculo'] + '" id="' + row['id_vinculo'] + '">'+ row['id_vinculo'] +' </button>'

        rowData.push(botao);
        rowData.push(botaoEnderecos);

        [ 'descr_placa', 'descr_gps', 'qtd_destinos', 'quadra', 'rua', 'data', 'velocidade', 'ultima_posicao'].forEach(function (campo) {
            rowData.push(row[campo]);
        });

        tabelaInfos.row.add(rowData);
    });

    // Redesenhar a DataTable
    tabelaInfos.draw();
}


function renderizaDesnvinculo(responseData) {
    // Limpar a DataTable
    tableDesvinculo.clear();

    responseData.forEach(function (row) {
        var rowData = [];

        // Adiciona o botão na primeira coluna
        var botao = '<button type="button" class="btn btn-outline-danger w-100 h-100 btn-desvinculo" data-bs-toggle="modal" data-bs-target="#confirmaFim" data-id="' + row['id_vinculo'] + '" id="' + row['id_vinculo'] + '"> <i class="bi bi-trash-fill"></i></button>';
        
        // Verifica se o botão está selecionado
        rowData.push(botao);

        ['id_vinculo', 'descr_placa', 'descr_gps', 'qtd_destinos', 'quadra', 'rua', 'data', 'ultima_posicao'].forEach(function (campo) {
            rowData.push(row[campo]);
        });

        tableDesvinculo.row.add(rowData);
    });

    // Redesenhar a DataTable
    tableDesvinculo.draw();

    // Adicionar eventos de clique aos botões
    adicionarEventoDesvinculo();   
}

function adicionarEventoDesvinculo() {
    $('.btn-desvinculo').click(function() {
        // Obtém o valor do atributo data-id do botão clicado
        var idDesvinculo = $(this).data('id');
        var textoDesvinculo = $('#msgDesvinculo');
        $('#id_desvinculo_input').val(idDesvinculo);
        textoDesvinculo.text("Confirmar Fim do Rastreamento ID: " + idDesvinculo);
    });
}





let hora;
function atualizarHora() {
    const agora = new Date();
    const dia = agora.getDate().toString().padStart(2, '0');
    const mes = (agora.getMonth() + 1).toString().padStart(2, '0');
    const ano = agora.getFullYear();
    const hora = agora.getHours().toString().padStart(2, '0');
    const minuto = agora.getMinutes().toString().padStart(2, '0');
    const segundo = agora.getSeconds().toString().padStart(2, '0');
  
    const horaAtualizada = `${dia}/${mes} ${hora}:${minuto}:${segundo}`;
    horaAtualElement.innerText = (horaAtualizada).toUpperCase();
  }

function exibirMapaVinculo() {


    const map = new google.maps.Map(document.getElementById('mapsVinculos'), {
        center: { lat: -16.612511, lng: -49.187390 },
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.HYBRID,
        streetViewControl: false,
        mapTypeControl: false,
        fullscreenControl: false,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            },
            {
                featureType: 'poi',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });

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
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(horaAtualElement);


    const markersArray = [];

    function limparMarcadores() {
        // Loop through markers and set the map to null to remove them
        markersArray.forEach(marker => marker.setMap(null));
        // Clear the markers array
        markersArray.length = 0;
    }

    function adicionarMarcadores(pontos) {
        limparMarcadores(); // Clear existing markers

        pontos.forEach(ponto => {
            const latitude = parseFloat(ponto.LATITUDE);
            const longitude = parseFloat(ponto.LONGITUDE);

            if (!isNaN(latitude) && !isNaN(longitude)) {
                const identificadorUnico = ponto.ras_vei_placa;
                const corDoMarcador = ponto.cor;

                if (/^#[0-9A-F]{6}$/i.test(corDoMarcador)) {
                    const marker = new google.maps.Marker({
                        position: { lat: latitude, lng: longitude },
                        map: map,
                        title: ponto.ras_vei_placa,
                        icon: criarIconeCor(corDoMarcador),
                    });

                    const infoWindow = new google.maps.InfoWindow({
                        content: ponto.ras_vei_placa,
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

                    markersArray.push(marker); // Add the marker to the array
                } else {
                    console.error('Sem pontos para exibir');
                }
            } else {
                console.error('Sem pontos para exibir');
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


    function atualizarMapa() {
        atualizarHora();
        requisitaTotal();
        requisitaNomeLotes();
        requisitaCondutor();
        
        $.ajax({
            url: '../manager/auxiliares/busca_localizacao_gps.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if ('gps' in data) {
                    adicionarMarcadores(data.gps);

                    if ('poligono' in data.gps) {
                        adicionarPoligonos(data.gps.poligono);
                    }
                } else {
                    console.error('Dados ausentes ou em formato incorreto.');
                }
            },
            error: function (xhr, status, error) {
            }
        });
    }

    //adiciona poligonos

    $(document).ready(function () {
        let botaoSelecionado = null;
        let zoomOriginal = 15; // Salvar o nível de zoom original

        $(document).on('click', '.btn-verificar', function () {
            const idVinculo = $(this).attr('id');

            if (botaoSelecionado === idVinculo) {
                removerPoligonosDoMapa();
                botaoSelecionado = null;
            } else {
                $('.btn-verificar').removeClass('btn-selecionado');
                removerPoligonosDoMapa();
                botaoSelecionado = idVinculo;
                $(this).addClass('btn-selecionado');
                exibirPoligonosDoVinculo(idVinculo);
            }
        });

        function adicionarPoligonos(poligonos) {
            for (const key in poligonos) {
                if (poligonos.hasOwnProperty(key)) {
                    const poligono = poligonos[key];

                    const pontosPoligono = poligono.pontos.map(ponto => {
                        return {
                            lat: parseFloat(ponto.latitude),
                            lng: parseFloat(ponto.longitude),
                            cor: poligono.cor,
                            title: poligono.descr_lote,
                        };
                    });

                    if (pontosPoligono.length >= 3) {
                        const novoPoligono = new google.maps.Polygon({
                            paths: pontosPoligono.map(ponto => ({ lat: ponto.lat, lng: ponto.lng })),
                            strokeColor: pontosPoligono[0].cor,
                            strokeOpacity: 1,
                            strokeWeight: 2,
                            fillColor: pontosPoligono[0].cor,
                            fillOpacity: 0.9,
                        });

                        // Adicionar eventos de mouseover e mouseout para o novo polígono
                        adicionarEventosMouseoverMouseout(novoPoligono, pontosPoligono[0].title);

                        novoPoligono.setMap(map);
                        poligonosArray.push(novoPoligono);

                        ajustarZoomParaPoligono(novoPoligono);
                    } else {
                        console.error('O polígono deve ter pelo menos 3 pontos.');
                    }
                }
            }
        }

        function adicionarEventosMouseoverMouseout(poligono, title) {
            google.maps.event.addListener(poligono, 'mouseover', function (event) {
                infoWindow.setContent(title);
                infoWindow.setPosition(event.latLng);
                infoWindow.open(map);
            });

            google.maps.event.addListener(poligono, 'mouseout', function () {
                infoWindow.close();
            });
        }

        const poligonosArray = [];

        const infoWindow = new google.maps.InfoWindow();

        function exibirPoligonosDoVinculo(idVinculo) {
            $.ajax({
                url: '../manager/auxiliares/busca_localizacao_gps.php',
                method: 'GET',
                data: { id_vinculo: idVinculo },
                dataType: 'json',
                success: function (data) {
                    if ('gps' in data) {
                        const poligonos = data.gps.find(item => item.id_vinculo === idVinculo)?.poligono;
                        if (poligonos) {
                            adicionarPoligonos(poligonos);
                        } else {
                            console.error('Polígonos ausentes ou em formato incorreto:', data);
                        }
                    } else {
                        console.error('Chave "gps" ausente ou em formato incorreto:', data);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Erro na chamada AJAX:', status, error);
                }
            });
        }


        function removerPoligonosDoMapa() {
            // Verificar se há polígonos para remover
            if (poligonosArray.length >= 0) {
                // Obter a coordenada desejada para o centro do mapa
                const centroMapa = { lat: -16.612511, lng: -49.187390 };
                infoWindow.close();
                infoWindow.setContent('');
                // Remover os polígonos do mapa
                poligonosArray.forEach(poligono => poligono.setMap(null));
                poligonosArray.length = 0;

                // Definir o centro do mapa para a coordenada desejada
                map.setCenter(centroMapa);

                // Resetar o zoom para o nível original
                map.setZoom(zoomOriginal);

                // Remover a classe 'btn-selecionado' de todos os botões
                $('.btn-verificar').removeClass('btn-selecionado');
            } else {
                console.warn('Nenhum polígono para remover.');
            }
        }

        function ajustarZoomParaPoligono(poligono) {
            const bounds = new google.maps.LatLngBounds();
            poligono.getPath().forEach(function (latLng) {
                bounds.extend(latLng);
            });


            map.setZoom(15);

            const novoZoom = map.getZoom();

            if (novoZoom < zoomOriginal) {
                zoomOriginal = novoZoom;
            }
        }

    });





    setInterval(atualizarMapa, 30000);


    atualizarMapa();
}



