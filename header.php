<?php

//Diretório raiz do projeto para todas as pastas
define('ROOT', __DIR__);

require ROOT . '/src/model/Estado.php';
require ROOT . '/src/model/Cidade.php';
require ROOT . '/src/model/Endereco.php';
require ROOT . '/src/model/Contato.php';
require ROOT . '/src/model/PessoaFisica.php';
require ROOT . '/src/model/PessoaJuridica.php';
require ROOT . '/src/model/Funcionario.php';
require ROOT . '/src/model/Nivel.php';
require ROOT . '/src/model/Usuario.php';
require ROOT . '/src/model/Parametrizacao.php';
require ROOT . '/src/model/Cliente.php';
require ROOT . '/src/model/Representacao.php';
require ROOT . "/src/model/TipoCaminhao.php";
require ROOT . "/src/model/Produto.php";
require ROOT . "/src/model/CategoriaContaPagar.php";
require ROOT . "/src/model/FormaPagamento.php";
require ROOT . "/src/model/DadosBancarios.php";
require ROOT . "/src/model/Motorista.php";
require ROOT . "/src/model/Caminhao.php";
require ROOT . "/src/model/Proprietario.php";
require ROOT . "/src/model/OrcamentoVenda.php";
require ROOT . "/src/model/ItemOrcamentoVenda.php";
require ROOT . "/src/model/OrcamentoFrete.php";
require ROOT . "/src/model/ItemOrcamentoFrete.php";
require ROOT . "/src/model/PedidoVenda.php";
require ROOT . "/src/model/ItemPedidoVenda.php";
require ROOT . "/src/model/ItemPedidoFrete.php";
require ROOT . "/src/model/PedidoFrete.php";
require ROOT . "/src/model/ContaPagar.php";
require ROOT . "/src/model/ContaReceber.php";
require ROOT . "/src/model/Evento.php";
require ROOT . "/src/model/Status.php";
require ROOT . "/src/model/StatusPedido.php";
require ROOT . "/src/model/EtapaCarregamento.php";

require ROOT . '/src/util/Banco.php';
require ROOT . "/helpers/fpdf/fpdf.php";
require ROOT . "/src/util/Autorizacao.php";
require ROOT . "/src/util/Retatorio.php";

require ROOT . '/src/dao/EnderecoDAO.php';
require ROOT . '/src/dao/ContatoDAO.php';
require ROOT . '/src/dao/PessoaFisicaDAO.php';
require ROOT . '/src/dao/PessoaJuridicaDAO.php';
require ROOT . '/src/dao/FuncionarioDAO.php';
require ROOT . '/src/dao/UsuarioDAO.php';
require ROOT . '/src/dao/ParametrizacaoDAO.php';
require ROOT . '/src/dao/ClienteDAO.php';
require ROOT . '/src/dao/RepresentacaoDAO.php';
require ROOT . "/src/dao/TipoCaminhaoDAO.php";
require ROOT . "/src/dao/DadosBancariosDAO.php";
require ROOT . "/src/dao/MotoristaDAO.php";

require ROOT . '/src/control/CidadeControl.php';
require ROOT . '/src/control/EstadoControl.php';
require ROOT . '/src/control/LoginControl.php';
require ROOT . '/src/control/NivelControl.php';
require ROOT . "/src/control/InicioControl.php";
require ROOT . '/src/control/FuncionarioControl.php';
require ROOT . '/src/control/FuncionarioNovoControl.php';
require ROOT . '/src/control/FuncionarioDetalhesControl.php';
require ROOT . '/src/control/ConfiguracaoParametrizacaoControl.php';
require ROOT . '/src/control/ConfiguracoesDadosControl.php';
require ROOT . '/src/control/ClienteControl.php';
require ROOT . '/src/control/ClienteNovoControl.php';
require ROOT . '/src/control/ClienteDetalhesControl.php';
require ROOT . '/src/control/RepresentacaoControl.php';
require ROOT . '/src/control/RepresentacaoNovoControl.php';
require ROOT . '/src/control/RepresentacaoDetalhesControl.php';
require ROOT . '/src/control/RepresentacaoAddUnidadeControl.php';
require ROOT . "/src/control/TipoCaminhaoControl.php";
require ROOT . "/src/control/TipoCaminhaoNovoControl.php";
require ROOT . "/src/control/TipoCaminhaoDetalhesControl.php";
require ROOT . "/src/control/ProdutoControl.php";
require ROOT . "/src/control/ProdutoNovoControl.php";
require ROOT . "/src/control/ProdutoDetalhesControl.php";
require ROOT . "/src/control/ProdutoTipoCaminhaoControl.php";
require ROOT . "/src/control/CategoriaControl.php";
require ROOT . "/src/control/CategoriaNovoControl.php";
require ROOT . "/src/control/CategoriaDetalhesControl.php";
require ROOT . "/src/control/FormaPagamentoControl.php";
require ROOT . "/src/control/FormaPagamentoNovoControl.php";
require ROOT . "/src/control/FormaPagamentoDetalhesControl.php";
require ROOT . "/src/control/MotoristaControl.php";
require ROOT . "/src/control/MotoristaNovoControl.php";
require ROOT . "/src/control/MotoristaDetalhesControl.php";
require ROOT . "/src/control/CaminhaoControl.php";
require ROOT . "/src/control/CaminhaoNovoControl.php";
require ROOT . "/src/control/CaminhaoDetalhesControl.php";
require ROOT . "/src/control/OrcamentoVendaControl.php";
require ROOT . "/src/control/OrcamentoVendaNovoControl.php";
require ROOT . "/src/control/OrcamentoVendaNovoItemControl.php";
require ROOT . "/src/control/OrcamentoVendaDetalhesControl.php";
require ROOT . "/src/control/OrcamentoVendaDetalhesItemControl.php";
require ROOT . "/src/control/ProprietarioControl.php";
require ROOT . "/src/control/ProprietarioNovoControl.php";
require ROOT . "/src/control/ProprietarioDetalhesControl.php";
require ROOT . "/src/control/OrcamentoFreteControl.php";
require ROOT . "/src/control/OrcamentoFreteNovoControl.php";
require ROOT . "/src/control/OrcamentoFreteNovoItemControl.php";
require ROOT . "/src/control/OrcamentoFreteDetalhesControl.php";
require ROOT . "/src/control/OrcamentoFreteDetalhesItemControl.php";
require ROOT . "/src/control/LancarDespesasControl.php";
require ROOT . "/src/control/LancarDespesasNovoControl.php";
require ROOT . "/src/control/ContasPagarControl.php";
require ROOT . "/src/control/ContasPagarDetalhesControl.php";
require ROOT . "/src/control/PedidoVendaControl.php";
require ROOT . "/src/control/PedidoVendaNovoControl.php";
require ROOT . "/src/control/PedidoVendaNovoItemControl.php";
require ROOT . "/src/control/PedidoVendaDetalhesControl.php";
require ROOT . "/src/control/ContasReceberControl.php";
require ROOT . "/src/control/ContasReceberDetalhesControl.php";
require ROOT . "/src/control/PedidoFreteControl.php";
require ROOT . "/src/control/PedidoFreteNovoControl.php";
require ROOT . "/src/control/PedidoFreteNovoItemControl.php";
require ROOT . "/src/control/PedidoFreteDetalhesControl.php";
require ROOT . "/src/control/PedidoFreteDetalhesItemControl.php";
require ROOT . "/src/control/PedidoStatusControl.php";
require ROOT . "/src/control/PedidoStatusAlterarControl.php";
require ROOT . "/src/control/PedidoAutorizarControl.php";
require ROOT . "/src/control/PedidoAutorizarVisualizarControl.php";
require ROOT . "/src/control/RelatorioClienteControl.php";
require ROOT . "/src/control/RelatorioPedidoVendaControl.php";
require ROOT . "/src/control/RelatorioPedidoFreteControl.php";
require ROOT . "/src/control/RelatorioOrcamentoVendaControl.php";
require ROOT . "/src/control/RelatorioOrcamentoFreteControl.php";
require ROOT . "/src/control/RelatorioContasPagarControl.php";
require ROOT . "/src/control/RelatorioContasReceberControl.php";
require ROOT . "/src/control/RelatorioProdutoControl.php";