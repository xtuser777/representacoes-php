const selectCliente = document.getElementById("select_cliente");
const selectOrcamento = document.getElementById("select_orcamento");
const selectForma = document.getElementById("select_forma");
const textDesc = document.getElementById("text_desc");
const selectVendedor = document.getElementById("select_vendedor");
const selectCidade = document.getElementById("select_cid_dest");
const selectEstado = document.getElementById("select_est_dest");
const tbodyItens = document.getElementById("tbody_itens");
const textPesoItens = document.getElementById("text_peso_itens");
const textValorItens = document.getElementById("text_valor_itens");
const textValorPago = document.getElementById("text_valor_pago");

var itens = [];

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

function selectClienteBlur() {
    let cliente = selectCliente.value;
    cliente = Number.parseInt(cliente);

    if (cliente === null || isNaN(cliente) || cliente === 0) {
        erroCliente = true;
        $("#mscliente").html('<span class="label label-danger">O cliente que solicitou deve ser selecionado.</span>');
    } else {
        erroCliente = false;
        $("#mscliente").html('');
    }
}

function textDescBlur() {
    let desc = textDesc.value.toString();
    if (desc.trim().length === 0) {
        erroDesc = true;
        $("#msdesc").html("<span class='label label-danger'>A Descrição do orçamento precisa ser preenchida.</span>");
    } else {
        erroDesc = false;
        $("#msdesc").html("");
    }
}

function selectEstadoBlur() {
    let estado = selectEstado.value.toString();
    if (estado === "0") {
        erroEstado = true;
        $("#msest").html("<span class='label label-danger'>O Estado de destino precisa ser preenchido.</span>");
    } else {
        erroEstado = false;
        $("#msest").html("");
    }
}

function selectCidadeBlur() {
    let cidade = selectCidade.value.toString();
    if (cidade === "0") {
        erroCidade = true;
        $("#mscid").html("<span class='label label-danger'>A Cidade de destino precida ser preenchida.</span>");
    } else {
        erroCidade = false;
        $("#mscid").html("");
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

function selectFormaBlur() {
    let forma = Number.parseInt(selectForma.value);

    if (forma === null || isNaN(forma) || forma === 0) {
        erroForma = true;
        $("#msforma").html('<span class="label label-danger">A forma de pagamento utilizada deve ser selecionada.</span>');
    } else {
        erroForma = false;
        $("#msforma").html('');
    }
}

function textValorPagoBlur() {
    let valor = Number.parseInt(textValorPago.value.toString().replace(",", "."));

    if (valor === null || isNaN(valor) || valor <= 0) {
        erroValor = true;
        $("#msvalorpago").html('<span class="label label-danger">O valor pago pelo pedido precisa ser preenchido.</span>');
    } else {
        erroValor = false;
        $("#msvalorpago").html('');
    }
}

function buttonClrItensClick() {
    itens = [];
    $(tbodyItens).html('');
    textPesoItens.value = 0.0;
    textValorItens.value = 0.0;
}

function validar() {
    textDescBlur();
    selectClienteBlur();
    selectEstadoBlur();
    selectCidadeBlur();
    selectFormaBlur();
    textValorPagoBlur();

    return (
        !erroCliente && !erroDesc && !erroEstado && !erroCidade && !erroForma && !erroValor
    );
}

function buttonCancelarClick() {
    buttonLimparClick();
    location.href = '../../venda';
}

function buttonLimparClick() {
    selectCliente.value = 0;
    textDesc.value = "";
    selectVendedor.value = 0;
    selectEstado.value = 0;
    selectCidade.value = 0;
    itens = [];
    $(tbodyItens).html("");
    textPesoItens.value = 0;
    textValorItens.value = 0;
    selectForma.value = 0;
    textValorPago.value = 0;

    erroCliente = true;
    erroDesc = true;
    erroEstado = true;
    erroCidade = true;
    erroForma = true;
    erroValor = true;
}

function buttonSalvarClick() {
    let cli = 0;
    let desc = "";
    let vdd = 0;
    let est = 0;
    let cid = 0;
    let peso = 0.0;
    let valor = 0.0;
    let forma = 0;
    let valorPago = 0.0;

    if (validar()) {
        if (itens.length > 0) {
            cli = selectCliente.value;
            desc = textDesc.value;
            vdd = selectVendedor.value;
            cid = selectCidade.value;
            peso = textPesoItens.value;
            valor = textValorItens.value;
            forma = selectForma.value;
            valorPago = Number.parseFloat(textValorPago.value);

            let frm = new FormData();
            frm.append("cli", cli);
            frm.append("desc", desc);
            frm.append("vdd", vdd);
            frm.append("cid", cid);
            frm.append("peso", peso);
            frm.append("valor", valor);
            frm.append("forma", forma);
            frm.append("valorPago", valorPago);
            frm.append("itens", JSON.stringify(itens));

            $.ajax({
                type: "POST",
                url: "/representacoes/pedido/venda/novo/gravar.php",
                data: frm,
                contentType: false,
                processData: false,
                async: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response === "") {
                        mostraDialogo(
                            "<strong>Pedido de venda gravado com sucesso!</strong>" +
                            "<br />Os dados do novo pedido de venda foram salvos com sucesso no banco de dados.",
                            "success",
                            2000
                        );
                        buttonLimparClick();
                    } else {
                        mostraDialogo(
                            "<strong>Problemas ao salvar o novo pedido...</strong>" +
                            "<br/>"+response,
                            "danger",
                            2000
                        );
                    }
                },
                error: function (XMLHttpRequest, txtStatus, errorThrown) {
                    mostraDialogo(
                        "<strong>Ocorreu um erro ao se comunicar com o servidor...</strong>" +
                        "<br/>"+errorThrown,
                        "danger",
                        2000
                    );
                }
            });
        } else {
            mostraDialogo(
                "Adicione produtos ao orçamento.",
                "warn",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Preencha os campos obrigatórios.",
            "warn",
            3000
        );
    }
}

$(document).ready((event) => {
    let prods = get("/representacoes/gerenciar/produto/obter.php");
    if (prods === null || prods.length === 0) {
        alert("Não existem produtos cadastrados!");
        location.href = "../../inicio";
    }

    clientes = get("/representacoes/pedido/venda/novo/obter-clientes.php");
    if (clientes !== "" || clientes !== [] || clientes !== null) {
        for (let i = 0; i < clientes.length; i++) {
            let option = document.createElement("option");
            option.value = clientes[i].id;
            option.text = (clientes[i].tipo === 1) ? clientes[i].pessoaFisica.nome : clientes[i].pessoaJuridica.nomeFantasia;
            selectCliente.appendChild(option);
        }
    }

    let orcamentos = get("/representacoes/pedido/venda/novo/obter-orcamentos.php");
    if (orcamentos !== null && orcamentos.length !== 0) {
        for (let i = 0; i < orcamentos.length; i++) {
            let option = document.getElementById("option");
            option.value = orcamentos[i].id;
            option.text = orcamentos[i].descricao;
            selectOrcamento.appendChild(option);
        }
    }

    let vendedores = get('/representacoes/pedido/venda/novo/obter-vendedores.php');
    if (vendedores !== null && vendedores.length !== 0) {
        for (let i = 0; i < vendedores.length; i++) {
            let option = document.createElement("option");
            option.value = vendedores[i].id;
            option.text = vendedores[i].pessoa.nome;
            selectVendedor.appendChild(option);
        }
    }

    let formas = get("/representacoes/pedido/venda/novo/obter-formas.php");
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

    buttonLimparClick();
    $(textValorPago).mask("000000000,00", { reverse: true });
});