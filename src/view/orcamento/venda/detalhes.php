<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Detalhes do Orçamento de Venda</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do cliente</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-3">
                <label for="select_cliente">Sel. Cliente:</label>
                <select id="select_cliente" class="form-control input-sm" onchange="selectClienteChange();">
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-6">
                <label for="text_nome_cli">Nome <span style="color: red;">*</span>:</label>
                <input type="text" id="text_nome_cli" class="form-control input-sm" style="width: 100%;" onblur="textNomeClienteBlur();" />
                <div id="msnomecli"></div>
            </div>

            <div class="col-sm-3">
                <label for="text_doc_cli">Documento (CPF ou CNPJ) <span style="color: red;">*</span>:</label>
                <input type="text" id="text_doc_cli" class="form-control input-sm" style="width: 100%;" onblur="textDocClienteBlur();" />
                <div id="msdoccli"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="text_tel_cli">Telefone <span style="color: red;">*</span>:</label>
                <input type="text" id="text_tel_cli" class="form-control input-sm" style="width: 100%;" data-mask="(00) 0000-0000" data-mask-clearifnotmatch="true" onblur="textTelCliBlur();" />
                <div id="mstelcli"></div>
            </div>

            <div class="col-sm-3">
                <label for="text_cel_cli">Celular <span style="color: red;">*</span>:</label>
                <input type="text" id="text_cel_cli" class="form-control input-sm" style="width: 100%;" data-mask="(00) 00000-0000" data-mask-clearifnotmatch="true" onblur="textCelCliBlur();" />
                <div id="mscelcli"></div>
            </div>

            <div class="col-sm-6">
                <label for="text_email_cli">E-Mail <span style="color: red;">*</span>:</label>
                <input type="text" id="text_email_cli" class="form-control input-sm" style="width: 100%;" onblur="textEmailCliBlur();" />
                <div id="msemailcli"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Orçamento</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-12">
                <label for="text_desc">Descrição <span style="color: red;">*</span>:</label>
                <input type="text" id="text_desc" class="form-control input-sm" style="width: 100%;" onblur="textDescBlur();" />
                <div id="msdesc"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">
                <label for="select_vendedor">Vendedor:</label>
                <select id="select_vendedor" class="form-control input-sm">
                    <option value="0">SELECIONAR</option>
                </select>
                <div id="msvend"></div>
            </div>

            <div class="col-sm-3">
                <label for="select_est_dest">Estado de Destino <span style="color: red;">*</span>:</label>
                <select id="select_est_dest" class="form-control input-sm" onblur="selectEstadoBlur();" onchange="selectEstadoChange();">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="msest"></div>
            </div>

            <div class="col-sm-4">
                <label for="select_cid_dest">Cidade de Destino <span style="color: red;">*</span>:</label>
                <select id="select_cid_dest" class="form-control input-sm" onblur="selectCidadeBlur();">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="mscid"></div>
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
                    <th>VALOR (R$)</th>
                    <th>QTDE.</th>
                    <th>TOTAL (R$)</th>
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
    <div class="fieldset-card-legend">Valores do Orçamento</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-4">
                <label for="text_peso_itens">Peso Total <span style="color: red;">*</span>:</label>
                <div class="input-group">
                    <input type="text" id="text_peso_itens" class="form-control input-sm" style="width: 100%;" value="" readonly />
                    <div class="input-group-addon">KG</div>
                </div>
            </div>

            <div class="col-sm-4">
                <label for="text_valor_itens">Valor Orçado dos Itens <span style="color: red;">*</span>:</label>
                <div class="input-group">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="text_valor_itens" class="form-control input-sm" style="width: 100%;" value="" readonly />
                </div>
            </div>

            <div class="col-sm-4">
                <label for="date_validade">Validade <span style="color: red;">*</span>:</label>
                <input type="date" id="date_validade" class="form-control input-sm" style="width: 100%;" onblur="dateValidadeBlur();" />
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
<div style="display: none; min-width: 300px; width: 950px" id="fbFrmAddProduto">

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
                <th>PREÇO</th>
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
            <input type="text" id="text_prod_sel" class="form-control input-sm" disabled style="width: 100%;" />
            <div id="msprodsel"></div>
        </div>

        <div class="col-sm-3">
            <label for="text_valor_prod">Valor Unitário <span style="color: red;">*</span>:</label>
            <div class="input-group">
                <div class="input-group-addon">R$</div>
                <input type="text" id="text_valor_prod" class="form-control input-sm" style="width: 100%;" />
            </div>
            <div id="msvalorout"></div>
            <div id="msvalorprod"></div>
        </div>

        <div class="col-sm-2">
            <label for="text_qtde_prod">Qtde desejada <span style="color: red;">*</span>:</label>
            <input type="number" id="text_qtde_prod" class="form-control input-sm" style="width: 100%;" onchange="calcularTotalItem();" oninput="calcularTotalItem();" value="0"/>
            <div id="msqtdeprod"></div>
        </div>

        <div class="col-sm-3">
            <label for="text_total_prod">Valor Total:</label>
            <div class="input-group">
                <div class="input-group-addon">R$</div>
                <input type="text" id="text_total_prod" class="form-control input-sm" style="width: 100%;" value="0,00" readonly />
            </div>
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
