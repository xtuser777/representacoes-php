<?php if (!isset($_COOKIE['USER_ID']))  header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Alterar Status de Pedidos de Frete</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Pedidos</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-12">
                <label for="textFiltro">Filtro:</label>
                <input type="text" id="textFiltro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição..." />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <label for="dateFiltroDataInicio">Filtro Data Início:</label>
                <input type="date" id="dateFiltroDataInicio" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="dateFiltroDataFim">Filtro Data Fim:</label>
                <input type="date" id="dateFiltroDataFim" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-4">
                <label for="selectStatus">Status:</label>
                <select id="selectStatus" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="selectOrdem">Ordenar por:</label>
                <select id="selectOrdem" class="form-control input-sm">
                    <option value="1">DESCRIÇÂO (CRESCENTE)</option>
                    <option value="2">DESCRIÇÂO (DECRESCENTE)</option>
                    <option value="3">DATA (CRESCENTE)</option>
                    <option value="4">DATA (DECRESCENTE)</option>
                    <option value="5">AUTOR (CRESCENTE)</option>
                    <option value="6">AUTOR (DECRESCENTE)</option>
                    <option value="7">FORMA PAGAMENTO (CRESCENTE)</option>
                    <option value="8">FORMA PAGAMENTO (DECRESCENTE)</option>
                    <option value="9">STATUS (CRESCENTE)</option>
                    <option value="10">STATUS (DECRESCENTE)</option>
                    <option value="11">VALOR (CRESCENTE)</option>
                    <option value="12">VALOR (DECRESCENTE)</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4"></div>

            <div class="col-sm-4">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
            </div>

            <div class="col-sm-4"></div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend">Pedidos Abertos</div>

    <div class="fieldset-card-container">
        <table id="tablePedidos" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th>DESCRIÇÃO</th>
                <th>CLIENTE</th>
                <th>DATA</th>
                <th>AUTOR</th>
                <th>FORMA PAGAMENTO</th>
                <th>STATUS</th>
                <th>VALOR (R$)</th>
                <th style="width: 2%;">&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbodyPedidos">
            </tbody>
        </table>
    </div>
</div>