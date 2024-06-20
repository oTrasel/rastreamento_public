<?php
session_start();
include('../manager/conexao.php');
if ($_SESSION['empresa'] == false) {

  //redireciona para a index.
  header('Location: ../index.php');
  session_destroy();
  exit();
}
//LOTES

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="cache-control" content="max-age=0" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="0" />
  <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
  <meta http-equiv="pragma" content="no-cache"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../bootstrap/icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../src/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="../src/select2-bootstrap-5-theme.min.css">
  <link rel="stylesheet" href="../src/select2.min.css">
  <link rel="stylesheet" href="../src/cadastro_vinculo_gps.css">
  <link rel="stylesheet" href="../src/home.css">
  <link rel="stylesheet" href="../src/cadastro_vinculo_gps.css">
  <script src="../js/frameworks/jquery-3.6.4.js"></script>
  <script src="../js/frameworks/popper.min.js"></script>
  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="shortcut icon" href="../images/favicon.ico" />

  <title>Empresa</title>
</head>

<body>
  <?php
  include('../layout.php');
  include('../manager/auxiliares/busca_rastreamento_ativo.php');
  include('../manager/auxiliares/busca_infos_vinculos.php')
  ?>



  <!--INICIO CONFIGURAÇÃO MAPEAMENTO-->
  <div id="container">
    <div id="children">
      <div class="buttonsMenu">
        <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;" id="acoes">
          <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">Ações</h6>

          <button type="button" class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#mapearRastreamento">Iniciar novo Rastreamento</button>
          <?php
          if ($vinculo_count !== 0) {
          ?>
            <button type="button" class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#rastreamentoAtivo">Verificar Rastreamentos Ativos</button>


        </section>
        <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;" id="sectionEndereco">
          <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">Informações do Condutor</h6>
          <div id="tableLotes">
            <table class="table table-striped" id="tableEndereco" style="width: 100%;">

              <thead class="thead-dark">
                <tr>
                  <th scope="col" id="tableHead" style="width: 33%;">Placa</th>
                  <th scope="col" id="tableHead" style="width: 33%;">Condutor</th>
                  <th scope="col" id="tableHead" style="width: 33%;">Contato</th>

                </tr>
              </thead>
              <tbody id="conteudo">
              </tbody>
            </table>
          </div>
        </section>
      <?php
          }
      ?>
      </div>
      <?php
      if ($vinculo_count !== 0) {
      ?>
        <div class="mapRastreamentos">
          <div class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative; height: 600px" id="mapsVinculos"></div>

          <section class="mt-3 pb-1 pe-1 ps-1 pt-3" style="border: solid grey 1px; border-radius: 10px; position: relative;" id="sectionMonitoramento">
            <div id="tableMonitoramento">
              <table class="table table-striped" id="tableInfos">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col" id="tableHead">Posição</th>
                    <th scope="col" id="tableHead">ID</th>
                    <th scope="col" id="tableHead">Placa</th>
                    <th scope="col" id="tableHead">GPS</th>
                    <th scope="col" id="tableHead">QTD Destinos</th>
                    <th scope="col" id="tableHead">Quadra</th>
                    <th scope="col" id="tableHead">Rua</th>
                    <th scope="col" id="tableHead">Data Entrada</th>
                    <th scope="col" id="tableHead">KM/h</th>
                    <th scope="col" id="tableHead">Ultima Posição</th>
                  </tr>
                </thead>
                <tbody id="conteudo">
                </tbody>
              </table>
            </div>
            <h6 style="color: white; position: absolute; top: -10px; background-color: grey; padding: 0 10px; border-radius: 10px;">Monitoramentos</h6>

          </section>

        </div>
      <?php
      }
      ?>


    </div><!--fim div content-->
  </div><!--fim div container-->



  <!-- MODAIS -->

  <!-- Modal VINCULAR-->
  <div class="modal fade" id="mapearRastreamento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mapearRastreamento" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Selecionar Dados</h5>
        </div>
        <div class="modal-body">
          <form action="../manager/cadastros/processa_vinculo_gps.php" method="post" id="vinculoGPS">


            <div class="form-floating mt-2" id="GPS">
              <select class="form-select mt-2" name="selectGPS" id="gpsSelect">
                <option id="semGPS" value="semGPS" selected>Escolha um Dispositivo GPS</option>
                <?php

                foreach ($gps as $rastreador) {
                  echo "<option id='" . $rastreador['id_gps'] . "|" . $rastreador['descr_gps'] . "' value='" . $rastreador['id_gps'] . "|" . $rastreador['descr_gps'] . "'>" . $rastreador['ras_vei_equipamento'] . ' - ' . $rastreador['descr_gps'] . "</option>";
                }
                ?>
              </select>
            </div>

            <div class="form-floating mt-2" id="condutor">
              <div class="form-floating mt-2" style="color: gray;">
                <input type="text" class="form-control" id="novoCondutor" placeholder="Nome do Condutor" name="condutorNome" maxlength="150" required>
                <label for="novoCondutor">Nome do Condutor</label>
              </div>
            </div>
            <div class="form-floating mt-2" id="contato">
              <div class="form-floating mt-2" style="color: gray;">
                <input type="text" class="form-control" id="contatoCondutor" placeholder="Contato do Condutor" name="condutorContato" maxlength="15" pattern="\(\d{2}\)\s*\d{5}-\d{4}" required>
                <label for="contatoCondutor">Contato do Condutor</label>
              </div>
            </div>
            <div class="form-floating mt-2" id="PLACA">
              <div class="form-floating mt-2" style="color: gray;">
                <input type="text" class="form-control" id="novaPlaca" placeholder="Cadastrar nova Placa" name="cadastroPlaca" maxlength="7" oninput="this.value = this.value.toUpperCase()">
                <label for="novaPlaca">Digite a Placa</label>
              </div>
            </div>

            <div class="form-floating mt-2" id="LOTE">
              <select class="form-select" name="selectLote[]" id="loteSelect" multiple>
                <?php
                if ($lotes_count != 0) {
                  foreach ($lotes as $end) {
                    echo "<option id='" . $end['id'] . "|" . $end['NOME_LOTE'] . "' value='" . $end['id'] . "'>" . $end['quadra'] . "</option>";
                  }
                }
                ?>
              </select>
            </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmarMapa" id="confirmarDados" disabled>Confirmar Dados</button>
        </div>
      </div>
    </div>
  </div>




  <!-- Modal  CONFIRMAR DESTINO-->
  <div class="modal fade" id="confirmarMapa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Confirmar Destino</h5>
        </div>
        <div class="modal-body">
          <div class="mt-2" id="confereMapa" style="height: 400px; width: 100%; border-radius: 10px;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#mapearRastreamento">Cancelar</button>
          <button type="submit" id="vinculoBT" class="btn btn-primary">Iniciar Rastreamento</button>
        </div>
        </form> <!--FINAL FORMULARIO DE CADASTRO DE VINCULO-->
      </div>
    </div>
  </div>



  <!-- MODAL DESVINCULAR -->


  <div class="modal fade" id="rastreamentoAtivo" tabindex="-1" aria-labelledby="rastreamentoAtivo" aria-hidden="true" data-bs-theme="dark" style="color: white;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="rastreamentoAtivo">Rastreamentos Ativos</h1>
        </div>
        <div class="modal-body">
          <div id="tableVinculos">
            <table class="table table-striped" id="tableDesvinculo">
              <thead class="thead-dark">
                <tr>
                  <th scope="col" id="tableHead">Desvincular</th>
                  <th scope="col" id="tableHead">ID</th>
                  <th scope="col" id="tableHead">Placa</th>
                  <th scope="col" id="tableHead">GPS</th>
                  <th scope="col" id="tableHead">QTD Destinos</th>
                  <th scope="col" id="tableHead">Quadra</th>
                  <th scope="col" id="tableHead">Rua</th>
                  <th scope="col" id="tableHead">Data Entrada</th>
                  <th scope="col" id="tableHead">Ultima Posição</th>
                </tr>
              </thead>
              <tbody id="conteudo">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>



  <!-- modal confirmar desvinculo -->
  <div class="modal fade" id="confirmaFim" tabindex="-1" aria-labelledby="confirmaFim" aria-hidden="true" data-bs-theme="dark" style="color: white;">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="confirmaFim">Finalizar Rastreamento</h1>
        </div>
        <div class="modal-body">
          <h1 class="modal-title fs-5" id="msgDesvinculo"></h1>
        </div>
        <div class="modal-footer">
          <form action="../manager/desvinculo_gps.php" method="post" id="desvinculoForm">
            <input type="text" name="idDesvinculo" id="id_desvinculo_input" value="" hidden>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="desvinculoBT">Finalizar Rastreamento</button>
          </form>
        </div>
      </div>
    </div>
  </div>




  <!-- modal verificar entradas -->

  <div class="modal fade" id="entradasEndereco" tabindex="-1" aria-labelledby="entradasEndereco" aria-hidden="true" data-bs-theme="dark" style="color: white;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="entradasEndereco">Rastreamentos Ativos</h1>
        </div>
        <div class="modal-body">
          <div id="tableEntradas">
            <table class="table table-striped" id="entradaEnderecos">
              <thead class="thead-dark">
                <tr>
                  <th scope="col" id="tableHead">Placa</th>
                  <th scope="col" id="tableHead">GPS</th>
                  <th scope="col" id="tableHead">Lote</th>
                  <th scope="col" id="tableHead">Entrada No Lote</th>
                  <th scope="col" id="tableHead">Saída Do Lote</th>
                </tr>
              </thead>
              <tbody id="conteudo">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>


 
  <script src="../js/frameworks/jquery.dataTables.min.js"></script>
  <script src="../js/frameworks/dataTables.bootstrap5.min.js"></script>
  <script src="../js/frameworks/select2.min.js"></script>
  <script src="../js/frameworks/html5shiv_3.7.3.min.js"></script>
  <script src="../js/paginas/administracao/desvinculo_gps.js"></script>
  <script src="../js/paginas/administracao/confere_local_gps.js"></script>
  <script src="../js/paginas/administracao/cadastro_vinculo_gps.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=&libraries=drawing&callback=initMap" async defer></script>
  <script>
  const tel = document.getElementById('contatoCondutor'); // Seletor do campo de telefone

  tel.addEventListener('blur', (e) => mascaraTelefone(e.target.value)); // Dispara quando o campo perde o foco

  const mascaraTelefone = (valor) => {
    valor = valor.replace(/\D/g, ""); // Remove caracteres não numéricos
    valor = valor.padEnd(11, '9'); // Preenche o número com 9's se não estiver completo
    valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
    valor = valor.replace(/(\d)(\d{4})$/, "$1-$2");
    tel.value = valor; // Insere o(s) valor(es) no campo
  };
</script>

</body>



</html>