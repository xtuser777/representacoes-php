<?php

if (session_status() !== PHP_SESSION_ACTIVE) //Verifica se sessão foi iniciada, senão inicia
{
    session_cache_expire(1440); //Define o limite de sessão para 1 dia (60*24)
    session_start();
}

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
require ROOT . "/src/model/Categoria.php";
require ROOT . "/src/model/FormaPagamento.php";

require ROOT . '/src/util/Singleton.php';
require ROOT . '/src/util/Banco.php';

require ROOT . '/src/dao/EstadoDAO.php';
require ROOT . '/src/dao/CidadeDAO.php';
require ROOT . '/src/dao/EnderecoDAO.php';
require ROOT . '/src/dao/ContatoDAO.php';
require ROOT . '/src/dao/PessoaFisicaDAO.php';
require ROOT . '/src/dao/PessoaJuridicaDAO.php';
require ROOT . '/src/dao/FuncionarioDAO.php';
require ROOT . '/src/dao/NivelDAO.php';
require ROOT . '/src/dao/UsuarioDAO.php';
require ROOT . '/src/dao/ParametrizacaoDAO.php';
require ROOT . '/src/dao/ClienteDAO.php';
require ROOT . '/src/dao/RepresentacaoDAO.php';
require ROOT . "/src/dao/TipoCaminhaoDAO.php";
require ROOT . "/src/dao/ProdutoDAO.php";
require ROOT . "/src/dao/CategoriaDAO.php";
require ROOT . "/src/dao/FormaPagamentoDAO.php";

require ROOT . '/src/control/CidadeControl.php';
require ROOT . '/src/control/EstadoControl.php';
require ROOT . '/src/control/LoginControl.php';
require ROOT . '/src/control/NivelControl.php';
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