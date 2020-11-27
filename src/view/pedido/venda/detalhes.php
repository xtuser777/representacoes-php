<?php if (!isset($_COOKIE["USER_ID"])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Detalhes do Pedido de Venda</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Pedido</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-5">
                <label for="select_orcamento">Orçamento:</label>
                <select id="select_orcamento" class="form-control input-sm" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-7">
                <label for="text_desc">Descrição:</label>
                <input type="text" id="text_desc" class="form-control input-sm" style="width: 100%;" readonly />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">
                <label for="select_cliente">Cliente:</label>
                <select id="select_cliente" class="form-control input-sm" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="select_est_dest">Estado de Destino:</label>
                <select id="select_est_dest" class="form-control input-sm" disabled>
                    <option value="0">SELECIONE</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="select_cid_dest">Cidade de Destino:</label>
                <select id="select_cid_dest" class="form-control input-sm" disabled>
                    <option value="0">SELECIONE</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Produtos do pedido</div>

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
                </tr>
                </thead>

                <tbody id="tbody_itens">
                </tbody>

            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="fieldset-card">
            <div class="fieldset-card-legend" style="width: 200px;">Vendedor externo</div>

            <div class="fieldset-card-container">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="select_vendedor">Vendedor:</label>
                        <select id="select_vendedor" class="form-control input-sm" disabled>
                            <option value="0">SELECIONAR</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <label for="textPorcentagemComissaoVendedor">Porcentagem de comissão ao vendedor:</label>
                        <div class="input-group">
                            <input type="number" id="textPorcentagemComissaoVendedor" class="form-control input-sm" value="1" readonly />
                            <div class="input-group-addon">%</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div style="height: 40px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-8">
        <div class="fieldset-card">
            <div class="fieldset-card-legend">Comissões</div>

            <div class="fieldset-card-container">
                <div class="table-container" style="height: 150px;">
                    <table id="tableComissoes" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>REPRESENTAÇÃO</th>
                            <th>VALOR (R$)</th>
                            <th>PORCENTAGEM</th>
                        </tr>
                        </thead>

                        <tbody id="tbodyComissoes">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Valores do Pedido</div>

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
                <label for="text_valor_itens">Valor dos Itens:</label>
                <div class="input-group">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="text_valor_itens" class="form-control input-sm" style="width: 100%;" value="" readonly />
                </div>
            </div>

            <div class="col-sm-4">
                <label for="select_forma">Forma de pagamento:</label>
                <select id="select_forma" class="form-control input-sm" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <button id="button_cancelar" class="btn btn-default" style="width: 100%;" onclick="buttonCancelarClick();">CANCELAR</button>
    </div>

    <div class="col-sm-8"></div>

    <div class="col-sm-2">
        <button id="button_deletar" class="btn btn-danger" style="width: 100%;" onclick="excluir();">DELETAR</button>
    </div>
</div>
