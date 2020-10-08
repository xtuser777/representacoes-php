const txConta = document.getElementById("txConta");
const dtDespesa = document.getElementById("dtDespesa");
const txParcela = document.getElementById("txParcela");
const txDescricao = document.getElementById("txDescricao");
const txEmpresa = document.getElementById("txEmpresa");
const txTipo = document.getElementById("txTipo");
const txCategoria = document.getElementById("txCategoria");
const txFonte = document.getElementById("txFonte");
const dtVencimento = document.getElementById("dtVencimento");
const txValor = document.getElementById("txValor");
const txSituacao = document.getElementById("txSituacao");
const slFormaPagamento = document.getElementById("slFormaPagamento");
const txValorPago = document.getElementById("txValorPago");
const dtPagamento = document.getElementById("dtPagamento");

let erroForma = true;
let erroValor = true;
let erroPagamento = true;

let despesa = 0;

let dtpg = null;

function validarFormaPagamento(event) {
    let forma = Number.parseInt(slFormaPagamento.value);
    if (forma === null || isNaN(forma) || forma === 0) {
        erroForma = true;
        $("#msforma").html('<span class="label label-danger">A forma de pagamento da despesa deve ser selecionada.</span>');
    } else {
        erroForma = false;
        $("#msforma").html('');
    }
}

function validarValor(event) {
    let valor = Number.parseFloat(txValorPago.value.toString().replace(",", "."));
    if (valor === null || isNaN(valor) || valor <= 0) {
        erroValor = true;
        $("#msvalor").html('<span class="label label-danger">O Valor pago precisa ser preenchido.</span>');
    } else {
        erroValor = false;
        $("#msvalor").html('');
    }
}

function validarPagamento(event) {
    let pagamento = dtPagamento.value.toString();
    if (pagamento === null || pagamento.length === 0) {
        erroPagamento = true;
        $("#mspagamento").html('<span class="label label-danger">A data de pagamento da despesa precisa ser informado.</span>');
    } else {
        let pag = new Date(pagamento);
        if (dtpg === null) {
            if (pag > Date.now()) {
                erroPagamento = true;
                $("#mspagamento").html('<span class="label label-danger">A Data de pagamento precisa ser igual ou menor que a data atual.</span>');
            } else {
                erroPagamento = false;
                $("#mspagamento").html('');
            }
        } else {
            let pag1 = new Date(dtpg);
            if (pag < pag1 || pag > Date.now()) {
                erroPagamento = true;
                $("#mspagamento").html('<span class="label label-danger">A Data precisa ser igual ou menor que a data atual e maior que a data anterior.</span>');
            } else {
                erroPagamento = false;
                $("#mspagamento").html('');
            }
        }
    }
}

function cancelarQuitacao() {
    limparCampos();
    location.href = "../../pagar";
}

function limparCampos() {
    txConta.value = "0000";
    dtDespesa.value = "";
    txParcela.value = "0";
    txDescricao.value = "";
    txEmpresa.value = "";
    txTipo.value = "";
    txCategoria.value = "";
    txFonte.value = "";
    dtVencimento.value = "";
    txValor.value = "0,00";
    txSituacao.value = "";

    slFormaPagamento.value = 0;
    txValorPago.value = "0,00";
    dtPagamento.value = "";
}

function validarCampos() {
    validarFormaPagamento();
    validarValor();
    validarPagamento();

    return (
        !erroForma && !erroValor && !erroPagamento
    );
}

function quitarDespesa() {
    let forma = "";
    let valor = 0.0;
    let pagamento = "";

    if (validarCampos()) {
        let penant = 0;
        
        let request1 = new XMLHttpRequest();
        request1.open("POST", "/representacoes/controlar/contas/pagar/detalhes/obter-por-conta.php", false);
        request1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request1.send(encodeURI("conta=" + txConta.value));

        if (request1.DONE === 4 && request1.status === 200) {
            let res = JSON.parse(request1.responseText);
            if (res !== null && res.length > 0) {
                for(let i = 0; i < Number.parseInt(txParcela.value) && penant === 0; i++) {
                    if (res[i].situacao === 1)
                        penant = res[i].parcela;
                }
            }
        }
        
        if (penant > 0) {
            bootbox.confirm({
                message: "Esta conta possui a parcela "+penant+" ainda pendente, deseja quitar assim mesmo?",
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
                        forma = slFormaPagamento.value;
                        valor = Number.parseFloat(txValorPago.value.replace(",", "."));
                        pagamento = dtPagamento.value;

                        let uri = "";

                        uri += "despesa=" + despesa;
                        uri += "&forma=" + forma;
                        uri += "&valor=" + valor;
                        uri += "&pagamento=" + pagamento;

                        let request = new XMLHttpRequest();
                        request.open("POST", "/representacoes/controlar/contas/pagar/detalhes/quitar.php", false);
                        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        request.send(encodeURI(uri));

                        if (request.DONE === 4 && request.status === 200) {
                            let res = request.responseText;
                            if (res !== null && res.length === 0) {
                                mostraDialogo(
                                    "<strong>Despesa quitada com sucesso!</strong>" +
                                    "<br />A despesa foi quitada com sucesso no banco de dados.",
                                    "success",
                                    2000
                                );
                            } else {
                                mostraDialogo(
                                    "<strong>Problemas ao quitar a despesa...</strong>" +
                                    "<br/>"+res,
                                    "danger",
                                    2000
                                );
                            }
                        } else {
                            mostraDialogo(
                                "Erro na requisição da URL /representacoes/controlar/contas/pagar/novo/quitar.php. <br />" +
                                "Status: "+request.status+" "+request.statusText,
                                "danger",
                                3000
                            );
                        }
                    }
                }
            });
        } else {
            forma = slFormaPagamento.value;
            valor = Number.parseFloat(txValorPago.value.replace(",", "."));
            pagamento = dtPagamento.value;

            let uri = "";

            uri += "despesa=" + despesa;
            uri += "&forma=" + forma;
            uri += "&valor=" + valor;
            uri += "&pagamento=" + pagamento;

            let request = new XMLHttpRequest();
            request.open("POST", "/representacoes/controlar/contas/pagar/detalhes/quitar.php", false);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.send(encodeURI(uri));

            if (request.DONE === 4 && request.status === 200) {
                let res = request.responseText;
                if (res !== null && res.length === 0) {
                    mostraDialogo(
                        "<strong>Despesa quitada com sucesso!</strong>" +
                        "<br />A despesa foi quitada com sucesso no banco de dados.",
                        "success",
                        2000
                    );
                } else {
                    mostraDialogo(
                        "<strong>Problemas ao quitar a despesa...</strong>" +
                        "<br/>"+res,
                        "danger",
                        2000
                    );
                }
            } else {
                mostraDialogo(
                    "Erro na requisição da URL /representacoes/controlar/contas/pagar/novo/quitar.php. <br />" +
                    "Status: "+request.status+" "+request.statusText,
                    "danger",
                    3000
                );
            }
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
    let formas = get("/representacoes/controlar/contas/pagar/detalhes/obter-formas.php");
    if (formas !== null && formas.length !== 0) {
        for (let i = 0; i < formas.length; i++) {
            let option = document.createElement("option");
            option.value = formas[i].id;
            option.text = formas[i].descricao;
            slFormaPagamento.appendChild(option);
        }
    }

    let detalhes = get("/representacoes/controlar/contas/pagar/detalhes/obter.php");
    if (detalhes !== null && typeof detalhes !== "string") {
        despesa = detalhes.id;
        txConta.value = detalhes.conta;
        dtDespesa.value = detalhes.data;
        txParcela.value = detalhes.parcela;
        txDescricao.value = detalhes.descricao;
        txEmpresa.value = detalhes.empresa;
        txCategoria.value = detalhes.categoria.descricao;
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

        let tipo = "";
        switch (detalhes.tipo) {
            case 1:
                tipo = "A VISTA";
                break;

            case 2:
                tipo = "A PRAZO";
                break;

            case 3:
                tipo = "FIXA";
                break;
        }
        txTipo.value = tipo;

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
            slFormaPagamento.value = detalhes.formaPagamento.id;
            let valorpg = detalhes.valorPago.toString();
            valorpg = valorpg.replace(".", "#");
            if (valorpg.search("#") === -1) {
                valorpg += "00";
            } else {
                if (valorpg.split("#")[1].length === 1) {
                    valorpg += "0";
                }
            }
            valorpg = valorpg.replace("#", ",");
            txValorPago.value = valorpg;
            dtpg = detalhes.dataPagamento;
            dtPagamento.value = detalhes.dataPagamento;
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
    $(txValorPago).mask("000000000,00", { reverse: true });
});
