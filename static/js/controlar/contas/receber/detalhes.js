const txConta = document.getElementById("txConta");
const dtDespesa = document.getElementById("dtDespesa");
const txDescricao = document.getElementById("txDescricao");
const txPagador = document.getElementById("txPagador");
const txFonte = document.getElementById("txFonte");
const dtVencimento = document.getElementById("dtVencimento");
const txValor = document.getElementById("txValor");
const txSituacao = document.getElementById("txSituacao");
const slFormaRecebimento = document.getElementById("slFormaRecebimento");
const txValorRecebido = document.getElementById("txValorRecebido");
const dtRecebimento = document.getElementById("dtRecebimento");

let erroForma = true;
let erroValor = true;
let erroRecebimento = true;

let despesa = 0;

let dtpg = null;

function validarFormaRecebimento(event) {
    let forma = Number.parseInt(slFormaRecebimento.value);
    if (forma === null || isNaN(forma) || forma === 0) {
        erroForma = true;
        $("#msforma").html('<span class="label label-danger">A forma de recebimento da despesa deve ser selecionada.</span>');
    } else {
        erroForma = false;
        $("#msforma").html('');
    }
}

function validarValor(event) {
    let valor = Number.parseFloat(txValorRecebido.value.toString().replace(",", "."));
    if (valor === null || isNaN(valor) || valor <= 0) {
        erroValor = true;
        $("#msvalor").html('<span class="label label-danger">O Valor recebido precisa ser preenchido.</span>');
    } else {
        erroValor = false;
        $("#msvalor").html('');
    }
}

function validarRecebimento(event) {
    let recebimento = dtRecebimento.value.toString();
    if (recebimento === null || recebimento.length === 0) {
        erroRecebimento = true;
        $("#msrecebimento").html('<span class="label label-danger">A data de recebimento da despesa precisa ser informado.</span>');
    } else {
        let pag = new Date(recebimento);
        if (dtpg === null) {
            if (pag > Date.now()) {
                erroRecebimento = true;
                $("#msrecebimento").html('<span class="label label-danger">A Data de recebimento precisa ser igual ou menor que a data atual.</span>');
            } else {
                erroRecebimento = false;
                $("#msrecebimento").html('');
            }
        } else {
            let pag1 = new Date(dtpg);
            if (pag < pag1 || pag > Date.now()) {
                erroRecebimento = true;
                $("#msrecebimento").html('<span class="label label-danger">A Data precisa ser igual ou menor que a data atual e maior que a data anterior.</span>');
            } else {
                erroRecebimento = false;
                $("#msrecebimento").html('');
            }
        }
    }
}

function cancelarRecebimento() {
    limparCampos();
    location.href = "../../receber";
}

function limparCampos() {
    txConta.value = "0000";
    dtDespesa.value = "";
    txDescricao.value = "";
    txPagador.value = "";
    txFonte.value = "";
    dtVencimento.value = "";
    txValor.value = "0,00";
    txSituacao.value = "";

    slFormaRecebimento.value = 0;
    txValorRecebido.value = "0,00";
    dtRecebimento.value = "";
}

function validarCampos() {
    validarFormaRecebimento();
    validarValor();
    validarRecebimento();

    return (
        !erroForma && !erroValor && !erroRecebimento
    );
}

function receberDespesa() {
    let forma = "";
    let valor = 0.0;
    let recebimento = "";

    if (validarCampos()) {
        let penant = 0;

        forma = slFormaRecebimento.value;
        valor = Number.parseFloat(txValorRecebido.value.replace(",", "."));
        recebimento = dtRecebimento.value;

        let uri = "";

        uri += "despesa=" + despesa;
        uri += "&forma=" + forma;
        uri += "&valor=" + valor;
        uri += "&recebimento=" + recebimento;

        let request = new XMLHttpRequest();
        request.open("POST", "/representacoes/controlar/contas/receber/detalhes/receber.php", false);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(encodeURI(uri));

        if (request.DONE === 4 && request.status === 200) {
            let res = request.responseText;
            if (res !== null && res.length === 0) {
                mostraDialogo(
                    "<strong>Despesa recebimento com sucesso!</strong>" +
                    "<br />A despesa foi recebimento com sucesso no banco de dados.",
                    "success",
                    2000
                );
            } else {
                mostraDialogo(
                    "<strong>Problemas ao receber a despesa...</strong>" +
                    "<br/>"+res,
                    "danger",
                    2000
                );
            }
        } else {
            mostraDialogo(
                "Erro na requisição da URL /representacoes/controlar/contas/recebimento/novo/receber.php. <br />" +
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
    let formas = get("/representacoes/controlar/contas/receber/detalhes/obter-formas.php");
    if (formas !== null && formas.length !== 0) {
        for (let i = 0; i < formas.length; i++) {
            let option = document.createElement("option");
            option.value = formas[i].id;
            option.text = formas[i].descricao;
            slFormaRecebimento.appendChild(option);
        }
    }

    let detalhes = get("/representacoes/controlar/contas/receber/detalhes/obter.php");
    if (detalhes !== null && typeof detalhes !== "string") {
        despesa = detalhes.id;
        txConta.value = detalhes.conta;
        dtDespesa.value = detalhes.data;
        txDescricao.value = detalhes.descricao;
        txPagador.value = detalhes.pagador;
        let fonte = "";
        if (detalhes.pedidoFrete !== null) {
            fonte = detalhes.pedidoFrete.descricao;
        } else if (detalhes.pedidoVenda !== null) {
            fonte = detalhes.pedidoVenda.descricao;
        } else {
            fonte = "INTERNO";
        }
        txFonte.value = fonte;
        dtVencimento.value = detalhes.vencimento;
        txValor.value = formatarValor(detalhes.valor);

        let situacao = "";
        switch (detalhes.situacao) {
          case 1:
            situacao = "PENDENTE";
            break;

          case 2:
            situacao = "PAGO PARC.";
            break;

          case 3:
            situacao = "PAGO";
            break;
        }
        txSituacao.value = situacao;

        if (detalhes.situacao === 2) {
            slFormaRecebimento.value = detalhes.formaRecebimento.id;
            txValorRecebido.value = formatarValor(detalhes.valorRecebido);
            dtpg = detalhes.dataRecebimento;
            dtRecebimento.value = detalhes.dataRecebimento;
        }
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
    $(txValorRecebido).mask("000000000,00", { reverse: true });
});
