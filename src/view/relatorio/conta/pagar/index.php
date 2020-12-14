<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Relatório de Contas a Pagar</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Contas</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-4">
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

            <div class="col-sm-2">
                <label for="selectSituacao">Situação:</label>
                <select id="selectSituacao" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                    <option value="1">PENDENTE</option>
                    <option value="2">PAGO PARCIALMENTE</option>
                    <option value="3">PAGO</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <label for="selectComissao">Comissão:</label>
                <select id="selectComissao" class="form-control input-sm" onchange="selecionarComissao();">
                    <option value="0">SELECIONE</option>
                    <option value="1">SIM</option>
                    <option value="2">NÃO</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="selectVendedor">Vendedor:</label>
                <select id="selectVendedor" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="selectOrdem">Ordenar por:</label>
                <select id="selectOrdem" class="form-control input-sm">
                    <option value="1">CONTA/PARCELA (CRESCENTE)</option>
                    <option value="2">CONTA (CRESCENTE)</option>
                    <option value="3">CONTA (DECRESCENTE)</option>
                    <option value="4">DESCRIÇÂO (CRESCENTE)</option>
                    <option value="5">DESCRIÇÂO (DECRESCENTE)</option>
                    <option value="6">PARCELA (CRESCENTE)</option>
                    <option value="7">PARCELA (DECRESCENTE)</option>
                    <option value="8">VALOR (CRESCENTE)</option>
                    <option value="9">VALOR (DECRESCENTE)</option>
                    <option value="10">VENCIMENTO (CRESCENTE)</option>
                    <option value="11">VENCIMENTO (DECRESCENTE)</option>
                    <option value="12">SITUAÇÃO (CRESCENTE)</option>
                    <option value="13">SITUAÇÃO (DECRESCENTE)</option>
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
    <div class="fieldset-card-legend">Contas Lançadas</div>

    <div class="fieldset-card-container">

        <table id="tableContas" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 5%;">CONTA</th>
                <th style="width: 28%;">DESCRIÇÃO</th>
                <th style="width: 6%;">PARCELA</th>
                <th>VALOR (R$)</th>
                <th>LANÇAMENTO</th>
                <th>VENCIMENTO</th>
                <th>PAGO (R$)</th>
                <th>PAGAMENTO</th>
                <th>SITUAÇÃO</th>
            </tr>
            </thead>

            <tbody id="tbodyContas">
            </tbody>
        </table>
    </div>
</div>
