<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Lançar Nova Despesa</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Fonte da Despesa</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-12">
                <label for="txEmpresa">Empresa <span style="color: red;">*</span>:</label>
                <input type="text" id="txEmpresa" class="form-control input-sm" style="width: 100%;" onblur="validarEmpresa();" />
                <div id="msempresa"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="slCategoria">Categoria <span style="color: red;">*</span>:</label>
                <select id="slCategoria" class="form-control input-sm" style="width: 100%;" onblur="validarCategoria();">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="mscategoria"></div>
            </div>

            <div class="col-sm-8">
                <label for="slPedido">Pedido de Frete:</label>
                <select id="slPedido" class="form-control input-sm" style="width: 100%;">
                    <option value="0">SELECIONE</option>
                </select>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados da Despesa</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-1">
                <label for="txConta">Conta:</label>
                <input type="text" id="txConta" class="form-control input-sm" style="width: 100%;" readonly />
            </div>

            <div class="col-sm-5">
                <label for="txDescricao">Descricao <span style="color: red;">*</span>:</label>
                <input type="text" id="txDescricao" class="form-control input-sm" style="width: 100%;" onblur="validarDescricao();" />
                <div id="msdescricao"></div>
            </div>

            <div class="col-sm-3">
                <label for="slTipo">Tipo <span style="color: red;">*</span>:</label>
                <select id="slTipo" class="form-control input-sm" style="width: 100%;" onblur="validarTipo();" onchange="mudarTipo();">
                    <option value="0">SELECIONE</option>
                    <option value="1">A VISTA</option>
                    <option value="2">A PRAZO</option>
                    <option value="3">FIXA</option>
                </select>
                <div id="mstipo"></div>
            </div>

            <div id="forma" class="col-sm-3">
                <label for="slFormaPagamento">Forma Pagamento <span style="color: red;">*</span>:</label>
                <select id="slFormaPagamento" class="form-control input-sm" style="width: 100%;" onblur="validarFormaPagamento();">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="msforma"></div>
            </div>

            <div id="intervalo" class="col-sm-3">
                <label for="txIntervaloParcelas">Dias entre Parcelas <span style="color: red;">*</span>:</label>
                <input type="number" id="txIntervaloParcelas" class="form-control input-sm" style="width: 100%;" onblur="validarIntervalo();" />
                <div id="msintervalo"></div>
            </div>

            <div id="frequencia" class="col-sm-3">
                <label for="slFrequencia">Frequência <span style="color: red;">*</span>:</label>
                <select id="slFrequencia" class="form-control input-sm" style="width: 100%;" onblur="validarFrequencia();">
                    <option value="0">SELECIONE</option>
                    <option value="1">MENSAL</option>
                    <option value="2">ANUAL</option>
                </select>
                <div id="msfrequencia"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="dtDespesa">Data Despesa <span style="color: red;">*</span>:</label>
                <input type="date" id="dtDespesa" class="form-control input-sm" style="width: 100%;" onblur="validarData();" />
                <div id="msdata"></div>
            </div>

            <div id="pago" class="col-sm-3">
                <label for="txValorPago">Valor Pago <span style="color: red;">*</span>:</label>
                <div class="input-group ">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="txValorPago" class="form-control input-sm" style="width: 100%;" value="0,00" onblur="validarValorPago();" />
                </div>
                <div id="msvalorpago"></div>
            </div>

            <div id="parcelas" class="col-sm-3">
                <label for="txParcelas">Parcelas <span style="color: red;">*</span>:</label>
                <input type="number" id="txParcelas" class="form-control input-sm" style="width: 100%;" onblur="validarParcelas();" />
                <div id="msparcelas"></div>
            </div>

            <div class="col-sm-3">
                <label for="txValor">Valor Despesa <span style="color: red;">*</span>:</label>
                <div class="input-group ">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="txValor" class="form-control input-sm" style="width: 100%;" value="0,00" onblur="validarValor();" />
                </div>
                <div id="msvalor"></div>
            </div>

            <div class="col-sm-3">
                <label for="dtVencimento">Vencimento <span style="color: red;">*</span>:</label>
                <input type="date" id="dtVencimento" class="form-control input-sm" style="width: 100%;" onblur="validarVencimento();" />
                <div id="msvencimento"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <button id="button_cancelar" class="btn btn-danger" style="width: 100%;" onclick="cancelarLancamento();">CANCELAR</button>
    </div>

    <div class="col-sm-6"></div>

    <div class="col-sm-2">
        <button id="button_limpar" class="btn btn-primary" style="width: 100%;" onclick="limparCampos();">LIMPAR</button>
    </div>

    <div class="col-sm-2">
        <button id="button_salvar" class="btn btn-success" style="width: 100%;" onclick="lancarDespesa();">SALVAR</button>
    </div>
</div>
