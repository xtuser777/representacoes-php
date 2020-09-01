const txFiltro = document.getElementById("txFiltro");
const txDataInicio = document.getElementById("txDataInicio");
const txDataFim = document.getElementById("txDataFim");
const selectOrd = document.getElementById("cbord");
const tbContas = document.getElementById("tbContas");
const tbodyContas = document.getElementById("tbodyContas");

function preencherTabela(dados) {
    let txt = "";
    $.each(dados, function () {
        let valorFormat = this.valor.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');

        let sit = "";
        switch (this.situacao) {
            case 1:
                sit = "PENDENTE";
                break;
            case 2:
                sit = "PAGO PARC.";
                break;
            case 3:
                sit = "PAGO";
                break;
        }

        let fonte = "";
        if (this.pedidoFrete !== null) {
            fonte = this.pedidoFrete.descricao;
        } else {
            if (this.pedidoVenda !== null) {
                fonte = this.pedidoVenda.descricao;
            } else {
                fonte = "INTERNO";
            }
        }
        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.descricao + '</td>\
                <td>'+ fonte +'</td>\
                <td>' + this.empresa + '</td>\
                <td>' + this.categoria.descricao + '</td>\
                <td>' + FormatarData(this.vencimento) + '</td>\
                <td>' + FormatarData(this.data) + '</td>\
                <td>'+ valorFormat +'</td>\
                <td>'+ sit +'</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="DETALHES" href="javascript:alterar(' + this.id + ')"></a></td>\
            </tr>';
    });
    $(tbodyContas).html(txt);
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

function obter() {
    let data = get("/representacoes/controlar/contas/pagar/obter.php");
    preencherTabela(data);
}

$(document).ready(function (event) {
    txDataInicio.value = new Date().toISOString().substr(0, 10);
    txDataFim.value = new Date().toISOString().substr(0, 10);

    obter();
});

function ordenar() {
    let ord = selectOrd.value;

    let request = new XMLHttpRequest();
    request.open("POST", "/representacoes/controlar/lancar/despesas/ordenar.php", false);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(encodeURI('col='+ord));

    if (request.DONE === 4 && request.status === 200) {
        let res = JSON.parse(request.responseText);
        if (res !== null && typeof res !== "string" && res.length !== 0) {
            preencherTabela(res);
        } else {
            mostraDialogo(
                res,
                "danger",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Erro na requisição da URL /representacoes/controlar/lancar/despesas/ordenar.php. <br />" +
            "Status: "+request.status+" "+request.statusText,
            "danger",
            3000
        );
    }
}

function filtrar() {
    let filtro = txFiltro.value;
    let dataInicio = txDataInicio.value;
    let dataFim = txDataFim.value;

    let data1 = new Date(dataInicio);
    let data2 = new Date(dataFim);

    if (filtro === "" && dataInicio === "" && dataFim === "") {
        obter();
    } else {
        if (filtro !== "" && dataInicio !== "" && dataFim !== "") {
            if (data1 > Date.now() || data2 > Date.now()) {
                mostraDialogo(
                    "As Datas de Início e Fim devem ser menor ou igual que a data atual.",
                    "warning",
                    3000
                );
            } else {
                if (dataInicio === dataFim) {
                    let request = new XMLHttpRequest();
                    request.open("POST", "/representacoes/controlar/lancar/despesas/obter-por-filtro-data.php", false);
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.send(encodeURI("filtro="+filtro+"&data="+dataInicio));

                    if (request.DONE === 4 && request.status === 200) {
                        let res = JSON.parse(request.responseText);
                        if (res !== null && typeof res !== "string") {
                            preencherTabela(res);
                        } else {
                            mostraDialogo(
                                res,
                                "danger",
                                3000
                            );
                        }
                    } else {
                        mostraDialogo(
                            "Erro na requisição da URL /representacoes/controlar/lancar/despesas/obter-por-filtro-data.php. <br />" +
                            "Status: "+request.status+" "+request.statusText,
                            "danger",
                            3000
                        );
                    }
                } else {
                    if (data1 > data2) {
                        mostraDialogo(
                            "A Data de Início deve ser menor que a data Fim do filtro.",
                            "warning",
                            3000
                        );
                    } else {
                        let request = new XMLHttpRequest();
                        request.open("POST", "/representacoes/controlar/lancar/despesas/obter-por-filtro-periodo.php", false);
                        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        request.send(encodeURI("filtro="+filtro+"&dataInicio="+dataInicio+"&dataFim="+dataFim));

                        if (request.DONE === 4 && request.status === 200) {
                            let res = JSON.parse(request.responseText);
                            if (res !== null && typeof res !== "string") {
                                preencherTabela(res);
                            } else {
                                mostraDialogo(
                                    res,
                                    "danger",
                                    3000
                                );
                            }
                        } else {
                            mostraDialogo(
                                "Erro na requisição da URL /representacoes/controlar/lancar/despesas/obter-por-filtro-periodo.php. <br />" +
                                "Status: "+request.status+" "+request.statusText,
                                "danger",
                                3000
                            );
                        }
                    }
                }
            }
        } else {
            if (filtro === "" && dataInicio !== "" && dataFim !== "") {
                if (data1 > Date.now() || data2 > Date.now()) {
                    mostraDialogo(
                        "As Datas de Início e Fim devem ser menor ou igual que a data atual.",
                        "warning",
                        3000
                    );
                } else {
                    if (dataInicio === dataFim) {
                        let request = new XMLHttpRequest();
                        request.open("POST", "/representacoes/controlar/lancar/despesas/obter-por-data.php", false);
                        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        request.send(encodeURI("data="+dataInicio));

                        if (request.DONE === 4 && request.status === 200) {
                            let res = JSON.parse(request.responseText);
                            if (res !== null && typeof res !== "string") {
                                preencherTabela(res);
                            } else {
                                mostraDialogo(
                                    res,
                                    "danger",
                                    3000
                                );
                            }
                        } else {
                            mostraDialogo(
                                "Erro na requisição da URL /representacoes/controlar/lancar/despesas/obter-por-data.php. <br />" +
                                "Status: "+request.status+" "+request.statusText,
                                "danger",
                                3000
                            );
                        }
                    } else {
                        if (data1 > data2) {
                            mostraDialogo(
                                "A Data de Início deve ser menor que a data Fim do filtro.",
                                "warning",
                                3000
                            );
                        } else {
                            let request = new XMLHttpRequest();
                            request.open("POST", "/representacoes/controlar/lancar/despesas/obter-por-periodo.php", false);
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.send(encodeURI("dataInicio="+dataInicio+"&dataFim="+dataFim));

                            if (request.DONE === 4 && request.status === 200) {
                                let res = JSON.parse(request.responseText);
                                if (res !== null && typeof res !== "string") {
                                    preencherTabela(res);
                                } else {
                                    mostraDialogo(
                                        res,
                                        "danger",
                                        3000
                                    );
                                }
                            } else {
                                mostraDialogo(
                                    "Erro na requisição da URL /representacoes/controlar/lancar/despesas/obter-por-periodo.php. <br />" +
                                    "Status: "+request.status+" "+request.statusText,
                                    "danger",
                                    3000
                                );
                            }
                        }
                    }
                }
            } else {
                if (filtro !== "" && dataInicio === "" && dataFim === "") {
                    $.ajax({
                        type: 'POST',
                        url: '/representacoes/controlar/lancar/despesas/obter-por-filtro.php',
                        data: { filtro: filtro },
                        success: function (response) {
                            if (response != null && response !== ""){
                                preencherTabela(response);
                            }
                        },
                        error: function () {
                            alert("Ocorreu um erro ao comunicar-se com o servidor...");
                        }
                    });
                } else {
                    if (filtro === "") {
                        if (dataInicio === "") {
                            mostraDialogo(
                                "A Data de Início do filtro período deve ser preenchida.",
                                "warning",
                                3000
                            );
                        } else {
                            if (dataFim === "") {
                                mostraDialogo(
                                    "A Data Fim do filtro período deve ser preenchida.",
                                    "warning",
                                    3000
                                );
                            }
                        }
                    }
                }
            }
        }
    }
}

function excluir(id) {
    bootbox.confirm({
        message: "Confirma a exclusão desta despesa?",
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
                let request = new XMLHttpRequest();
                request.open("POST", "/representacoes/controlar/lancar/despesas/excluir.php", false);
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                request.send(encodeURI('id='+id));

                if (request.DONE === 4 && request.status === 200) {
                    let res = JSON.parse(request.responseText);
                    if (res !== null && res.length === 0) {
                        obter();
                    } else {
                        mostraDialogo(
                            res,
                            "danger",
                            3000
                        );
                    }
                } else {
                    mostraDialogo(
                        "Erro na requisição da URL /representacoes/controlar/lancar/despesas/enviar.php. <br />" +
                        "Status: "+request.status+" "+request.statusText,
                        "danger",
                        3000
                    );
                }
            }
        }
    });
}

function alterar(id) {
    let request = new XMLHttpRequest();
    request.open("POST", "/representacoes/controlar/lancar/despesas/enviar.php", false);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(encodeURI('id='+id));

    if (request.DONE === 4 && request.status === 200) {
        let res = JSON.parse(request.responseText);
        if (res !== null && res.length === 0) {
            window.location.href = "../../../controlar/lancar/despesas/detalhes";
        } else {
            mostraDialogo(
                res,
                "danger",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Erro na requisição da URL /representacoes/controlar/lancar/despesas/enviar.php. <br />" +
            "Status: "+request.status+" "+request.statusText,
            "danger",
            3000
        );
    }
}