<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Detalhes do Orçamento de Frete</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do orçamento</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-12">
                <label for="txDescricao">Descrição <span style="color: red;">*</span>:</label>
                <input id="txDescricao" class="form-control input-sm" style="width: 100%;" value="" onblur="textDescBlur();" />
                <div id="msdesc"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="selectOrcamentoVenda">Orçamento de Venda:</label>
                <select id="selectOrcamentoVenda" class="form-control input-sm" style="width: 100%;" onchange="selectOrcVendaChange();">
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="selectRepresentacao">Representação:</label>
                <select id="selectRepresentacao" class="form-control input-sm" style="width: 100%;" onchange="selectRepresentacaoChange();">
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="selectCliente">Cliente: <span style="color: red;">*</span></label>
                <select id="selectCliente" class="form-control input-sm" style="width: 100%;" onblur="selectClienteBlur();">
                    <option value="0">SELECIONAR</option>
                </select>
                <div id="mscli"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Produtos orçados</div>

    <div class="fieldset-card-container">
        <div class="table-container" style="height: 150px;">
            <table id="table_itens" class="table table-striped table-hover">

                <thead>
                <tr>
                    <th>DESCRIÇÃO</th>
                    <th>REPRESENTAÇÃO</th>
                    <th>PESO (Kg)</th>
                    <th>QTDE.</th>
                    <th>TOTAL (Kg)</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>

                <tbody id="tbody_itens">
                </tbody>

            </table>
        </div>

        <div class="row">
            <div class="col-sm-8"></div>

            <div class="col-sm-2">
                <button id="button_clr_itens" class="btn btn-primary btn-sm" style="width: 100%;" onclick="buttonClrItensClick();">LIMPAR</button>
            </div>

            <div class="col-sm-2">
                <button id="button_add_item" class="btn btn-success btn-sm" onclick="abrirAdicionarItem();" style="width: 100%;">ADICIONAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do transporte</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-3">
                <label for="selEstadoDestino">Estado <span style="color: red;">*</span>:</label>
                <select id="selEstadoDestino" class="form-control input-sm" style="width: 100%;" onchange="selectEstadoChange();" onblur="selectEstadoBlur();">
                    <option value="0">SELECIONAR</option>
                </select>
                <div id="msest"></div>
            </div>

            <div class="col-sm-3">
                <label for="selCidadeDestino">Cidade <span style="color: red;">*</span>:</label>
                <select id="selCidadeDestino" class="form-control input-sm" style="width: 100%;" onblur="selectCidadeBlur();">
                    <option value="0">SELECIONAR</option>
                </select>
                <div id="mscid"></div>
            </div>

            <div class="col-sm-3">
                <label for="selTipoCaminhao">Tipo Caminhão <span style="color: red;">*</span>:</label>
                <select id="selTipoCaminhao" class="form-control input-sm" style="width: 100%;" onblur="selectTipoCaminhaoBlur();" onchange="selectTipoCaminhaoChange();">
                    <option value="0">SELECIONAR</option>
                </select>
                <div id="mstipo"></div>
            </div>

            <div class="col-sm-3">
                <label for="txDistancia">Distância <span style="color: red;">*</span>:</label>
                <div class="input-group">
                    <input type="number" id="txDistancia" class="form-control input-sm" style="width: 100%;" onblur="textDistanciaBlur();" />
                    <div class="input-group-addon">KM</div>
                </div>
                <div id="msdist"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Valores do Orçamento</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-3">
                <label for="txPesoProdutos">Peso Total dos Produtos:</label>
                <div class="input-group">
                    <input type="text" id="txPesoProdutos" class="form-control input-sm" style="width: 100%;" value="1.800" readonly />
                    <div class="input-group-addon">KG</div>
                </div>
            </div>

            <div class="col-sm-3">
                <label for="txValorFrete">Valor Orçado do Frete:</label>
                <div class="input-group">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="txValorFrete" class="form-control input-sm" style="width: 100%;" />
                </div>
                <div id="msvalor"></div>
            </div>

            <div class="col-sm-3">
                <label for="dtEntrega">Data Aprox. de Entrega <span style="color: red;">*</span>:</label>
                <input type="date" id="dtEntrega" class="form-control input-sm" style="width: 100%;" onblur="dateEntregaBlur();" />
                <div id="msentrega"></div>
            </div>

            <div class="col-sm-3">
                <label for="dtValidade">Validade do Orçamento <span style="color: red;">*</span>:</label>
                <input type="date" id="dtValidade" class="form-control input-sm" style="width: 100%;" onblur="dateValidadeBlur();" />
                <div id="msvalid"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <button id="button_cancelar" class="btn btn-danger" style="width: 100%;" onclick="buttonCancelarClick();">CANCELAR</button>
    </div>

    <div class="col-sm-8"></div>

    <div class="col-sm-2">
        <button id="button_salvar" class="btn btn-success" style="width: 100%;" onclick="buttonSalvarClick();">SALVAR</button>
    </div>
</div>

<!-- Fancybox Add Produto -->
<div style="display: none; min-width: 300px; width: 700px" id="fbFrmAddProduto">

    <h3 style="text-align: center; font-weight: bold;">Adicionar Produto</h3>
    <hr style="color: grey;" />

    <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="text_filtro_prod">Filtro:</label>
                <input type="text" id="text_filtro_prod" class="form-control input-sm" />
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="button_filtrar_prod">&nbsp;</label>
                <button id="button_filtrar_prod" class="btn btn-primary btn-sm" style="width: 100%;" onclick="buttonFiltrarProdClick();">FILTRAR</button>
            </div>
        </div>
    </div>
    <div class="table-container" style="height: 190px;">
        <table id="table_produtos" class="table table-striped table-hover">

            <thead>
            <tr>
                <th>DESCRIÇÃO</th>
                <th>UNIDADE</th>
                <th>REPRESENTAÇÂO</th>
                <th>PESO</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbody_produtos">
            </tbody>

        </table>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <label for="text_prod_sel">Produto selecionado <span style="color: red;">*</span>:</label>
            <input type="text" id="text_prod_sel" class="form-control input-sm" readonly style="width: 100%;" />
            <div id="msprodsel"></div>
        </div>

        <div class="col-sm-4">
            <label for="text_qtde_prod">Quantidade desejada <span style="color: red;">*</span>:</label>
            <input type="number" id="text_qtde_prod" class="form-control input-sm" style="width: 100%;" onchange="calcularPesoTotal();" oninput="calcularPesoTotal();" value="0" />
            <div id="msqtdeprod"></div>
        </div>

        <div class="col-sm-4">
            <label for="text_peso_total">Peso total:</label>
            <input type="text" id="text_peso_total" class="form-control input-sm" style="width: 100%;" value="0,0" readonly />
        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="row">
        <div class="col-sm-3">
            <button id="button_canc_prod" class="btn btn-danger" style="width: 100%;" onclick="cancelarAdicao();">Cancelar</button>
        </div>

        <div class="col-sm-6"></div>

        <div class="col-sm-3">
            <button id="button_conf_prod" class="btn btn-success" style="width: 100%;" onclick="adicionarItem();">Confirmar</button>
        </div>
    </div>
</div>
