<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="icon" type="image/png" href="/static/images/logo.png">

    <title><?php echo $page_title ?> - Sistema de Controle de Representações</title>

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
                            <li><a href="/orcamento/venda/index">Venda</a></li>
                            <li><a href="/orcamento/frete/index">Frete</a></li>
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
    <?php include ROOT . $section_container; ?>
</div>
<!-- Fim conteudo da pagina -->

<footer>
</footer>

<script src="/static/lib/jquery/dist/jquery.js"></script>
<script src="/static/lib/bootstrap/dist/js/bootstrap.js"></script>
<script src="/static/js/site.js"></script>

<?php include ROOT . $section_scripts; ?>
</body>

</html>