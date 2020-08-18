const selectOrcVenda = document.getElementById("selOrcamentoVenda");
const selectRepresentacao = document.getElementById("selRepresentacao");
const textDesc = document.getElementById("txDescricao");
const selectCidade = document.getElementById("selCidadeDestino");
const selectEstado = document.getElementById("selEstadoDestino");
const tbodyItens = document.getElementById("tbody_itens");
const selectTipoCam = document.getElementById("selTipoCaminhao");
const textDistancia = document.getElementById("txDistancia");
const textPesoItens = document.getElementById("txPesoProdutos");
const textValorFrete = document.getElementById("txValorFrete");
const dateEntrega = document.getElementById("dtEntrega");
const dateValidade = document.getElementById("dtValidade");

var orcamento = 0;

var orcamentos = [];
var tipos = [];

var piso = 0.0;

var itens = [];

var erroDesc = true;
var erroEstado = true;
var erroCidade = true;
var erroTipo = true;
var erroDistancia = true;
var erroValor = true;
var erroEntrega = true;
var erroValidade = true;

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

function limparSelectTipo() {
    for (let i = selectTipoCam.childElementCount - 1; i > 0; i--) {
        selectTipoCam.children.item(i).remove();
    }
}

function selectOrcVendaChange() {
    let ven = Number.parseInt(selectOrcVenda.value);
    if (ven > 0) {
        let orc = orcamentos[ven-1];
        selectRepresentacao.value = 0;
        selectRepresentacao.disabled = true;
        itens = [];
        tipos = [];
        limparSelectTipo();
        textPesoItens.value = 0.0;
        $("#button_clr_itens").prop("disabled", true);
        $("#button_add_item").prop("disabled", true);
        piso = 0.0;
        let valorFormat = piso.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');

        textValorFrete.value = valorFormat;

        let request = new XMLHttpRequest();
        request.open('POST', '/representacoes/orcamento/frete/detalhes/item/obter-por-venda.php', false);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(encodeURI('venda='+ven));

        if (request.DONE === 4 && request.status === 200) {
            let response = JSON.parse(request.responseText);
            if (response !== null && typeof response !== "string" && response.length !== 0) {
                let peso = 0.0;
                for (let i = 0; i < response.length; i++) {
                    peso += response[i].peso;
                    let request = new XMLHttpRequest();
                    request.open('POST', '/representacoes/orcamento/frete/detalhes/item/obter-tipos-por-item.php', false);
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    request.send(encodeURI('item='+response[i].produto.id));

                    if (request.DONE === 4 && request.status === 200) {
                        let res = JSON.parse(request.responseText);
                        if (res !== null && typeof res !== "string" && res.length !== 0) {
                            if (tipos.length === 0) {
                                tipos = res;
                                for (let i = 0; i < res.length; i++) {
                                    let option = document.createElement("option");
                                    option.value = res[i].id;
                                    option.text = res[i].descricao;
                                    selectTipoCam.appendChild(option);
                                }
                            } else {
                                let tmp = [];
                                limparSelectTipo();

                                for (let i = 0; i < res.length; i++) {
                                    if (tipos.findIndex((element) => { return (element.id === res[i].id); }) !== -1) {
                                        tmp.push(res[i]);
                                        let option = document.createElement("option");
                                        option.value = res[i].id;
                                        option.text = res[i].descricao;
                                        selectTipoCam.appendChild(option);
                                    }
                                }
                                tipos = tmp;
                            }
                        } else {
                            mostraDialogo(
                                res,
                                "danger",
                                3000
                            );
                        }
                    } else {
                        mostraDialogo(
                            "Erro na requisição da URL /representacoes/orcamento/frete/detalhes/item/obter-tipos-por-item.php. <br />" +
                            "Status: "+request.status+" "+request.statusText,
                            "danger",
                            3000
                        );
                    }

                    let item = {
                        orcamento: 0,
                        produto: {
                            id: response[i].produto.id,
                            descricao: response[i].produto.descricao,
                            peso: response[i].produto.peso,
                            estado: response[i].produto.representacao.pessoa.contato.endereco.cidade.estado.id,
                            representacao: response[i].produto.representacao.pessoa.nomeFantasia
                        },
                        quantidade: Number(response[i].quantidade),
                        peso: response[i].peso
                    };
                    itens.push(item);
                    preencheTabelaItens(itens);
                }
                textPesoItens.value = peso;
            } else {
                mostraDialogo(
                    res,
                    "danger",
                    3000
                );
            }
        } else {
            mostraDialogo(
                "Erro na requisição da URL /representacoes/orcamento/frete/detalhes/item/obter-por-venda.php. <br />" +
                "Status: "+request.status+" "+request.statusText,
                "danger",
                3000
            );
        }
    } else {
        selectRepresentacao.disabled = false;
        itens = [];
        preencheTabelaItens(itens);
        tipos = [];
        limparSelectTipo();
        textPesoItens.value = 0.0;
        $("#button_clr_itens").prop("disabled", false);
        $("#button_add_item").prop("disabled", false);
        piso = 0.0;
        let valorFormat = piso.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');

        textValorFrete.value = valorFormat;
    }
}

function selectRepresentacaoChange() {
    let rep = Number.parseInt($(selectRepresentacao).val());
    selectOrcVenda.disabled = rep !== 0;

    let nome = selectRepresentacao.innerText;

    if (itens.length !== 0 && itens[0].produto.representacao !== nome) {
        itens = [];
        preencheTabelaItens(itens);
        tipos = [];
        limparSelectTipo();
        textPesoItens.value = "0,0";
        piso = 0.0;
        let valorFormat = piso.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');

        textValorFrete.value = valorFormat;
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

function selectTipoCaminhaoBlur() {
    let tipo = Number.parseInt(selectTipoCam.value);
    if (tipo === 0) {
        erroTipo = true;
        $("#mstipo").html('<span class="label label-danger">O tipo de caminhão deve ser selecionado.</span>');
    } else {
        erroTipo = false;
        $("#mstipo").html('');
    }
}

async function textDistanciaBlur() {
    let dist = Number.parseFloat(textDistancia.value);
    if (dist <= 0 || isNaN(dist)) {
        erroDistancia = true;
        $("#msdist").html('<span class="label label-danger">A distância a percorrer deve ser preenchida.</span>');

        piso = 0.0;
        let valorFormat = piso.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');

        textValorFrete.value = valorFormat;
    } else {
        erroDistancia = false;
        $("#msdist").html('');

        let tipo = tipos.findIndex((element) => { return (element.id === Number.parseInt(selectTipoCam.value)); });

        await $.ajax({
            type: "POST",
            url: "/representacoes/orcamento/frete/detalhes/calcular-piso-minimo.php",
            data: { distancia: dist, eixos: tipos[tipo].eixos },
            success: function (response) {
                if (response <= 0) {
                    mostraDialogo(
                        "Erro ao processar a requisição",
                        "danger",
                        3000
                    );
                } else {
                    let tmp = response.toString();
                    tmp = tmp.replace('.', '#');
                    tmp = tmp.substring(0, tmp.indexOf('#')+3);
                    tmp = tmp.replace('#', '.');
                    piso = Number.parseFloat(tmp);
                }
            },
            error: function (xhr, status, thrown) {
                console.error(thrown);
                mostraDialogo(
                    "Erro ao processar a requisição: <br/>" +
                    "/representacoes/orcamento/frete/detalhes/calcular-piso-minimo.php",
                    "danger",
                    3000
                );
            }
        })

        let valorFormat = piso.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) {
            valorFormat += ',00';
        } else {
            if (valorFormat.search('#') !== -1 && valorFormat.split('#')[1].length > 2) {
                valorFormat = valorFormat.substring(0, valorFormat.indexOf('#') + 3);
                valorFormat = valorFormat.replace('#', ',');
            } else {
                valorFormat = valorFormat.replace('#', ',');
            }
        }

        textValorFrete.value = valorFormat;
    }
}

async function textDistanciaValid() {
    let dist = Number.parseFloat(textDistancia.value);
    if (dist <= 0 || isNaN(dist)) {
        erroDistancia = true;
        $("#msdist").html('<span class="label label-danger">A distância a percorrer deve ser preenchida.</span>');

        piso = 0.0;
        let valorFormat = piso.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');

        textValorFrete.value = valorFormat;
    } else {
        erroDistancia = false;
        $("#msdist").html('');

        await $.ajax({
            type: "POST",
            url: "/representacoes/orcamento/frete/detalhes/calcular-piso-minimo.php",
            data: { distancia: dist, eixos: tipos[tipos.findIndex((element) => { return (element.id === Number.parseInt(selectTipoCam.value)); })].eixos },
            success: function (response) {
                if (response <= 0) {
                    mostraDialogo(
                        "Erro ao processar a requisição",
                        "danger",
                        3000
                    );
                } else {
                    let tmp = response.toString();
                    tmp = tmp.replace('.', '#');
                    tmp = tmp.substring(0, tmp.indexOf('#')+3);
                    tmp = tmp.replace('#', '.');
                    piso = Number.parseFloat(tmp);
                }
            },
            error: function (xhr, status, thrown) {
                console.error(thrown);
                mostraDialogo(
                    "Erro ao processar a requisição: <br/>/representacoes/orcamento/frete/detalhes/calcular-piso-minimo.php",
                    "danger",
                    3000
                );
            }
        });
    }
}

function textValorFreteBlur() {
    let valor = $(textValorFrete).val();
    if (valor.length === 0 || valor === "0,00" || valor === 0 || valor === 0.0) {
        erroValor = true;
        $("#msvalor").html('<span class="label label-danger">O valor do orçamento deve ser preenchido.</span>');
    } else {
        if (piso === 0.0) {
            erroValor = true;
            $("#msvalor").html('<span class="label label-danger">Selecione o tipo de caminhão e a distância para calcular o piso.</span>');
        } else {
            if (Number.parseFloat(valor.replace(',', '.')) < piso) {
                erroValor = true;
                $("#msvalor").html('<span class="label label-danger">O valor do orçamento menor que o piso ('+piso+').</span>');
            } else {
                erroValor = false;
                $("#msvalor").html('');
            }
        }
    }
}

function dateEntregaBlur() {
    let entrega = dateEntrega.value.toString();
    if (entrega.length === 0) {
        erroEntrega = true;
        $("#msentrega").html('<span class="label label-danger">A data aproximada de entrega precisa ser preenchida.</span>');
    } else {
        let dateEntrega = new Date(entrega + " 23:59:00");
        if (dateEntrega < new Date()) {
            erroEntrega = true;
            $("#msentrega").html('<span class="label label-danger">A data é inválida (menor que a atual).</span>');
        } else {
            erroEntrega = false;
            $("#msentrega").html('');
        }
    }
}

function dateValidadeBlur() {
    let validade = dateValidade.value.toString();
    if (validade.trim().length === 0) {
        erroValidade = true;
        $("#msvalid").html("<span class='label label-danger'>A data de vencimento do orçamento precisa ser preenchida.</span>");
    } else {
        let dateVal = new Date(validade + " 23:59:00");
        if (dateVal < new Date()) {
            erroValidade = true;
            $("#msvalid").html("<span class='label label-danger'>A data de vencimento é inválida (menor que a atual).</span>");
        } else {
            erroValidade = false;
            $("#msvalid").html("");
        }
    }
}

function buttonClrItensClick() {
    itens = [];
    $(tbodyItens).html('');
    textPesoItens.value = 0.0;
    textValorItens.value = 0.0;
}

async function validar() {
    textDescBlur();
    selectEstadoBlur();
    selectCidadeBlur();
    selectTipoCaminhaoBlur();
    await textDistanciaValid();
    textValorFreteBlur();
    dateEntregaBlur();
    dateValidadeBlur();

    return (
        !erroDesc && !erroEstado && !erroCidade && !erroTipo && !erroDistancia && !erroValor && !erroEntrega && !erroValidade
    );
}

function buttonCancelarClick() {
    buttonLimparClick();
    location.href = '../../frete';
}

function buttonLimparClick() {
    textDesc.value = "";
    selectOrcVenda.value = 0;
    selectRepresentacao.disabled = false;
    selectRepresentacao.value = 0;
    selectEstado.value = 0;
    selectCidade.value = 0;
    itens = [];
    $(tbodyItens).html("");
    $("#button_clr_itens").prop("disabled", false);
    $("#button_add_item").prop("disabled", false);
    textPesoItens.value = "0,0";
    piso = 0.0;
    textValorFrete.value = "0,00";
    tipos = [];
    limparSelectTipo();
    selectTipoCam.value = 0;
    textDistancia.value = "";
    dateEntrega.value = "";
    dateValidade.value = "";

    erroDesc = true;
    erroEstado = true;
    erroCidade = true;
    erroTipo = true;
    erroDistancia = true;
    erroValor = true;
    erroEntrega = true;
    erroValidade = true;
}

async function buttonSalvarClick() {
    let desc = "";
    let ven = 0;
    let rep = 0;
    let est = 0;
    let cid = 0;
    let tip = 0;
    let dist = 0;
    let peso = 0.0;
    let valor = 0.0;
    let entrega = "";
    let venc = "";

    if (await validar()) {
        if (itens.length > 0) {
            desc = textDesc.value;
            ven = selectOrcVenda.value;
            rep = selectRepresentacao.value;
            cid = selectCidade.value;
            tip = selectTipoCam.value;
            dist = textDistancia.value;
            peso = textPesoItens.value.toString().replace(',', '.');
            valor = textValorFrete.value.toString().replace(',', '.');
            entrega = dateEntrega.value;
            venc = dateValidade.value;

            let frm = new FormData();
            frm.append("orc", orcamento);
            frm.append("desc", desc);
            frm.append("ven", ven);
            frm.append("rep", rep);
            frm.append("cid", cid);
            frm.append("tip", tip);
            frm.append("dist", dist);
            frm.append("peso", peso);
            frm.append("valor", valor);
            frm.append("entrega", entrega);
            frm.append("venc", venc);
            frm.append("itens", JSON.stringify(itens));

            $.ajax({
                type: "POST",
                url: "/representacoes/orcamento/frete/detalhes/alterar.php",
                data: frm,
                contentType: false,
                processData: false,
                async: false,
                success: function(response) {
                    if (response === "") {
                        mostraDialogo(
                            "<strong>Orçamento de frete alterado com sucesso!</strong>" +
                            "<br />Os dados do orçamento de frete foram salvos com sucesso no banco de dados.",
                            "success",
                            2000
                        );
                    } else {
                        mostraDialogo(
                            "<strong>Problemas ao salvar o orçamento...</strong>" +
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
                "warning",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Preencha os campos obrigatórios.",
            "warning",
            3000
        );
    }
}

$(document).ready(async (event) => {
    let prods = get("/representacoes/gerenciar/produto/obter.php");
    if (prods === null || prods.length === 0) {
        alert("Não existem produtos cadastrados!");
        location.href = "../../inicio";
    }

    orcamentos = get("/representacoes/orcamento/frete/detalhes/obter-vendas.php");
    if (orcamentos !== "" && orcamentos !== [] && orcamentos !== null) {
        for (let i = 0; i < orcamentos.length; i++) {
            let option = document.createElement("option");
            option.value = orcamentos[i].id;
            option.text = orcamentos[i].descricao;
            selectOrcVenda.appendChild(option);
        }
    }

    let representacoes = get('/representacoes/orcamento/frete/detalhes/obter-representacoes.php');
    if (representacoes !== "" && representacoes !== [] && representacoes !== null) {
        for (let i = 0; i < representacoes.length; i++) {
            let option = document.createElement("option");
            option.value = representacoes[i].id;
            option.text = representacoes[i].pessoa.nomeFantasia;
            selectRepresentacao.appendChild(option);
        }
    }

    let estados = get('/representacoes/estado/obter.php');
    limparEstados();
    if (estados !== null && estados.length !== 0) {
        for (let i = 0; i < estados.length; i++) {
            let option = document.createElement("option");
            option.value = estados[i].id;
            option.text = estados[i].nome;
            selectEstado.appendChild(option);
        }
    }

    buttonLimparClick();

    let detalhes = get("/representacoes/orcamento/frete/detalhes/obter.php");
    if (detalhes !== null) {
        orcamento = detalhes.id;
        $(textDesc).val(detalhes.descricao);
        if (detalhes.orcamentoVenda !== null) {
            $(selectOrcVenda).val(detalhes.orcamentoVenda.id);
            selectOrcVendaChange();

            $(selectEstado).val(detalhes.destino.estado.id);
            selectEstadoChange();
            $(selectCidade).val(detalhes.destino.id);
            selectTipoCam.value = detalhes.tipoCaminhao.id;
            $(textDistancia).val(detalhes.distancia);
            await textDistanciaBlur();
            $(textValorFrete).val(detalhes.valor);
            $(dateEntrega).val(detalhes.entrega);
            $(dateValidade).val(detalhes.validade);
        } else {
            $(selectRepresentacao).val(detalhes.representacao.id);
            selectRepresentacaoChange();

            let request = new XMLHttpRequest();
            request.open('POST', '/representacoes/orcamento/frete/detalhes/item/obter.php', false);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.send(encodeURI('orcamento='+detalhes.id));

            if (request.DONE === 4 && request.status === 200) {
                let response = JSON.parse(request.responseText);
                if (response !== null && typeof response !== "string" && response.length !== 0) {
                    let peso = 0.0;
                    for (let i = 0; i < response.length; i++) {
                        peso += response[i].peso;
                        let request = new XMLHttpRequest();
                        request.open('POST', '/representacoes/orcamento/frete/detalhes/item/obter-tipos-por-item.php', false);
                        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        request.send(encodeURI('item='+response[i].produto.id));

                        if (request.DONE === 4 && request.status === 200) {
                            let res = JSON.parse(request.responseText);
                            if (res !== null && typeof res !== "string" && res.length !== 0) {
                                if (tipos.length === 0) {
                                    tipos = res;
                                    for (let i = 0; i < res.length; i++) {
                                        let option = document.createElement("option");
                                        option.value = res[i].id;
                                        option.text = res[i].descricao;
                                        selectTipoCam.appendChild(option);
                                    }
                                } else {
                                    let tmp = [];
                                    limparSelectTipo();

                                    for (let i = 0; i < res.length; i++) {
                                        if (tipos.findIndex((element) => { return (element.id === res[i].id); }) !== -1) {
                                            tmp.push(res[i]);
                                            let option = document.createElement("option");
                                            option.value = res[i].id;
                                            option.text = res[i].descricao;
                                            selectTipoCam.appendChild(option);
                                        }
                                    }
                                    tipos = tmp;
                                }
                            } else {
                                mostraDialogo(
                                    res,
                                    "danger",
                                    3000
                                );
                            }
                        } else {
                            mostraDialogo(
                                "Erro na requisição da URL /representacoes/orcamento/frete/detalhes/item/obter-tipos-por-item.php. <br />" +
                                "Status: "+request.status+" "+request.statusText,
                                "danger",
                                3000
                            );
                        }

                        let item = {
                            orcamento: 0,
                            produto: {
                                id: response[i].produto.id,
                                descricao: response[i].produto.descricao,
                                peso: response[i].produto.peso,
                                estado: response[i].produto.representacao.pessoa.contato.endereco.cidade.estado.id,
                                representacao: response[i].produto.representacao.pessoa.nomeFantasia
                            },
                            quantidade: Number(response[i].quantidade),
                            peso: response[i].peso
                        };
                        itens.push(item);
                        preencheTabelaItens(itens);
                    }
                    textPesoItens.value = peso;

                    $(selectEstado).val(detalhes.destino.estado.id);
                    selectEstadoChange();
                    $(selectCidade).val(detalhes.destino.id);
                    selectTipoCam.value = detalhes.tipoCaminhao.id;
                    $(textDistancia).val(detalhes.distancia);
                    await textDistanciaBlur();
                    $(textValorFrete).val(detalhes.valor);
                    $(dateEntrega).val(detalhes.entrega);
                    $(dateValidade).val(detalhes.validade);
                } else {
                    mostraDialogo(
                        res,
                        "danger",
                        3000
                    );
                }
            } else {
                mostraDialogo(
                    "Erro na requisição da URL /representacoes/orcamento/frete/detalhes/item/obter.php. <br />" +
                    "Status: "+request.status+" "+request.statusText,
                    "danger",
                    3000
                );
            }
        }
    }
});