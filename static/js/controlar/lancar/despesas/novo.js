const txEmpresa = document.getElementById("txEmpresa");
const slCategoria = document.getElementById("slCategoria");
const slPedido = document.getElementById("slPedido");
const txConta = document.getElementById("txConta");
const txDescricao = document.getElementById("txDescricao");
const slTipo = document.getElementById("slTipo");
const slFormaPagamento = document.getElementById("slFormaPagamento");
const txIntervaloParcelas = document.getElementById("txIntervaloParcelas");
const slFrequencia = document.getElementById("slFrequencia");
const dtDespesa = document.getElementById("dtDespesa");
const txValorPago = document.getElementById("txValorPago");
const txParcelas = document.getElementById("txParcelas");
const txValor = document.getElementById("txValor");
const dtVencimento = document.getElementById("dtVencimento");

const forma = document.getElementById("forma");
const intervalo = document.getElementById("intervalo");
const frequencia = document.getElementById("frequencia");
const pago = document.getElementById("pago");
const parcelas = document.getElementById("parcelas");

let conta = 0;

let erroEmpresa = true;
let erroCategoria = true;
let erroDescricao = true;
let erroTipo = true;
let erroFormaPagamento = true;
let erroIntervalo = true;
let erroFrequencia = true;
let erroData = true;
let erroValorPago = true;
let erroParcelas = true;
let erroValor = true;
let erroVencimento = true;

function validarEmpresa(event) {
    let empresa = txEmpresa.value.toString();
    if (empresa === null || empresa.length === 0) {
        erroEmpresa = true;
        $("#msempresa").html('<span class="label label-danger">A Empresa que originou a despesa precisa ser informada.</span>');
    } else {
        erroEmpresa = false;
        $("#msempresa").html('');
    }
}

function validarCategoria(event) {
    let categoria = Number.parseInt(slCategoria.value);
    if (categoria === null || isNaN(categoria) || categoria === 0) {
        erroCategoria = true;
        $("#mscategoria").html('<span class="label label-danger">A Categoria da despesa precisa ser selecionada.</span>');
    } else {
        erroCategoria = false;
        $("#mscategoria").html('');
    }
}

function validarDescricao(event) {
    let descricao = txDescricao.value.toString();
    if (descricao === null || descricao.length === 0) {
        erroDescricao = true;
        $("#msdescricao").html('<span class="label label-danger">A Descrição da despesa precisa ser informada.</span>');
    } else {
        erroDescricao = false;
        $("#msdescricao").html('');
    }
}

function validarTipo(event) {
    let tipo = Number.parseInt(slTipo.value);
    if (tipo === null || isNaN(tipo) || tipo === 0) {
        erroTipo = true;
        $("#mstipo").html('<span class="label label-danger">O tipo de conta deve ser selecionado.</span>');
    } else {
        erroTipo = false;
        $("#mstipo").html('');
    }
}

function validarFormaPagamento(event) {
    let fp = Number.parseInt(slFormaPagamento.value);
    let tipo = Number.parseInt(slTipo.value);

    if (tipo === 1 && (fp === null || isNaN(fp) || fp === 0)) {
        erroFormaPagamento = true;
        $("#msforma").html('<span class="label label-danger">A Forma de pagamento usada deve ser selecionada.</span>');
    } else {
        erroFormaPagamento = false;
        $("#msforma").html('');
    }
}

function validarIntervalo(event) {
    let dias = Number.parseInt(txIntervaloParcelas.value);
    let tipo = Number.parseInt(slTipo.value);

    if (tipo === 2 && (dias === null || isNaN(dias) || dias <= 0)) {
        erroIntervalo = true;
        $("#msintervalo").html('<span class="label label-danger">O intervalo em dias entre as parcelas deve ser informado.</span>');
    } else {
        erroIntervalo = false;
        $("#msintervalo").html('');
    }
}

function validarFrequencia(event) {
    let frequencia = Number.parseInt(slFrequencia.value);
    let tipo = Number.parseInt(slTipo.value);

    if (tipo === 3 && (frequencia === null || isNaN(frequencia) || frequencia === 0)) {
        erroFrequencia = true;
        $("#msfrequencia").html('<span class="label label-danger">A frequência das parces deve ser selecionada.</span>');
    } else {
        erroFrequencia = false;
        $("#msfrequencia").html('');
    }
}

function validarData(event) {
    let data = dtDespesa.value.toString();
    if (data === null || data.length === 0) {
        erroData = true;
        $("#msdata").html('<span class="label label-danger">A Data da geração da despesa deve ser informada.</span>');
    } else {
        let data1 = new Date(data + '12:00:00');
        if (data1 > Date.now()) {
            erroData = true;
            $("#msdata").html('<span class="label label-danger">A Data da despesa precisa ser igual ou menor que a data atual.</span>');
        } else {
            erroData = false;
            $("#msdata").html('');
        }
    }
}

function validarValorPago(event) {
    let valor = Number.parseFloat(txValorPago.value.replace(",", "."));
    let tipo = Number.parseInt(slTipo.value);

    if (tipo === 1 && (valor === null || isNaN(valor) || valor <= 0)) {
        erroValorPago = true;
        $("#msvalorpago").html('<span class="label label-danger">O valor pago pela despesa deve ser informado.</span>');
    } else {
        erroValorPago = false;
        $("#msvalorpago").html('');
    }
}

function validarParcelas(event) {
    let parcelas = Number.parseInt(txParcelas.value);
    if (parcelas === null || isNaN(parcelas) || parcelas <= 0) {
        erroParcelas = true;
        $("#msparcelas").html('<span class="label label-danger">O número de parcelas deve ser informado.</span>')
    } else {
        erroParcelas = false;
        $("#msparcelas").html('');
    }
}

function validarValor(event) {
    let valor = Number.parseFloat(txValor.value.toString().replace(",", "."));
    if (valor === null || isNaN(valor) || valor <= 0) {
        erroValor = true;
        $("#msvalor").html('<span class="label label-danger">O Valor da despesa precisa ser preenchido.</span>');
    } else {
        erroValor = false;
        $("#msvalor").html('');
    }
}

function validarVencimento(event) {
    let vencimento = dtVencimento.value.toString();
    if (vencimento === null || vencimento.length === 0) {
        erroVencimento = true;
        $("#msvencimento").html('<span class="label label-danger">O Vencimento da despesa precisa ser informado.</span>');
    } else {
        let venc = new Date(vencimento + '12:00:00');
        if (venc < Date.now()) {
            erroVencimento = true;
            $("#msvencimento").html('<span class="label label-danger">A Data de vencimento precisa ser igual ou maior que a data atual.</span>');
        } else {
            erroVencimento = false;
            $("#msvencimento").html('');
        }
    }
}

function obterConta() {
    conta = get("/representacoes/controlar/lancar/despesas/novo/obter-conta.php");
    if (conta > 0) {
        let tmp = conta.toString();
        switch (tmp.length) {
            case 1:
                tmp = "000" + tmp;
                break;
            case 2:
                tmp = "00" + tmp;
                break;
            case 3:
                tmp = "0" + tmp;
                break;
        }

        txConta.value = tmp;
    }
}

function mudarTipo(event) {
    let tipo = Number.parseInt(slTipo.value);
    if (tipo !== null && !isNaN(tipo) && tipo >= 0) {
        switch (tipo) {
            case 1:
                slFormaPagamento.value = 0;
                if (forma.classList.contains("hidden"))
                    forma.classList.remove("hidden");

                txIntervaloParcelas.value = 1;
                if (!intervalo.classList.contains("hidden"))
                    intervalo.classList.add("hidden");

                slFrequencia.value = 1;
                if (!frequencia.classList.contains("hidden"))
                    frequencia.classList.add("hidden");

                txValorPago.value = "0,00";
                if (pago.classList.contains("hidden"))
                    pago.classList.remove("hidden");

                txParcelas.value = 1;
                if (!parcelas.classList.contains("hidden"))
                    parcelas.classList.add("hidden");
                break;
            case 2:
                slFormaPagamento.value = 0;
                if (!forma.classList.contains("hidden"))
                    forma.classList.add("hidden");

                txIntervaloParcelas.value = 1;
                if (intervalo.classList.contains("hidden"))
                    intervalo.classList.remove("hidden");

                slFrequencia.value = 1;
                if (!frequencia.classList.contains("hidden"))
                    frequencia.classList.add("hidden");

                txValorPago.value = "0,00";
                if (!pago.classList.contains("hidden"))
                    pago.classList.add("hidden");

                txParcelas.value = 1;
                if (parcelas.classList.contains("hidden"))
                    parcelas.classList.remove("hidden");
                break;
            case 3:
                slFormaPagamento.value = 0;
                if (!forma.classList.contains("hidden"))
                    forma.classList.add("hidden");

                txIntervaloParcelas.value = 1;
                if (!intervalo.classList.contains("hidden"))
                    intervalo.classList.add("hidden");

                slFrequencia.value = 0;
                if (frequencia.classList.contains("hidden"))
                    frequencia.classList.remove("hidden");

                txValorPago.value = "0,00";
                if (!pago.classList.contains("hidden"))
                    pago.classList.add("hidden");

                txParcelas.value = 1;
                if (parcelas.classList.contains("hidden"))
                    parcelas.classList.remove("hidden");
                break;
            case 0:
                slFormaPagamento.value = 0;
                if (!forma.classList.contains("hidden"))
                    forma.classList.add("hidden");

                txIntervaloParcelas.value = 1;
                if (intervalo.classList.contains("hidden"))
                    intervalo.classList.remove("hidden");

                slFrequencia.value = 1;
                if (!frequencia.classList.contains("hidden"))
                    frequencia.classList.add("hidden");

                txValorPago.value = "0,00";
                if (!pago.classList.contains("hidden"))
                    pago.classList.add("hidden");

                txParcelas.value = 1;
                if (parcelas.classList.contains("hidden"))
                    parcelas.classList.remove("hidden");
                break;
        }
    }
}

function cancelarLancamento() {
    limparCampos();
    location.href = "../../despesas";
}

function limparCampos() {
    txEmpresa.value = "";
    slCategoria.value = 0;
    slPedido.value = 0;
    txConta.value = "0000";
    txDescricao.value = "";
    slTipo.value = 0;

    slFormaPagamento.value = 0;
    txIntervaloParcelas.value = 1;
    slFrequencia.value = 0;
    dtDespesa.value = "";
    txValorPago.value = "0,00";
    txParcelas.value = 1;
    txValor.value = "0,00";
    dtVencimento.value = "";
}

function validarCampos() {
    let tipo = Number.parseInt(slTipo.value);

    validarEmpresa();
    validarCategoria();
    validarDescricao();
    validarTipo();
    switch (tipo) {
        case 1:
            validarFormaPagamento();
            validarValorPago();
            break;
        case 2:
            validarIntervalo();
            validarParcelas();
            break;
        case 3:
            validarFrequencia();
            validarParcelas();
            break;
    }
    validarData();
    validarValor();
    validarVencimento();
    
    switch (tipo) {
        case 1:
            return (
                !erroEmpresa &&
                !erroCategoria &&
                !erroDescricao &&
                !erroTipo &&
                !erroFormaPagamento &&
                !erroData &&
                !erroValorPago &&
                !erroValor &&
                !erroVencimento
            );
        case 2:
            return (
                !erroEmpresa &&
                !erroCategoria &&
                !erroDescricao &&
                !erroTipo &&
                !erroIntervalo &&
                !erroData &&
                !erroParcelas &&
                !erroValor &&
                !erroVencimento
            );
        case 3:
            return (
                !erroEmpresa &&
                !erroCategoria &&
                !erroDescricao &&
                !erroTipo &&
                !erroFrequencia &&
                !erroData &&
                !erroParcelas &&
                !erroValor &&
                !erroVencimento
            );
    }
}

function lancarDespesa() {
    let empresa = "";
    let categoria = "";
    let pedido = 0;
    let descricao = "";
    let tipo = 0;
    let forma = 0;
    let intervalo = 0;
    let frequencia = 0;
    let data = "";
    let valorPago = 0.0;
    let parcelas = 0;
    let valor = 0.0;
    let vencimento = "";

    if (validarCampos()) {
        empresa = txEmpresa.value;
        categoria = slCategoria.value;
        pedido = slPedido.value;
        descricao = txDescricao.value;
        tipo = slTipo.value;
        forma = slFormaPagamento.value;
        intervalo = Number.parseInt(txIntervaloParcelas.value);
        frequencia = slFrequencia.value;
        data = dtDespesa.value;
        valorPago = Number.parseFloat(txValorPago.value.replace(",", "."));
        parcelas = txParcelas.value;
        valor = Number.parseFloat(txValor.value.replace(",", "."));
        vencimento = dtVencimento.value;

        let uri = "";

        uri += "empresa=" + empresa;
        uri += "&categoria=" + categoria;
        uri += "&pedido=" + pedido;
        uri += "&conta=" + conta;
        uri += "&descricao=" + descricao;
        uri += "&tipo=" + tipo;
        uri += "&forma=" + forma;
        uri += "&intervalo=" + intervalo;
        uri += "&frequencia=" + frequencia;
        uri += "&data=" + data;
        uri += "&valorPago=" + valorPago;
        uri += "&parcelas=" + parcelas;
        uri += "&valor=" + valor;
        uri += "&vencimento=" + vencimento;

        let request = new XMLHttpRequest();
        request.open("POST", "/representacoes/controlar/lancar/despesas/novo/lancar.php", false);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(encodeURI(uri));

        if (request.DONE === 4 && request.status === 200) {
            let res = JSON.parse(request.responseText);
            if (res !== null && res.length === 0) {
                mostraDialogo(
                    "<strong>Lançamento gravado com sucesso!</strong>" +
                    "<br />Os dados da nova despesa foram salvos com sucesso no banco de dados.",
                    "success",
                    2000
                );
                limparCampos();
                obterConta();
            } else {
                mostraDialogo(
                    "<strong>Problemas ao salvar a nova despesa...</strong>" +
                    "<br/>"+res,
                    "danger",
                    2000
                );
            }
        } else {
            mostraDialogo(
                "Erro na requisição da URL /representacoes/controlar/lancar/despesas/novo/lancar.php. <br />" +
                "Status: "+request.status+" "+request.statusText,
                "danger",
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

function get(url_i) {
    let res = {};
    let request = new XMLHttpRequest();
    request.open("GET", url_i, false);
    request.send();

    if (request.DONE === 4 && request.status === 200) {
        res = JSON.parse(request.responseText);
    } else {
        mostraDialogo(
            "Erro na requisição da URL " + url_i + ". <br />" +
            "Status: "+request.status+" "+request.statusText,
            "danger",
            3000
        );
    }

    return res;
}

$(document).ready(() => {
    $(txValor).mask("000000000,00", { reverse: true });
    $(txValorPago).mask("000000000,00", { reverse: true });

    let categorias = get("/representacoes/controlar/lancar/despesas/novo/obter-categorias.php");
    if (categorias !== null || categorias !== "" || categorias.length !== 0) {
        for (let i = 0; i < categorias.length; i++) {
            let option = document.createElement("option");
            option.value = categorias[i].id;
            option.text = categorias[i].descricao;
            slCategoria.appendChild(option);
        }
    }

    let pedidos = get("/representacoes/controlar/lancar/despesas/novo/obter-pedidos.php");
    if (pedidos !== null || pedidos !== "" || pedidos.length !== 0) {
        for (let i = 0; i < pedidos.length; i++) {
            let option = document.createElement("option");
            option.value = pedidos[i].id;
            option.text = pedidos[i].descricao;
            slPedido.appendChild(option);
        }
    }

    let formas = get("/representacoes/controlar/lancar/despesas/novo/obter-formas.php");
    if (formas !== null && formas.length !== 0) {
        for (let i = 0; i < formas.length; i++) {
            let option = document.createElement("option");
            option.value = formas[i].id;
            option.text = formas[i].descricao;
            slFormaPagamento.appendChild(option);
        }
    }

    obterConta();

    mudarTipo();
});