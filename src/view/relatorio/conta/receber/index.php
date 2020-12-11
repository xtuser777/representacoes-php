<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Relatório de Contas a Receber</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Contas</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="textFiltro">Filtro:</label>
                <input type="text" id="textFiltro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição..." />
            </div>

            <div class="col-sm-2">
                <label for="dateFiltroInicio">Data Lançamento Início:</label>
                <input type="date" id="dateFiltroInicio" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="dateFiltroFim">Data Lançamento Fim:</label>
                <input type="date" id="dateFiltroFim" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="dateVencimento">Data Vencimento:</label>
                <input type="date" id="dateVencimento" class="form-control input-sm" style="width: 100%;" />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="selectSituacao">Situação:</label>
                <select id="selectSituacao" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                    <option value="1">PENDENTE</option>
                    <option value="2">PAGO PARCIALMENTE</option>
                    <option value="3">PAGO</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="selectOrdem">Ordenar por:</label>
                <select id="selectOrdem" class="form-control input-sm">
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
    <div class="fieldset-card-legend" style="width: 200px;">Contas Lançadas</div>

    <div class="fieldset-card-container">

        <table id="tableContas" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th>CONTA</th>
                <th>DESCRIÇÃO</th>
                <th>VALOR (R$)</th>
                <th>LANÇAMENTO</th>
                <th>VENCIMENTO</th>
                <th>RECEBIDO (R$)</th>
                <th>RECEBIMENTO</th>
                <th>SITUAÇÃO</th>
            </tr>
            </thead>

            <tbody id="tbodyContas">
            </tbody>
        </table>
    </div>
</div>
