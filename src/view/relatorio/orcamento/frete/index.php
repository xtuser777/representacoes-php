<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Relatório de Orçamentos de Frete</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Orçamentos</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="textFiltro">Filtro:</label>
                <input type="text" id="textFiltro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição e cliente..." />
            </div>

            <div class="col-sm-3">
                <label for="dateFiltroInicio">Filtro Data Início:</label>
                <input type="date" id="dateFiltroInicio" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-3">
                <label for="dateFiltroFim">Filtro Data Fim:</label>
                <input type="date" id="dateFiltroFim" class="form-control input-sm" style="width: 100%;" />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="selectCliente">Cliente:</label>
                <select id="selectCliente" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="selectOrdem">Ordenar por:</label>
                <select id="selectOrdem" class="form-control input-sm">
                    <option value="1">DESCRIÇÂO (CRESCENTE)</option>
                    <option value="2">DESCRIÇÂO (DECRESCENTE)</option>
                    <option value="3">CLIENTE (CRESCENTE)</option>
                    <option value="4">CLIENTE (DECRESCENTE)</option>
                    <option value="5">DATA (CRESCENTE)</option>
                    <option value="6">DATA (DECRESCENTE)</option>
                    <option value="7">AUTOR (CRESCENTE)</option>
                    <option value="8">AUTOR (DECRESCENTE)</option>
                    <option value="9">VENCIMENTO (CRESCENTE)</option>
                    <option value="10">VENCIMENTO (DECRESCENTE)</option>
                    <option value="11">VALOR (CRESCENTE)</option>
                    <option value="12">VALOR (DECRESCENTE)</option>
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
    <div class="fieldset-card-legend" style="width: 200px;">Orçamentos Listados</div>

    <div class="fieldset-card-container">

        <table id="tableOrcamentos" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th>DESCRIÇÃO</th>
                <th>CLIENTE</th>
                <th>DATA</th>
                <th>DESTINO</th>
                <th>AUTOR</th>
                <th>VENCIMENTO</th>
                <th>VALOR (R$)</th>
            </tr>
            </thead>

            <tbody id="tbodyOrcamentos">
            </tbody>
        </table>
    </div>
</div>