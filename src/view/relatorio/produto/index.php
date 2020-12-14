<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Relatório de Produtos</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Produtos</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-9">
                <label for="textFiltro">Filtro:</label>
                <input type="text" id="textFiltro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição..." />
            </div>

            <div class="col-sm-3">
                <label for="textUnidade">Medida:</label>
                <input type="text" id="textUnidade" class="form-control input-sm" style="width: 100%;" placeholder="SACO, GRANEL, ETC..." />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="selectRepresentacao">Representação:</label>
                <select id="selectRepresentacao" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="selectOrdem">Ordenar por:</label>
                <select name="selectOrdem" id="selectOrdem" class="form-control input-sm">
                    <option value="1">DESCRIÇÃO (CRESCENTE)</option>
                    <option value="2">DESCRIÇÃO (DECRESCENTE)</option>
                    <option value="3">MEDIDA (CRESCENTE)</option>
                    <option value="4">MEDIDA (DECRESCENTE)</option>
                    <option value="5">PESO (CRESCENTE)</option>
                    <option value="6">PESO (DECRESCENTE)</option>
                    <option value="7">PREÇO (CRESCENTE)</option>
                    <option value="8">PREÇO (DECRESCENTE)</option>
                    <option value="9">REPRESENTAÇÃO (CRESCENTE)</option>
                    <option value="10">REPRESENTAÇÃO (DECRESCENTE)</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
            </div>

            <div class="col-sm-2">
                <label for="emitir">&nbsp;</label>
                <button id="emitir" class="btn btn-info btn-sm" style="width: 100%;" onclick="emitir();">EMITIR PDF</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend">Produtos Listados</div>

    <div class="fieldset-card-container">
        <table id="tableProdutos" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 40%;">DESCRIÇÃO</th>
                <th style="width: 16%;">MEDIDA</th>
                <th style="width: 12%;">PESO (KG)</th>
                <th style="width: 12%;">PREÇO (R$)</th>
                <th>REPRESENTAÇÂO</th>
            </tr>
            </thead>

            <tbody id="tbodyProdutos">
            </tbody>
        </table>
    </div>
</div>
