const selectCliente = document.getElementById("select_cliente");
const selectOrcamento = document.getElementById("select_orcamento");
const selectForma = document.getElementById("select_forma");
const textDesc = document.getElementById("text_desc");
const selectVendedor = document.getElementById("select_vendedor");
const selectCidade = document.getElementById("select_cid_dest");
const selectEstado = document.getElementById("select_est_dest");
const tbodyItens = document.getElementById("tbody_itens");
const textPesoItens = document.getElementById("text_peso_itens");
const textPorcentagemComisaoVendedor = document.getElementById("textPorcentagemComissaoVendedor");
const tableComissoes = document.getElementById("tableComissoes");
const tbodyComissoes = document.getElementById("tbodyComissoes");
const textValorItens = document.getElementById("text_valor_itens");
const textValorPago = document.getElementById("text_valor_pago");

var _pedido = {};

var itens = [];

var comissoes = [];

let erroCliente = true;
let erroDesc = true;
let erroEstado = true;
let erroCidade = true;
let erroForma = true;
let erroValor = true;

function get(url_i) {
    let res;
    $.ajax({
        type: 'GET',
        url: url_i,
        async: false,
        contentType: 'application/json',
        dataType: 'json',
        success: function (result) {res = result;},
        error: function (xhr, status, thrown) {
            console.error(thrown);
            alert(thrown);
        }
    });

    return res;
}

function selecionarVendedor() {
    let vdd = Number.parseInt(selectVendedor.value);

    if (vdd === 0 || isNaN(vdd)) {
        textPorcentagemComisaoVendedor.value = 0;
        textPorcentagemComisaoVendedor.disabled = true;
    } else {
        textPorcentagemComisaoVendedor.value = 1;
        textPorcentagemComisaoVendedor.disabled = false;
    }
}

function limparEstados() {
    for (let i = selectEstado.childElementCount - 1; i > 0; i--) {
        selectEstado.children.item(i).remove();
    }
}

function limparCidades() {
    for (let i = selectCidade.childElementCount - 1; i > 0; i--) {
        selectCidade.children.item(i).remove();
    }
}

function carregarCidades() {
    let cidades = [];

    $.ajax({
        type: 'POST',
        url: '/representacoes/cidade/obter-por-estado.php',
        data: {
            estado: selectEstado.value
        },
        async: false,
        success: function (response) {
            cidades = response;
        },
        error: function (err) {
            mostraDialogo(
                "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                "<br/>Um problema no servidor impediu sua comunicação...",
                "danger",
                2000
            );
        }
    });

    limparCidades();
    if (cidades !== "") {
        for (let i = 0; i < cidades.length; i++) {
            let option = document.createElement("option");
            option.value = cidades[i].id;
            option.text = cidades[i].nome;
            selectCidade.appendChild(option);
        }
    }
}

function selectEstadoChange() {
    if (selectEstado.value === "0") {
        limparCidades();
        selectCidade.disabled = true;
    } else {
        carregarCidades();
        selectCidade.disabled = false;
    }
}

function buttonCancelarClick() {
    buttonLimparClick();
    location.href = '../../venda';
}

function buttonLimparClick() {
    selectOrcamento.value = 0;
    selectCliente.value = 0;
    textDesc.value = "";
    selectEstado.value = 0;
    selectCidade.value = 0;
    itens = [];
    $(tbodyItens).html("");
    comissoes = [];
    $(tbodyComissoes).html("");
    selectVendedor.value = 0;
    selecionarVendedor();
    textPesoItens.value = "0,0";
    textValorItens.value = "0,00";
    selectForma.value = 0;
    textValorPago.value = 0;

    erroCliente = true;
    erroDesc = true;
    erroEstado = true;
    erroCidade = true;
    erroForma = true;
    erroValor = true;
}

function carregarComissaoVendedor(pedidoId, valorPedido) {
    $.ajax({
        type: "POST",
        url: "/representacoes/pedido/venda/detalhes/obter-comissao-vendedor.php",
        data: {
            pedido: pedidoId
        },
        async: false,
        success: (response) => {
            let porc = response.valor * 100 / valorPedido;
            textPorcentagemComisaoVendedor.value = porc;
        },
        error: (xhr, status, thrown) => {
            console.error(thrown);
            mostraDialogo(
                "Erro no processamento da requisição, Código "+status,
                "danger",
                3000
            );
        }
    });
}

function carregarComissoesVenda(pedidoId) {
    $.ajax({
        type: "POST",
        url: "/representacoes/pedido/venda/detalhes/obter-comissoes-venda.php",
        data: {
            pedido: pedidoId
        },
        async: false,
        success: (response) => {
            let totalRepresentacao = 0.0;
            for (let i = 0; i < response.length; i++) {
                let comissao = {
                    representacao: {
                        id: response[i].representacao.id,
                        nomeFantasia: response[i].representacao.pessoa.nomeFantasia
                    },
                    valor: 0.0,
                    porcentagem: 0
                };

                for (let j = 0; j < itens.length; j++) {
                    if (itens[j].produto.representacao.id === comissao.representacao.id) {
                        totalRepresentacao += itens[j].valor;
                    }
                }

                let porcentagem = response[i].valor * 100 / totalRepresentacao;

                comissao.valor = truncate(totalRepresentacao);
                comissao.porcentagem = porcentagem;

                comissoes.push(comissao);

                totalRepresentacao = 0.0;
            }

            preencheTabelaComissoes(comissoes);
        },
        error: (xhr, status, thrown) => {
            console.error(thrown);
            mostraDialogo(
                "Erro no processamento da requisição, Código "+status,
                "danger",
                3000
            );
        }
    });
}

function carregarRecebimentoVenda(pedidoId) {
    $.ajax({
        type: "POST",
        url: "/representacoes/pedido/venda/detalhes/obter-recebimento-venda.php",
        data: {
            pedido: pedidoId
        },
        async: false,
        success: (response) => {
            textValorPago.value = formatarValor(response.valorRecebido);
        },
        error: (xhr, status, thrown) => {
            console.error(thrown);
            mostraDialogo(
                "Erro no processamento da requisição, Código "+status,
                "danger",
                3000
            );
        }
    });
}

$(document).ready((event) => {
    clientes = get("/representacoes/pedido/venda/detalhes/obter-clientes.php");
    if (clientes !== "" || clientes !== [] || clientes !== null) {
        for (let i = 0; i < clientes.length; i++) {
            let option = document.createElement("option");
            option.value = clientes[i].id;
            option.text = (clientes[i].tipo === 1) ? clientes[i].pessoaFisica.nome : clientes[i].pessoaJuridica.nomeFantasia;
            selectCliente.appendChild(option);
        }
    }

    let orcamentos = get("/representacoes/pedido/venda/detalhes/obter-orcamentos.php");
    if (orcamentos !== null && orcamentos.length !== 0) {
        for (let i = 0; i < orcamentos.length; i++) {
            let option = document.createElement("option");
            option.value = orcamentos[i].id;
            option.text = orcamentos[i].descricao;
            selectOrcamento.appendChild(option);
        }
    }

    let vendedores = get('/representacoes/pedido/venda/detalhes/obter-vendedores.php');
    if (vendedores !== null && vendedores.length !== 0) {
        for (let i = 0; i < vendedores.length; i++) {
            let option = document.createElement("option");
            option.value = vendedores[i].id;
            option.text = vendedores[i].pessoa.nome;
            selectVendedor.appendChild(option);
        }
    }

    let formas = get("/representacoes/pedido/venda/detalhes/obter-formas.php");
    if (formas !== null && formas.length !== 0) {
        for (let i = 0; i < formas.length; i++) {
            let option = document.createElement("option");
            option.value = formas[i].id;
            option.text = formas[i].descricao;
            selectForma.appendChild(option);
        }
    }

    let estados = get('/representacoes/estado/obter.php');
    limparEstados();
    if (estados !== "" || estados !== [] || estados !== null) {
        for (let i = 0; i < estados.length; i++) {
            let option = document.createElement("option");
            option.value = estados[i].id;
            option.text = estados[i].nome;
            selectEstado.appendChild(option);
        }
    }

    selecionarVendedor();

    buttonLimparClick();
    $(textValorPago).mask("000000000,00", { reverse: true });

    _pedido = get("/representacoes/pedido/venda/detalhes/obter.php");
    if (_pedido === null || typeof _pedido === "string") {
        alert("Nenhum pedido selecionado, voltando ao controle de pedidos de venda.");
        location.href = "../../index.php";
    } else {
        if (_pedido.orcamento) {
            selectOrcamento.value = _pedido.orcamento.id.toString();
        }
        textDesc.value = _pedido.descricao;
        selectCliente.value = _pedido.cliente.id;
        selectEstado.value = _pedido.destino.estado.id;
        selectEstadoChange();
        selectCidade.value = _pedido.destino.id;
        itens = _pedido.itens;
        preencheTabelaItens(_pedido.itens);
        selectVendedor.value = _pedido.vendedor ? _pedido.vendedor.id : 0;
        selecionarVendedor();
        carregarComissaoVendedor(_pedido.id, _pedido.valor);
        carregarComissoesVenda(_pedido.id);
        textPesoItens.value = formatarPeso(_pedido.peso);
        textValorItens.value = formatarValor(_pedido.valor);
        selectForma.value = _pedido.formaPagamento.id;
        carregarRecebimentoVenda(_pedido.id);

        selectOrcamento.disabled = true;
        textDesc.readOnly = true;
        selectCliente.disabled = true;
        selectEstado.disabled = true;
        selectCidade.disabled = true;
        selectVendedor.disabled = true;
        textPorcentagemComisaoVendedor.readOnly = true;
        selectForma.disabled = true;
        textValorPago.readOnly = true;
    }
});

function excluir() {
    bootbox.confirm({
        message: "Confirma a exclusão deste pedido?",
        buttons: {
            confirm: {
                label: 'Sim',
                className: 'btn-success'
            },
            cancel: {
                label: 'Não',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: '/representacoes/pedido/venda/excluir.php',
                    data: {
                        id: _pedido.id
                    },
                    success: function (result) {
                        if (result === "") {
                            buttonCancelarClick();
                        } else {
                            mostraDialogo(
                                "<strong>Ocorreu um problema ao excluir este pedido.</strong><br />" +
                                result,
                                "danger",
                                3000
                            );
                        }
                    },
                    error: function (XMLHttpRequest, txtStatus, errorThrown) {
                        alert("Status: " + txtStatus);
                        alert("Error: " + errorThrown);
                    }
                });
            }
        }
    });
}