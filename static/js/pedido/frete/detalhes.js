const selectOrcFrete = document.getElementById("selectOrcamentoFrete");
const selectPedVenda = document.getElementById('selectPedidoVenda');
const selectRepresentacao = document.getElementById("selectRepresentacao");
const textDesc = document.getElementById("textDescricao");
const selectCliente = document.getElementById('selectCliente');
const selectCidade = document.getElementById("selectCidadeDestino");
const selectEstado = document.getElementById("selectEstadoDestino");
const tbodyItens = document.getElementById("tbodyItens");
const tbodyEtapas = document.getElementById("tbodyEtapas");
const selectTipoCam = document.getElementById("selectTipoCaminhao");
const selectProprietario = document.getElementById('selectProprietario');
const selectCaminhao = document.getElementById('selectCaminhao');
const textDistancia = document.getElementById("textDistancia");
const selectMotorista = document.getElementById('selectMotorista');
const textValorMotorista = document.getElementById('textValorMotorista');
const textValorAdiantamento = document.getElementById('textValorAdiantamento');
const selectFormaAdiantamento = document.getElementById('selectFormaAdiantamento');
const textPesoItens = document.getElementById("textPesoProdutos");
const textValorFrete = document.getElementById("textValorFrete");
const selectForma = document.getElementById('selectForma');
const dateEntrega = document.getElementById("dateEntrega");

let pedido = {};

let tipos = [];

let piso = 0.0;

let itens = [];

let etapas = [];

function limparSelectTipo() {
    for (let i = selectTipoCam.childElementCount - 1; i > 0; i--) {
        selectTipoCam.children.item(i).remove();
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
    } else {
        carregarCidades();
    }
}

async function selectTipoCaminhaoChange() {
    let tipo = Number.parseInt(selectTipoCam.value);

    if (tipo === null || isNaN(tipo) || tipo === 0) {
        selectProprietario.value = 0;
        await selectProprietarioChange();
        selectProprietario.innerHTML = '<option value="0">SELECIONE</option>';
    } else {
        selectProprietario.value = 0;

        let res = await postJSON(
            '/representacoes/pedido/frete/novo/obter-proprietarios-por-tipo.php',
            { tipo: tipo }
        );

        if (res.status) {
            let props = res.response;

            let options = `<option value="0">SELECIONE</option>`;
            for (let i = 0; i < props.length; i++) {
                options +=
                    `<option value="${props[i].id}">
                        ${props[i].tipo === 1 ? props[i].pessoaFisica.nome : props[i].pessoaJuridica.nomeFantasia}
                    </option>`;
            }

            selectProprietario.innerHTML = options;
        } else {
            mostraDialogo(
                res.error.message + "<br />" +
                "Status: "+res.error.code+" "+res.error.status,
                "danger",
                3000
            );
        }
    }
}

async function selectProprietarioChange() {
    let prop = Number.parseInt(selectProprietario.value);
    let tipo = Number.parseInt(selectTipoCam.value);

    if (prop === null || isNaN(prop) || prop === 0) {
        selectCaminhao.value = 0;
        selectCaminhao.innerHTML = '<option value="0">SELECIONE</option>';
        selectCaminhao.disabled = true;
    } else {
        selectCaminhao.disabled = false;

        let res = await postJSON(
            '/representacoes/pedido/frete/novo/obter-caminhoes-por-prop-tc.php',
            {
                prop: prop,
                tipo: tipo
            }
        );

        if (res.status) {
            let caminhoes = res.response;

            let options = `<option value="0">SELECIONE</option>`;
            for (let i = 0; i < caminhoes.length; i++) {
                options +=
                    `<option value="${caminhoes[i].id}">
                        ${caminhoes[i].marca} / ${caminhoes[i].modelo}
                    </option>`;
            }

            selectCaminhao.innerHTML = options;
        } else {
            mostraDialogo(
                res.error.message + "<br />" +
                "Status: "+res.error.code+" "+res.error.status,
                "danger",
                3000
            );
        }
    }
}

async function buttonCancelarClick() {
    await buttonLimparClick();
    location.href = '../../frete';
}

async function buttonLimparClick() {
    textDesc.value = "";
    selectOrcFrete.disabled = false;
    selectOrcFrete.value = 0;
    selectPedVenda.disabled = false;
    selectPedVenda.value = 0;
    selectRepresentacao.disabled = false;
    selectRepresentacao.value = 0;
    selectCliente.value = 0;
    selectCliente.disabled = false;
    selectEstado.value = 0;
    selectEstadoChange();
    selectCidade.value = 0;
    itens = [];
    $(tbodyItens).html("");
    etapas = [];
    $(tbodyEtapas).html('');
    textPesoItens.value = "0,0";
    piso = 0.0;
    textValorFrete.value = "0,00";
    tipos = [];
    limparSelectTipo();
    selectTipoCam.value = 0;
    await selectTipoCaminhaoChange()
    textDistancia.value = "";
    selectMotorista.value = 0;
    textValorMotorista.value = "0,00";
    textValorAdiantamento.value = "";
    selectForma.value = 0;
    dateEntrega.value = "";
}

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
        callback: async function (result) {
            if (result) {
                let res = await postJSON(
                    '/representacoes/pedido/frete/excluir.php',
                    { id: pedido.id }
                );

                if (res.status) {
                    await buttonCancelarClick();
                } else {
                    mostraDialogo(
                        `Código: ${res.error.code}. <br />
                        Erro: ${res.error.message}`,
                        'danger',
                        3000
                    );
                }
            }
        }
    });
}

$(document).ready(async (event) => {
    pedido = get("/representacoes/pedido/frete/detalhes/obter.php");
    if (pedido === null || typeof pedido === "string") {
        alert(pedido);
        location.href = "../../inicio";
    }

    let orcamentos = get('/representacoes/pedido/frete/detalhes/obter-orcamentos.php');
    if (orcamentos !== null && orcamentos.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < orcamentos.length; i++) {
            options +=
                `<option value="${orcamentos[i].id}">
                    ${orcamentos[i].descricao}
                </option>`;
        }

        selectOrcFrete.innerHTML = options;
    }

    let vendas = get("/representacoes/pedido/frete/detalhes/obter-vendas.php");
    if (vendas !== null && vendas.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < vendas.length; i++) {
            options +=
                `<option value="${vendas[i].id}">
                    ${vendas[i].descricao}
                </option>`;
        }

        selectPedVenda.innerHTML = options;
    }

    let representacoes = get('/representacoes/pedido/frete/detalhes/obter-representacoes.php');
    if (representacoes !== null && representacoes.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < representacoes.length; i++) {
            options +=
                `<option value="${representacoes[i].id}">
                    ${representacoes[i].pessoa.nomeFantasia} (${representacoes[i].unidade})
                </option>`;
        }

        selectRepresentacao.innerHTML = options;
    }

    let clientes = get('/representacoes/pedido/frete/detalhes/obter-clientes.php');
    if (clientes !== null && clientes.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < clientes.length; i++) {
            options +=
                `<option value="${clientes[i].id}">
                    ${clientes[i].tipo === 1 ? clientes[i].pessoaFisica.nome : clientes[i].pessoaJuridica.nomeFantasia}
                </option>`;
        }

        selectCliente.innerHTML = options;
    }

    let motoristas = get('/representacoes/pedido/frete/detalhes/obter-motoristas.php');
    if (motoristas !== null && motoristas.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < motoristas.length; i++) {
            options +=
                `<option value="${motoristas[i].id}">
                    ${motoristas[i].pessoa.nome}
                </option>`;
        }

        selectMotorista.innerHTML = options;
    }

    let estados = get('/representacoes/estado/obter.php');
    limparEstados();
    if (estados !== null && estados !== []) {
        for (let i = 0; i < estados.length; i++) {
            let option = document.createElement("option");
            option.value = estados[i].id;
            option.text = estados[i].nome;
            selectEstado.appendChild(option);
        }
    }

    let formasPagamento = get('/representacoes/pedido/frete/detalhes/obter-formas-pagamento.php');
    if (formasPagamento !== null && formasPagamento.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < formasPagamento.length; i++) {
            options +=
                `<option value="${formasPagamento[i].id}">
                    ${formasPagamento[i].descricao}
                </option>`;
        }

        selectFormaAdiantamento.innerHTML = options;
    }

    let formasRecebimento = get('/representacoes/pedido/frete/detalhes/obter-formas-recebimento.php');
    if (formasRecebimento !== null && formasRecebimento.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < formasRecebimento.length; i++) {
            options +=
                `<option value="${formasRecebimento[i].id}">
                    ${formasRecebimento[i].descricao}
                </option>`;
        }

        selectForma.innerHTML = options;
    }

    //await buttonLimparClick();

    if (pedido.orcamento) {
        selectOrcFrete.value = pedido.orcamento.id;
        if (pedido.orcamento.representacao) {
            selectRepresentacao.value = pedido.orcamento.representacao.id;
        }
    } else {
        if (pedido.venda) {
            selectPedVenda.value = pedido.venda.id;
        } else {
            selectRepresentacao.value = pedido.representacao.id;
        }
    }
    selectOrcFrete.disabled = selectRepresentacao.disabled = selectPedVenda.disabled = true;

    textDesc.value = pedido.descricao;
    textDesc.readOnly = true;

    selectCliente.value = pedido.cliente.id;
    selectCliente.disabled = true;

    for (let i = 0; i < pedido.itens.length; i++) {
        await adicionarItem(pedido.itens[i]);
    }

    for (let i = 0; i < pedido.etapas.length; i++) {
        adicionarEtapa(pedido.etapas[i]);
    }

    selectTipoCam.value = pedido.tipoCaminhao.id;
    await selectTipoCaminhaoChange();
    selectTipoCam.disabled = true;

    selectProprietario.value = pedido.proprietario.id;
    await selectProprietarioChange();
    selectProprietario.disabled = true;

    selectCaminhao.value = pedido.caminhao.id;
    selectCaminhao.disabled = true;

    textDistancia.value = pedido.distancia;
    textDistancia.readOnly = true;

    selectEstado.value = pedido.destino.estado.id;
    selectEstadoChange();
    selectEstado.disabled = true;

    selectCidade.value = pedido.destino.id;
    selectCidade.disabled = true;

    selectMotorista.value = pedido.motorista.id;
    selectMotorista.disabled = true;

    textValorMotorista.value = formatarValor(pedido.valorMotorista);
    textValorMotorista.readOnly = true;

    textValorAdiantamento.value = formatarValor(pedido.entradaMotorista);
    textValorAdiantamento.readonly = true;

    selectFormaAdiantamento.value = pedido.formaPagamentoMotorista ? pedido.formaPagamentoMotorista.id : 0;
    selectFormaAdiantamento.disabled = true;

    textPesoItens.value = formatarPeso(pedido.peso);

    textValorFrete.value = formatarValor(pedido.valor);

    selectForma.value = pedido.formaPagamentoFrete.id;

    dateEntrega.value = pedido.entrega;

    $(textValorMotorista).mask('0000000000,00', { reverse: true });
    $(textValorAdiantamento).mask('0000000000,00', { reverse: true });
    $(textValorFrete).mask('0000000000,00', { reverse: true });
});
