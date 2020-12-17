<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Lançar Despesas</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Despesas</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="txFiltro">Filtro:</label>
                <input type="text" id="txFiltro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição..." />
            </div>

            <div class="col-sm-2">
                <label for="txDataInicio">Data Vencimento Início:</label>
                <input type="date" id="txDataInicio" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="txDataFim">Data Vencimento Fim:</label>
                <input type="date" id="txDataFim" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend" style="width: 200px;">Despesas Lançadas</div>

    <div class="fieldset-card-container">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-10">
                <label for="cbord">Ordenar por:</label>
                <select id="cbord" class="form-control input-sm" onchange="ordenar();">
                    <option value="1">CONTA/PARCELA (CRESCENTE)</option>
                    <option value="2">CONTA (CRESCENTE)</option>
                    <option value="3">CONTA (DECRESCENTE)</option>
                    <option value="4">DESCRIÇÂO (CRESCENTE)</option>
                    <option value="5">DESCRIÇÂO (DECRESCENTE)</option>
                    <option value="6">PARCELA (CRESCENTE)</option>
                    <option value="7">PARCELA (DECRESCENTE)</option>
                    <option value="8">CATEGORIA (CRESCENTE)</option>
                    <option value="9">CATEGORIA (DECRESCENTE)</option>
                    <option value="12">VENCIMENTO (CRESCENTE)</option>
                    <option value="13">VENCIMENTO (DECRESCENTE)</option>
                    <option value="14">AUTOR (CRESCENTE)</option>
                    <option value="15">AUTOR (DECRESCENTE)</option>
                    <option value="16">VALOR (CRESCENTE)</option>
                    <option value="17">VALOR (DECRESCENTE)</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="novo">&nbsp;</label>
                <a role="button" id="novo" class="btn btn-success btn-sm" style="width: 100%;" href="/representacoes/controlar/lancar/despesas/novo">NOVO</a>
            </div>
        </div>

        <table id="tabDespesas" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 6%;">CONTA</th>
                <th style="width: 20%;">DESCRIÇÃO</th>
                <th style="width: 6%;">PARCELA</th>
                <th style="width: 14%;">CATEGORIA</th>
                <th style="width: 8%;">VENC.</th>
                <th style="width: 12%;">AUTOR</th>
                <th>VALOR (R$)</th>
                <th>SITUAÇÃO</th>
                <th style="width: 2%;">&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbodyDespesas">
            </tbody>
        </table>
    </div>
</div>