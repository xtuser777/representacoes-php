<?php if (!isset($_COOKIE['USER_ID']))  header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Autorizar Carregamento de Etapas de Pedidos de Frete</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Informações do Pedido</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="textDescricao">Descrição:</label>
                <input id="textDescricao" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-4">
                <label for="textDestino">Destino:</label>
                <input id="textDestino" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-2">
                <label for="textDistancia">Distância:</label>
                <div class="input-group">
                    <input type="number" id="textDistancia" class="form-control input-sm" style="width: 100%;" readonly />
                    <div class="input-group-addon">KM</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="textProprietario">Proprietário:</label>
                <input id="textProprietario" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-3">
                <label for="textCaminhao">Caminhão:</label>
                <input id="textCaminhao" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-3">
                <label for="textTipoCaminhao">Tipo Caminhão:</label>
                <input id="textTipoCaminhao" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-3">
                <label for="dateEntrega">Data Entrega:</label>
                <input type="date" id="dateEntrega" class="form-control input-sm" style="width: 100%;" readonly />
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Etapas de Carregamento do Pedido</div>

    <div class="fieldset-card-container">
        <div class="table-container" style="height: 170px;">
            <table id="tableEtapas" class="table table-striped table-hover">

                <thead>
                <tr>
                    <th>ORDEM</th>
                    <th>REPRESENTAÇÃO</th>
                    <th>CIDADE</th>
                    <th>CARGA (Kg)</th>
                    <th>STATUS</th>
                </tr>
                </thead>

                <tbody id="tbodyEtapas">
                </tbody>

            </table>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Etapa a ser autorizada</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-4">
                <label for="textRepresentacao">Representação:</label>
                <input id="textRepresentacao" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-4">
                <label for="textCidade">Cidade:</label>
                <input id="textCidade" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-2">
                <label for="textCarga">Carga:</label>
                <div class="input-group">
                    <input type="text" id="textCarga" class="form-control input-sm" style="width: 100%;" readonly />
                    <div class="input-group-addon">Kg</div>
                </div>
            </div>

            <div class="col-sm-2">
                <label for="buttonAutorizar">&nbsp;</label>
                <button id="buttonAutorizar" class="btn btn-success btn-sm" style="width: 100%;" onclick="autorizar();">AUTORIZAR</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <button id="button_voltar" class="btn btn-default" style="width: 100%;" onclick="voltar();">VOLTAR</button>
    </div>

    <div class="col-sm-10"></div>
</div>