const txEmpresa = document.getElementById("txEmpresa");
const slCategoria = document.getElementById("slCategoria");
const slPedido = document.getElementById("slPedido");
const txConta = document.getElementById("txConta");
const txDescricao = document.getElementById("txDescricao");
const slTipo = document.getElementById("slTipo");
const slFrequencia = document.getElementById("slFrequencia");
const dtDespesa = document.getElementById("dtDespesa");
const txParcelas = document.getElementById("txParcelas");
const txValor = document.getElementById("txValor");
const dtVencimento = document.getElementById("dtVencimento");

let conta = 0;

let erroEmpresa = true;
let erroCategoria = true;
let erroDescricao = true;
let erroTipo = true;
let erroFrequencia = true;
let erroData = true;
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

function validarFrequencia(event) {
    let frequencia = Number.parseInt(slFrequencia.value);
    if (frequencia === null || isNaN(frequencia) || frequencia === 0) {
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
        let data1 = new Date(data);
        if (data1 > Date.now()) {
            erroData = true;
            $("#msdata").html('<span class="label label-danger">A Data da despesa precisa ser igual ou menor que a data atual.</span>');
        } else {
            erroData = false;
            $("#msdata").html('');
        }
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
        let venc = new Date(vencimento);
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
                slFrequencia.value = 1;
                slFrequencia.disabled = true;
                txParcelas.value = 1;
                txParcelas.disabled = true;
                break;
            case 2:
                slFrequencia.disabled = false;
                slFrequencia.value = 0;
                txParcelas.disabled = false;
                txParcelas.value = 1;
                break;
            case 3:
                slFrequencia.disabled = false;
                slFrequencia.value = 0;
                txParcelas.disabled = true;
                txParcelas.value = 60;
                break;
            case 0:
                slFrequencia.disabled = false;
                slFrequencia.value = 0;
                txParcelas.disabled = false;
                txParcelas.value = 1;
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

    slFrequencia.value = 0;
    dtDespesa.value = "";
    txParcelas.value = 1;
    txValor.value = "0,00";
    dtVencimento.value = "";
}

function validarCampos() {
    validarEmpresa();
    validarCategoria();
    validarDescricao();
    validarTipo();
    validarFrequencia();
    validarData();
    validarParcelas();
    validarValor();
    validarVencimento();

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

function lancarDespesa() {
    let empresa = "";
    let categoria = "";
    let pedido = 0;
    let descricao = "";
    let tipo = 0;
    let frequencia = 0;
    let data = "";
    let parcelas = 0;
    let valor = 0.0;
    let vencimento = "";

    if (validarCampos()) {
        empresa = txEmpresa.value;
        categoria = slCategoria.value;
        pedido = slPedido.value;
        descricao = txDescricao.value;
        tipo = slTipo.value;
        frequencia = slFrequencia.value;
        data = dtDespesa.value;
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
        uri += "&frequencia=" + frequencia;
        uri += "&data=" + data;
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

    obterConta();
});