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

            <div class="col-sm-2">
                <label for="dtDespesa">Data:</label>
                <input type="date" id="dtDespesa" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-6">
                <label for="txDescricao">Descricao:</label>
                <input type="text" id="txDescricao" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-3">
                <label for="txFonte">Fonte:</label>
                <input type="text" id="txFonte" class="form-control input-sm" style="width: 100%;" readonly />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="txPagador">Pagador:</label>
                <input type="text" id="txPagador" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-2">
                <label for="dtVencimento">Vencimento:</label>
                <input type="date" id="dtVencimento" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-3">
                <label for="txValor">Valor:</label>
                <div class="input-group ">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="txValor" class="form-control input-sm" style="width: 100%;" value="0,00" readonly />
                </div>
            </div>

            <div class="col-sm-4">
                <label for="txSituacao">Situação:</label>
                <input type="text" id="txSituacao" class="form-control input-sm" style="width: 100%;" readonly />
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Recebimento</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="slFormaRecebimento">Forma Recebimento <span style="color: red;">*</span>:</label>
                <select id="slFormaRecebimento" class="form-control input-sm" style="width: 100%;" onblur="validarFormaRecebimento();">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="msforma"></div>
            </div>

            <div class="col-sm-3">
                <label for="txValorRecebido">Valor Recebido <span style="color: red;">*</span>:</label>
                <div class="input-group ">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="txValorRecebido" class="form-control input-sm" style="width: 100%;" value="0,00" onblur="validarValor();" />
                </div>
                <div id="msvalor"></div>
            </div>

            <div class="col-sm-3">
                <label for="dtRecebimento">Data Recebimento <span style="color: red;">*</span>:</label>
                <input type="date" id="dtRecebimento" class="form-control input-sm" style="width: 100%;" onblur="validarRecebimento();" />
                <div id="msrecebimento"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <button id="button_cancelar" class="btn btn-danger" style="width: 100%;" onclick="cancelarRecebimento();">CANCELAR</button>
    </div>

    <div class="col-sm-8"></div>

    <div class="col-sm-2">
        <button id="button_salvar" class="btn btn-success" style="width: 100%;" onclick="receberDespesa();">RECEBER</button>
    </div>
</div>