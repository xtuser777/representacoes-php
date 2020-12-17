<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Detalhes do Pedido de Frete</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do pedido</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-4">
                <label for="selectOrcamentoFrete">Orçamento de Frete:</label>
                <select id="selectOrcamentoFrete" class="form-control input-sm" style="width: 100%;" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="selectPedidoVenda">Pedido de Venda:</label>
                <select id="selectPedidoVenda" class="form-control input-sm" style="width: 100%;" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="selectRepresentacao">Representação:</label>
                <select id="selectRepresentacao" class="form-control input-sm" style="width: 100%;" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-7">
                <label for="textDescricao">Descrição:</label>
                <input id="textDescricao" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-5">
                <label for="selectCliente">Cliente:</label>
                <select id="selectCliente" class="form-control input-sm" style="width: 100%;" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Itens fretados</div>

    <div class="fieldset-card-container">
        <div class="table-container" style="height: 150px;">
            <table id="tableItens" class="table table-striped table-hover">

                <thead>
                <tr>
                    <th>DESCRIÇÃO</th>
                    <th>REPRESENTAÇÃO</th>
                    <th>PESO (Kg)</th>
                    <th>QTDE.</th>
                    <th>TOTAL (Kg)</th>
                </tr>
                </thead>

                <tbody id="tbodyItens">
                </tbody>

            </table>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Etapas de carregamento</div>

    <div class="fieldset-card-container">
        <div class="table-container" style="height: 150px;">
            <table id="tableEtapas" class="table table-striped table-hover">

                <thead>
                <tr>
                    <th>ORDEM</th>
                    <th>REPRESENTAÇÃO</th>
                    <th>CIDADE</th>
                    <th>CARGA (Kg)</th>
                    <th>STATUS</th>
                </tr>
                </thead>

                <tbody id="tbodyEtapas">
                </tbody>

            </table>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do transporte</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-3">
                <label for="selectTipoCaminhao">Tipo Caminhão:</label>
                <select id="selectTipoCaminhao" class="form-control input-sm" style="width: 100%;" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="selectProprietario">Proprietário Caminhão:</label>
                <select id="selectProprietario" class="form-control input-sm" style="width: 100%;" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="selectCaminhao">Caminhão:</label>
                <select id="selectCaminhao" class="form-control input-sm" style="width: 100%;" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="textDistancia">Distância:</label>
                <div class="input-group">
                    <input type="number" id="textDistancia" class="form-control input-sm" style="width: 100%;" readonly />
                    <div class="input-group-addon">KM</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        <div class="fieldset-card">
            <div class="fieldset-card-legend">Destino</div>

            <div class="fieldset-card-container">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="selectEstadoDestino">Estado:</label>
                        <select id="selectEstadoDestino" class="form-control input-sm" style="width: 100%;" onchange="selectEstadoChange();" disabled>
                            <option value="0">SELECIONAR</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <label for="selectCidadeDestino">Cidade:</label>
                        <select id="selectCidadeDestino" class="form-control input-sm" style="width: 100%;" disabled>
                            <option value="0">SELECIONAR</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-7">
        <div class="fieldset-card">
            <div class="fieldset-card-legend">Pagamento Motorista</div>

            <div class="fieldset-card-container">
                <div class="row">
                    <div class="col-sm-7">
                        <label for="selectMotorista">Motorista:</label>
                        <select id="selectMotorista" class="form-control input-sm" style="width: 100%;" disabled>
                            <option value="0">SELECIONAR</option>
                        </select>
                    </div>

                    <div class="col-sm-5">
                        <label for="textValorMotorista">Valor:</label>
                        <div class="input-group">
                            <div class="input-group-addon">R$</div>
                            <input type="text" id="textValorMotorista" class="form-control input-sm" style="width: 100%;" readonly />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-5">
                        <label for="textValorAdiantamento">Valor adiantamento:</label>
                        <div class="input-group">
                            <div class="input-group-addon">R$</div>
                            <input type="text" id="textValorAdiantamento" class="form-control input-sm" style="width: 100%;" readonly />
                        </div>
                    </div>

                    <div class="col-sm-7">
                        <label for="selectFormaAdiantamento">Forma de pagamento:</label>
                        <select id="selectFormaAdiantamento" class="form-control input-sm" disabled>
                            <option value="0">SELECIONAR</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Valores do Pedido</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-3">
                <label for="textPesoProdutos">Peso Total dos Produtos:</label>
                <div class="input-group">
                    <input type="text" id="textPesoProdutos" class="form-control input-sm" style="width: 100%;" value="" readonly />
                    <div class="input-group-addon">KG</div>
                </div>
            </div>

            <div class="col-sm-3">
                <label for="textValorFrete">Valor do Frete:</label>
                <div class="input-group">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="textValorFrete" class="form-control input-sm" style="width: 100%;" readonly />
                </div>
            </div>

            <div class="col-sm-3">
                <label for="selectForma">Forma de pagamento:</label>
                <select id="selectForma" class="form-control input-sm" disabled>
                    <option value="0">SELECIONAR</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="dateEntrega">Data Aprox. de Entrega:</label>
                <input type="date" id="dateEntrega" class="form-control input-sm" style="width: 100%;" readonly />
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
        <button id="button_excluir" class="btn btn-danger" style="width: 100%;" onclick="excluir();">EXCLUIR</button>
    </div>
</div>