<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Detalhes da Conta</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Detalhes da Conta</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-1">
                <label for="txConta">Conta:</label>
                <input type="text" id="txConta" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-3">
                <label for="dtDespesa">Data:</label>
                <input type="date" id="dtDespesa" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-2">
                <label for="txParcela">Parcela:</label>
                <input type="text" id="txParcela" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-6">
                <label for="txDescricao">Descricao:</label>
                <input type="text" id="txDescricao" class="form-control input-sm" style="width: 100%;" readonly />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="txEmpresa">Empresa:</label>
                <input type="text" id="txEmpresa" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-2">
                <label for="txTipo">Tipo:</label>
                <input type="text" id="txTipo" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-3">
                <label for="txCategoria">Categoria:</label>
                <input type="text" id="txCategoria" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-3">
                <label for="txFonte">Fonte:</label>
                <input type="text" id="txFonte" class="form-control input-sm" style="width: 100%;" readonly />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="dtVencimento">Vencimento <span style="color: red;">*</span>:</label>
                <input type="date" id="dtVencimento" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-3">
                <label for="txValor">Valor Despesa <span style="color: red;">*</span>:</label>
                <div class="input-group ">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="txValor" class="form-control input-sm" style="width: 100%;" value="0,00" readonly />
                </div>
            </div>

            <div class="col-sm-6">
                <label for="txSituacao">Situação:</label>
                <input type="text" id="txSituacao" class="form-control input-sm" style="width: 100%;" readonly />
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Pagamento</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="slFormaPagamento">Forma Pagamento <span style="color: red;">*</span>:</label>
                <select id="slFormaPagamento" class="form-control input-sm" style="width: 100%;" onblur="validarFormaPagamento();">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="msforma"></div>
            </div>

            <div class="col-sm-3">
                <label for="txValorPago">Valor Pago <span style="color: red;">*</span>:</label>
                <div class="input-group ">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="txValorPago" class="form-control input-sm" style="width: 100%;" value="0,00" onblur="validarValor();" />
                </div>
                <div id="msvalor"></div>
            </div>

            <div class="col-sm-3">
                <label for="dtPagamento">Data Pagamento <span style="color: red;">*</span>:</label>
                <input type="date" id="dtPagamento" class="form-control input-sm" style="width: 100%;" onblur="validarPagamento();" />
                <div id="mspagamento"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <button id="button_cancelar" class="btn btn-danger" style="width: 100%;" onclick="cancelarQuitacao();">CANCELAR</button>
    </div>

    <div class="col-sm-8"></div>

    <div class="col-sm-2">
        <button id="button_salvar" class="btn btn-success" style="width: 100%;" onclick="quitarDespesa();">QUITAR</button>
    </div>
</div>