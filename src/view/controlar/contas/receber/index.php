<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Contas a Receber</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Contas</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="txFiltro">Filtro:</label>
                <input type="text" id="txFiltro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição..." />
            </div>

            <div class="col-sm-3">
                <label for="txDataInicio">Data Vencimento Início:</label>
                <input type="date" id="txDataInicio" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-3">
                <label for="txDataFim">Data Vencimento Fim:</label>
                <input type="date" id="txDataFim" class="form-control input-sm" style="width: 100%;" />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="slSituacao">Situação:</label>
                <select id="slSituacao" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                    <option value="1">PENDENTE</option>
                    <option value="2">PAGO PARCIALMENTE</option>
                    <option value="3">PAGO</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="selectComissao">Comissão:</label>
                <select id="selectComissao" class="form-control input-sm" onchange="selecionarComissao();">
                    <option value="0">SELECIONE</option>
                    <option value="1">SIM</option>
                    <option value="2">NÃO</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="selectRepresentacao">Representação:</label>
                <select id="selectRepresentacao" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="slOrdenar">Ordenar por:</label>
                <select id="slOrdenar" class="form-control input-sm">
                    <option value="1">CONTA (CRESCENTE)</option>
                    <option value="2">CONTA (DECRESCENTE)</option>
                    <option value="3">DESCRIÇÂO (CRESCENTE)</option>
                    <option value="4">DESCRIÇÂO (DECRESCENTE)</option>
                    <option value="5">VALOR (CRESCENTE)</option>
                    <option value="6">VALOR (DECRESCENTE)</option>
                    <option value="7">VENCIMENTO (CRESCENTE)</option>
                    <option value="8">VENCIMENTO (DECRESCENTE)</option>
                    <option value="9">SITUAÇÃO (CRESCENTE)</option>
                    <option value="10">SITUAÇÃO (DECRESCENTE)</option>
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
    <div class="fieldset-card-legend" style="width: 200px;">Contas Lançadas</div>

    <div class="fieldset-card-container">

        <table id="tbContas" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 5%;">CONTA</th>
                <th style="width: 28%;">DESCRIÇÃO</th>
                <th>VALOR (R$)</th>
                <th style="width: 10%;">VENCIMENTO</th>
                <th style="width: 14%;">VALOR PAGO (R$)</th>
                <th style="width: 16%;">DATA RECEBIMENTO</th>
                <th>SITUAÇÃO</th>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 2%;">&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbodyContas">
            </tbody>
        </table>
    </div>
</div>