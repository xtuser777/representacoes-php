<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="icon" type="image/png" href="/representacoes/static/images/logo.png">

        <title>Detalhes do Orçamento de Frete - Sistema de Controle de Representações</title>

        <link rel="stylesheet" type="text/css" href="/representacoes/static/lib/bootstrap/dist/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="/representacoes/static/lib/fancybox/jquery.fancybox.min.css"/>
        <link rel="stylesheet" type="text/css" href="/representacoes/static/css/style.css" />
    </head>

    <body>
        <header>
            <!-- Barra de ferramentas -->
            <nav class="navbar navbar-fixed-top navbar-default navbar-scr">

                <div class="container-fluid">

                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <a style="color: #fff; font-weight: bold;" class="navbar-brand" href="/representacoes/inicio">SCR</a>

                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                        <!-- Botoes -->
                        <ul class="nav navbar-nav">

                            <!-- Botao inicio -->
                            <li>
                                <a style="color: #fff;" class="font-navbar" href="/representacoes/inicio">Início</a>
                            </li>
                            <!-- Fim botao inicio -->
                            <!-- Botao gerenciar -->
                            <li class="dropdown">
                                <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gerenciar <span class="caret"></span></a>
                                <!-- Popup botao gerenciar -->
                                <ul class="dropdown-menu">
                                    <?php if ($_COOKIE['USER_LEVEL'] == '1'): ?>
                                        <li><a href="/representacoes/gerenciar/funcionario">Funcionários</a></li>
                                    <?php endif ?>
                                    <li><a href="/representacoes/gerenciar/cliente">Clientes</a></li>
                                    <li><a href="/representacoes/gerenciar/motorista">Motoristas</a></li>
                                    <li><a href="/representacoes/gerenciar/proprietario">Proprietários de Caminhões</a></li>
                                    <li><a href="/representacoes/gerenciar/caminhao">Caminhões</a></li>
                                    <li><a href="/representacoes/gerenciar/representacao">Representações</a></li>
                                    <li><a href="/representacoes/gerenciar/produto">Produtos</a></li>
                                    <li><a href="/representacoes/gerenciar/tipocaminhao">Tipos de Caminhão</a></li>
                                    <li><a href="/representacoes/gerenciar/categoria">Categorias de Contas</a></li>
                                    <li><a href="/representacoes/gerenciar/formapagamento">Formas de Pagamento</a></li>
                                </ul>
                                <!-- Fim popup botao gerenciar -->
                            </li>
                            <!-- Fim botao gerenciar-->
                            <!-- Botao orcamento -->
                            <li class="dropdown">
                                <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Orçamento <span class="caret"></span></a>
                                <!-- Popup botao orcamento -->
                                <ul class="dropdown-menu">
                                    <li><a href="/representacoes/orcamento/venda">Venda</a></li>
                                    <li><a href="/representacoes/orcamento/frete">Frete</a></li>
                                </ul>
                                <!-- Fim popup botao orcamento -->
                            </li>
                            <!-- Fim botao orcamento -->
                            <!-- Botao pedido -->
                            <li class="dropdown">
                                <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pedido <span class="caret"></span></a>
                                <!-- Popup botao pedido -->
                                <ul class="dropdown-menu">
                                    <li><a href="/representacoes/pedido/venda">Venda</a></li>
                                    <li><a href="/representacoes/pedido/frete">Frete</a></li>
                                    <li><a href="/representacoes/pedido/status">Alterar status</a></li>
                                    <?php if ($_COOKIE['USER_LEVEL'] == '1'): ?>
                                        <li><a href="/pedido/autorizar/index">Autorizar Carregamento</a></li>
                                    <?php endif; ?>
                                </ul>
                                <!-- Fim popup botao pedido -->
                            </li>
                            <!-- Fim botao pedido -->
                            <?php if ($_COOKIE['USER_LEVEL'] == '1' || $_COOKIE['USER_LEVEL'] == '2'): ?>
                                <!-- Botao controlar -->
                                <li class="dropdown">
                                    <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        Controlar
                                        <span class="caret"></span>
                                    </a>
                                    <!-- Popup botao controlar -->
                                    <ul class="dropdown-menu">
                                        <li><a href="/representacoes/controlar/contas/pagar">Contas a Pagar</a></li>
                                        <li><a href="/representacoes/controlar/contas/receber">Contas a Receber</a></li>
                                        <li><a href="/representacoes/controlar/lancar/despesas">Lançar Despesas</a></li>
                                    </ul>
                                    <!-- Fim popup botao controlar -->
                                </li>
                                <!-- Fim botao controlar -->
                            <?php endif; ?>

                            <?php if ($_COOKIE['USER_LEVEL'] == '1'): ?>
                                <!-- Botao relatorio -->
                                <li class="dropdown">
                                    <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        Relatório
                                        <span class="caret"></span>
                                    </a>
                                    <!-- Popup botao relatorio -->
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Clientes</a></li>
                                        <li><a href="#">Pedido de Venda</a></li>
                                        <li><a href="#">Pedido de Frete</a></li>
                                        <li><a href="#">Orçamento de Venda</a></li>
                                        <li><a href="#">Orçamento de Frete</a></li>
                                        <li><a href="#">Contas a Pagar</a></li>
                                        <li><a href="#">Contas a Receber</a></li>
                                        <li><a href="#">Comissões</a></li>
                                        <li><a href="#">Motoristas/Caminhões</a></li>
                                        <li><a href="#">Produtos</a></li>
                                    </ul>
                                    <!-- Fim popup botao relatorio -->
                                </li>
                                <!-- Fim botao relatorio -->
                            <?php endif; ?>
                        </ul>
                        <!-- Fim botoes -->
                        <!-- Ferramentas do sistema -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Botao sobre -->
                            <li>
                                <a style="color: #fff;" href="/representacoes/inicio/sobre">Sobre</a>
                            </li>
                            <!-- Fim botao sobre -->
                            <!-- Botao de usuario -->
                            <li class="dropdown">
                                <a href="#" style="color: #fff; font-weight: bold;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $_COOKIE['USER_LOGIN']; ?>
                                    <span class="caret"></span>
                                </a>
                                <!-- Popup usuario -->
                                <ul class="dropdown-menu">
                                    <li class="dropdown-header">Configurações</li>
                                    <?php if ($_COOKIE['USER_LEVEL'] == '1') : ?>
                                        <li><a href="/representacoes/configuracao/parametrizacao">Parametrização</a></li>
                                    <?php endif; ?>
                                    <li><a href="/representacoes/configuracao/dados">Meus Dados</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="/representacoes/login/logout.php">Sair</a></li>
                                </ul>
                                <!-- Fim popup usuario -->
                            </li>
                            <!-- Fim botao de usuario -->
                        </ul>
                        <!-- Fim ferramentas do sistema -->

                    </div><!-- /.navbar-collapse -->

                </div><!-- /.container-fluid -->

            </nav>
            <!-- Fim barra de ferramentas -->
        </header>

        <!-- Conteudo da pagina -->
        <div class="container">
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
                        <div class="col-sm-6">
                            <label for="selOrcamentoVenda">Orçamento de Venda:</label>
                            <select id="selOrcamentoVenda" class="form-control input-sm" style="width: 100%;" onchange="selectOrcVendaChange();">
                                <option value="0">SELECIONAR</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="selRepresentacao">Representação:</label>
                            <select id="selRepresentacao" class="form-control input-sm" style="width: 100%;" onchange="selectRepresentacaoChange();">
                                <option value="0">SELECIONAR</option>
                            </select>
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
        </div>
        <!-- Fim conteudo da pagina -->

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

        <script src="/representacoes/static/lib/jquery/dist/jquery.js"></script>
        <script src="/representacoes/static/lib/bootstrap/dist/js/bootstrap.js"></script>
        <script src="/representacoes/static/js/site.js"></script>
        <script src="/representacoes/static/lib/fancybox/jquery.fancybox.min.js"></script>
        <script src="/representacoes/static/lib/jquery-mask-plugin/dist/jquery.mask.js"></script>
        <script src="/representacoes/static/js/orcamento/frete/detalhes.js"></script>
        <script src="/representacoes/static/js/orcamento/frete/detalhes_add_item.js"></script>

    </body>

</html>
