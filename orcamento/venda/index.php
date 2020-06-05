<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="icon" type="image/png" href="/static/images/logo.png">

        <title>Gerenciar Orçamentos de Vendas - Sistema de Controle de Representações</title>

        <link rel="stylesheet" type="text/css" href="/static/lib/bootstrap/dist/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="/static/lib/fancybox/jquery.fancybox.min.css"/>
        <link rel="stylesheet" type="text/css" href="/static/css/style.css" />
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

                        <a style="color: #fff; font-weight: bold;" class="navbar-brand" href="/inicio">SCR</a>

                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                        <!-- Botoes -->
                        <ul class="nav navbar-nav">

                            <!-- Botao inicio -->
                            <li>
                                <a style="color: #fff;" class="font-navbar" href="/inicio">Início</a>
                            </li>
                            <!-- Fim botao inicio -->
                            <!-- Botao gerenciar -->
                            <li class="dropdown">
                                <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gerenciar <span class="caret"></span></a>
                                <!-- Popup botao gerenciar -->
                                <ul class="dropdown-menu">
                                    <?php if ($_SESSION['USER_LEVEL'] == '1'): ?>
                                        <li><a href="/gerenciar/funcionario">Funcionários</a></li>
                                    <?php endif ?>
                                    <li><a href="/gerenciar/cliente">Clientes</a></li>
                                    <li><a href="/gerenciar/motorista">Motoristas</a></li>
                                    <li><a href="/gerenciar/caminhao">Caminhões</a></li>
                                    <li><a href="/gerenciar/representacao">Representações</a></li>
                                    <li><a href="/gerenciar/produto">Produtos</a></li>
                                    <li><a href="/gerenciar/tipocaminhao">Tipos de Caminhão</a></li>
                                    <li><a href="/gerenciar/categoria">Categorias de Contas</a></li>
                                    <li><a href="/gerenciar/formapagamento">Formas de Pagamento</a></li>
                                </ul>
                                <!-- Fim popup botao gerenciar -->
                            </li>
                            <!-- Fim botao gerenciar-->
                            <!-- Botao orcamento -->
                            <li class="dropdown">
                                <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Orçamento <span class="caret"></span></a>
                                <!-- Popup botao orcamento -->
                                <ul class="dropdown-menu">
                                    <li><a href="/orcamento/venda">Venda</a></li>
                                    <li><a href="/orcamento/frete">Frete</a></li>
                                </ul>
                                <!-- Fim popup botao orcamento -->
                            </li>
                            <!-- Fim botao orcamento -->
                            <!-- Botao pedido -->
                            <li class="dropdown">
                                <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pedido <span class="caret"></span></a>
                                <!-- Popup botao pedido -->
                                <ul class="dropdown-menu">
                                    <li><a href="/pedido/venda/index">Venda</a></li>
                                    <li><a href="/pedido/frete/index">Frete</a></li>
                                    <li><a href="/pedido/status/index">Alterar status</a></li>
                                    <?php if ($_SESSION['USER_LEVEL'] == '1'): ?>
                                        <li><a href="/pedido/autorizar/index">Autorizar Carregamento</a></li>
                                    <?php endif; ?>
                                </ul>
                                <!-- Fim popup botao pedido -->
                            </li>
                            <!-- Fim botao pedido -->
                            <?php if ($_SESSION['USER_LEVEL'] == '1' || $_SESSION['USER_LEVEL'] == '2'): ?>
                                <!-- Botao controlar -->
                                <li class="dropdown">
                                    <a href="#" style="color: #fff;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        Controlar
                                        <span class="caret"></span>
                                    </a>
                                    <!-- Popup botao controlar -->
                                    <ul class="dropdown-menu">
                                        <li><a href="/controlar/contas/pagar/index">Contas a Pagar</a></li>
                                        <li><a href="/controlar/contas/receber/index">Contas a Receber</a></li>
                                        <li><a href="/controlar/lancar/despezas/index">Lançar Despesas</a></li>
                                        <?php if ($_SESSION['USER_LEVEL'] == '1'): ?>
                                            <li><a href="/controlar/comissao/index">Comissões</a></li>
                                        <?php endif; ?>
                                    </ul>
                                    <!-- Fim popup botao controlar -->
                                </li>
                                <!-- Fim botao controlar -->
                            <?php endif; ?>

                            <?php if ($_SESSION['USER_LEVEL'] == '1'): ?>
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
                                <a style="color: #fff;" href="/inicio/sobre">Sobre</a>
                            </li>
                            <!-- Fim botao sobre -->
                            <!-- Botao de usuario -->
                            <li class="dropdown">
                                <a href="#" style="color: #fff; font-weight: bold;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $_SESSION['USER_LOGIN']; ?>
                                    <span class="caret"></span>
                                </a>
                                <!-- Popup usuario -->
                                <ul class="dropdown-menu">
                                    <li class="dropdown-header">Configurações</li>
                                    <?php if ($_SESSION['USER_LEVEL'] == '1') : ?>
                                        <li><a href="/configuracao/parametrizacao">Parametrização</a></li>
                                    <?php endif; ?>
                                    <li><a href="/configuracao/dados">Meus Dados</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="/login/logout.php">Sair</a></li>
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
                    <h4>
                        <b>SCR - Gerenciar Orçamentos de Vendas</b>
                    </h4>
                </div>
            </div>
            <!-- Fim card titulo pagina -->

            <div class="fieldset-card">
                <div class="fieldset-card-legend">Filtragem de Orçamentos</div>

                <div class="fieldset-card-container">
                    <div class="row">
                        <div class="col-sm-8">
                            <label for="filtro">Filtro:</label>
                            <input type="text" id="filtro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição e cliente..." />
                        </div>

                        <div class="col-sm-2">
                            <label for="filtro_data">Filtro Data:</label>
                            <input type="date" id="filtro_data" class="form-control input-sm" style="width: 100%;" />
                        </div>

                        <div class="col-sm-2">
                            <label for="filtrar">&nbsp;</label>
                            <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fieldset-card" style="margin-bottom: 40px;">
                <div class="fieldset-card-legend" style="width: 200px;">Orçamentos Abertos</div>

                <div class="fieldset-card-container">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-sm-10">
                            <label for="cbord">Ordenar por:</label>
                            <select id="cbord" class="form-control input-sm" onchange="ordenar();">
                                <option value="1">REGISTRO (CRESCENTE)</option>
                                <option value="2">REGISTRO (DECRESCENTE)</option>
                                <option value="3">DESCRIÇÂO (CRESCENTE)</option>
                                <option value="4">DESCRIÇÂO (DECRESCENTE)</option>
                                <option value="5">CLIENTE (CRESCENTE)</option>
                                <option value="6">CLIENTE (DECRESCENTE)</option>
                                <option value="7">DATA (CRESCENTE)</option>
                                <option value="8">DATA (DECRESCENTE)</option>
                                <option value="9">ATOR (CRESCENTE)</option>
                                <option value="10">ATOR (DECRESCENTE)</option>
                                <option value="11">VALOR (CRESCENTE)</option>
                                <option value="12">VALOR (DECRESCENTE)</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="novo">&nbsp;</label>
                            <a role="button" id="novo" class="btn btn-success btn-sm" style="width: 100%;" href="/orcamento/venda/novo">NOVO</a>
                        </div>
                    </div>

                    <table id="table_orcamentos" class="table table-responsive" style="width: 100%;">
                        <thead>
                        <tr>
                            <th class="hidden">ID</th>
                            <th>DESCRIÇÃO</th>
                            <th>CLIENTE</th>
                            <th>DATA</th>
                            <th>ATOR</th>
                            <th>VALOR (R$)</th>
                            <th style="width: 2%;">&nbsp;</th>
                            <th style="width: 2%;">&nbsp;</th>
                        </tr>
                        </thead>

                        <tbody id="tbody_orcamentos">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Fim conteudo da pagina -->

        <script src="/static/lib/jquery/dist/jquery.js"></script>
        <script src="/static/lib/bootstrap/dist/js/bootstrap.js"></script>
        <script src="/static/js/site.js"></script>
        <script src="/static/lib/bootbox/bootbox.min.js"></script>
        <script src="/static/js/orcamento/venda/index.js"></script>
    </body>
</html>