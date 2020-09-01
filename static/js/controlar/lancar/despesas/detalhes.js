const txEmpresa = document.getElementById("txEmpresa");
const slCategoria = document.getElementById("slCategoria");
const slPedido = document.getElementById("slPedido");
const txDescricao = document.getElementById("txDescricao");
const dtDespesa = document.getElementById("dtDespesa");
const txValor = document.getElementById("txValor");
const dtVencimento = document.getElementById("dtVencimento");

let erroEmpresa = true;
let erroCategoria = true;
let erroDescricao = true;
let erroData = true;
let erroValor = true;
let erroVencimento = true;

let despesa = 0;

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

function cancelarLancamento() {
    limparCampos();
    location.href = "../../despesas";
}

function limparCampos() {
    txEmpresa.value = "";
    slCategoria.value = 0;
    slPedido.value = 0;
    txDescricao.value = "";
    dtDespesa.value = "";
    txValor.value = "0,00";
    dtVencimento.value = "";
}

function validarCampos() {
    validarEmpresa();
    validarCategoria();
    validarDescricao();
    validarData();
    validarValor();
    validarVencimento();

    return (
        !erroEmpresa && !erroCategoria && !erroDescricao && !erroData && !erroValor && !erroVencimento
    );
}

function alterarDespesa() {
    let empresa = "";
    let categoria = "";
    let pedido = 0;
    let descricao = "";
    let data = "";
    let valor = 0.0;
    let vencimento = "";

    if (validarCampos()) {
        empresa = txEmpresa.value;
        categoria = slCategoria.value;
        pedido = slPedido.value;
        descricao = txDescricao.value;
        data = dtDespesa.value;
        valor = Number.parseFloat(txValor.value.replace(",", "."));
        vencimento = dtVencimento.value;

        let uri = "";

        uri += "despesa=" + despesa;
        uri += "&empresa=" + empresa;
        uri += "&categoria=" + categoria;
        uri += "&pedido=" + pedido;
        uri += "&descricao=" + descricao;
        uri += "&data=" + data;
        uri += "&valor=" + valor;
        uri += "&vencimento=" + vencimento;

        let request = new XMLHttpRequest();
        request.open("POST", "/representacoes/controlar/lancar/despesas/detalhes/alterar.php", false);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(encodeURI(uri));

        if (request.DONE === 4 && request.status === 200) {
            let res = request.responseText;
            if (res !== null && res.length === 0) {
                mostraDialogo(
                    "<strong>Lançamento alterado com sucesso!</strong>" +
                    "<br />Os dados da despesa foram alterados com sucesso no banco de dados.",
                    "success",
                    2000
                );
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
            "warning",
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
    //$(txValor).mask("000000000,00", { reverse: true });

    let categorias = get("/representacoes/controlar/lancar/despesas/detalhes/obter-categorias.php");
    if (categorias !== null && categorias.length !== 0) {
        for (let i = 0; i < categorias.length; i++) {
            let option = document.createElement("option");
            option.value = categorias[i].id;
            option.text = categorias[i].descricao;
            slCategoria.appendChild(option);
        }
    }

    let pedidos = get("/representacoes/controlar/lancar/despesas/detalhes/obter-pedidos.php");
    if (pedidos !== null && pedidos.length !== 0) {
        for (let i = 0; i < pedidos.length; i++) {
            let option = document.createElement("option");
            option.value = pedidos[i].id;
            option.text = pedidos[i].descricao;
            slPedido.appendChild(option);
        }
    }

    let detalhes = get("/representacoes/controlar/lancar/despesas/detalhes/obter.php");
    if (detalhes !== null && typeof detalhes !== "string") {
        despesa = detalhes.id;
        txEmpresa.value = detalhes.empresa;
        slCategoria.value = detalhes.categoria.id;
        slPedido.value = (detalhes.pedidoFrete !== null) ? detalhes.pedidoFrete.id : 0;
        txDescricao.value = detalhes.descricao;
        dtDespesa.value = detalhes.data;
        let valor = detalhes.valor.toString();
        valor = valor.replace(".", "#");
        if (valor.search("#") === -1) {
            valor += "00";
        } else {
            if (valor.split("#")[1].length === 1) {
                valor += "0";
            }
        }
        valor = valor.replace("#", ",");
        txValor.value = valor;
        dtVencimento.value = detalhes.vencimento;
    } else {
        if (typeof detalhes === "string") {
            mostraDialogo(
                detalhes,
                "danger",
                3000
            );
        }
    }

    $(txValor).mask("000000000,00", { reverse: true });
});