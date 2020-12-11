<?php

require '../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    echo "Método inválido.";
}

?>
<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="icon" type="image/png" href="/representacoes/static/images/logo.png">

        <title>Detalhes do Propritário de Caminhão - Sistema de Controle de Representações</title>

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
                                        <li><a href="/representacoes/relatorio/cliente/">Clientes</a></li>
                                        <li><a href="/representacoes/relatorio/pedido/venda">Pedido de Venda</a></li>
                                        <li><a href="/representacoes/relatorio/pedido/frete">Pedido de Frete</a></li>
                                        <li><a href="/representacoes/relatorio/orcamento/venda">Orçamento de Venda</a></li>
                                        <li><a href="/representacoes/relatorio/orcamento/frete">Orçamento de Frete</a></li>
                                        <li><a href="#">Contas a Pagar</a></li>
                                        <li><a href="#">Contas a Receber</a></li>
                                        <li><a href="#">Comissões</a></li>
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
                    <h4>
                        <b>SCR - Detalhes do Proprietário de Caminhão</b>
                    </h4>
                </div>
            </div>
            <!-- Fim card titulo pagina -->

            <div class="fieldset-card">
                <div class="fieldset-card-legend">Vínculo</div>
                <div class="fieldset-card-container">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="select_motorista">Motorista </label>
                            <select id="select_motorista" class="form-control input-sm" onchange="selectMotoristaChange();">
                                <option value="0">SELECIONE</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="select_tipo">Tipo </label>
                            <select id="select_tipo" class="form-control input-sm" disabled>
                                <option value="1">PESSOA FÍSICA</option>
                                <option value="2">PESSOA JURÍDICA</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div id="fisica" class="fieldset-card">
                <div class="fieldset-card-legend">Dados do Proprietário</div>
                <div class="fieldset-card-container">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="text_nome">Nome <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_nome" class="form-control input-sm" style="width: 100%;" onblur="textNomeBlur();" />
                            <div id="msnome"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <label for="text_rg">RG <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_rg" class="form-control input-sm" style="width: 100%;" maxlength="30" onblur="textRgBlur();" />
                            <div id="msrg"></div>
                        </div>

                        <div class="col-sm-4">
                            <label for="text_cpf">CPF <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_cpf" class="form-control input-sm" style="width: 100%;" onblur="textCpfBlur();" />
                            <div id="mscpf"></div>
                        </div>

                        <div class="col-sm-4">
                            <label for="date_nasc">Nascimento <span style="color: red;">*</span>:</label>
                            <input type="date" id="date_nasc" class="form-control input-sm" style="width: 100%;" onblur="dateNascBlur();" />
                            <div id="msnasc"></div>
                        </div>
                    </div>

                    <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
                </div>
            </div>

            <div id="juridica" class="fieldset-card">
                <div class="fieldset-card-legend">Dados do Proprietário</div>
                <div class="fieldset-card-container">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="text_razao_social">Razão Social <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_razao_social" class="form-control input-sm" style="width: 100%;" onblur="textRazaoSocialBlur();" />
                            <div id="msrs"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-9">
                            <label for="text_nome_fantasia">Nome Fantasia <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_nome_fantasia" class="form-control input-sm" style="width: 100%;" onblur="textNomeFantasiaBlur();" />
                            <span id="msnf" class="label label-danger hidden"></span>
                        </div>

                        <div class="col-sm-3">
                            <label for="text_cnpj">CNPJ <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_cnpj" class="form-control input-sm" style="width: 100%;" onblur="textCnpjBlur();" />
                            <div id="mscnpj"></div>
                        </div>
                    </div>

                    <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
                </div>
            </div>

            <div class="fieldset-card">
                <div class="fieldset-card-legend">Dados de contato do funcionario</div>
                <div class="fieldset-card-container">
                    <div class="row">
                        <div class="col-sm-9">
                            <label for="text_rua">Rua <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_rua" class="form-control input-sm" style="width: 100%;" onblur="textRuaBlur();" />
                            <div id="msrua"></div>
                        </div>

                        <div class="col-sm-3">
                            <label for="text_numero">Número <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_numero" class="form-control input-sm" style="width: 100%;" onblur="textNumeroBlur();" />
                            <div id="msnumero"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <label for="text_bairro">Bairro <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_bairro" class="form-control input-sm" style="width: 100%;" onblur="textBairroBlur();" />
                            <div id="msbairro"></div>
                        </div>

                        <div class="col-sm-6">
                            <label for="text_complemento">Complemento:</label>
                            <input type="text" id="text_complemento" class="form-control input-sm" style="width: 100%;" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <label for="select_estado">Estado <span style="color: red;">*</span>:</label>
                            <select id="select_estado" class="form-control input-sm" style="width: 100%;" onchange="selectEstadoChange();" onblur="selectEstadoBlur();">
                                <option value="0">SELECIONAR</option>
                            </select>
                            <div id="msestado"></div>
                        </div>

                        <div class="col-sm-5">
                            <label for="select_cidade">Cidade <span style="color: red;">*</span>:</label>
                            <select id="select_cidade" class="form-control input-sm" style="width: 100%;" onblur="selectCidadeBlur();" >
                                <option value="0">SELECIONAR</option>
                            </select>
                            <div id="mscidade"></div>
                        </div>

                        <div class="col-sm-3">
                            <label for="text_cep">CEP <span style="color: red;">*</span>:</label>
                            <input type="text" id="text_cep" class="form-control input-sm" style="width: 100%;" onblur="textCepBlur();" />
                            <div id="mscep"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <label for="text_tel">Telefone <span style="color: red;">*</span>:</label>
                            <div class="input-group" style="width: 100%;">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span></div>
                                <input type="text" id="text_tel" class="form-control input-sm" onblur="textTelefoneBlur();" />
                            </div>
                            <div id="mstel"></div>
                        </div>

                        <div class="col-sm-3">
                            <label for="text_cel">Celular <span style="color: red;">*</span>:</label>
                            <div class="input-group" style="width: 100%;">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span></div>
                                <input type="text" id="text_cel" class="form-control input-sm" onblur="textCelularBlur();" />
                            </div>
                            <div id="mscel"></div>
                        </div>

                        <div class="col-sm-6">
                            <label for="text_email">E-Mail <span style="color: red;">*</span>:</label>
                            <div class="input-group" style="width: 100%;">
                                <div class="input-group-addon">@</div>
                                <input type="text" id="text_email" class="form-control input-sm" onblur="textEmailBlur();" />
                            </div>
                            <div id="msemail"></div>
                        </div>
                    </div>

                    <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2">
                    <a role="button" id="voltar" class="btn btn-default" style="width: 100%;" href="/representacoes/gerenciar/proprietario">VOLTAR</a>
                </div>

                <div class="col-sm-8"></div>

                <div class="col-sm-2">
                    <button id="salvar" class="btn btn-success" style="width: 100%;" onclick="alterar();">SALVAR</button>
                </div>
            </div>
        </div>
        <!-- Fim conteudo da pagina -->

        <script src="/representacoes/static/lib/jquery/dist/jquery.js"></script>
        <script src="/representacoes/static/lib/bootstrap/dist/js/bootstrap.js"></script>
        <script src="/representacoes/static/js/site.js"></script>
        <script src="/representacoes/static/lib/jquery-mask-plugin/dist/jquery.mask.js"></script>
        <script src="/representacoes/static/js/gerenciar/proprietario/detalhes.js"></script>

    </body>

</html>