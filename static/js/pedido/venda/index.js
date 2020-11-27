const textFiltro = document.getElementById("textFiltro");
const dateFiltroDataInicio = document.getElementById("dateFiltroDataInicio");
const dateFiltroDataFim = document.getElementById("dateFiltroDataFim");
const selectOrdem = document.getElementById("selectOrdem");
const tablePedidos = document.getElementById("table_pedidos");
const tbodyPedidos = document.getElementById("tbody_pedidos");

function preencherTabela(dados) {
    let txt = "";

    $.each(dados, function () {
        let cliente = (this.cliente.tipo === 1) ? this.cliente.pessoaFisica.nome : this.cliente.pessoaJuridica.nomeFantasia;

        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.descricao + '</td>\
                <td>' + cliente + '</td>\
                <td>' + FormatarData(this.data) + '</td>\
                <td>' + this.autor.funcionario.pessoa.nome + '</td>\
                <td>' + this.formaPagamento.descricao + '</td>\
                <td>'+ formatarValor(this.valor) +'</td>\
                <td><a role="button" class="glyphicon glyphicon-plus" data-toggle="tooltip" data-placement="top" title="MAIS DETALHES" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
            </tr>';
    });
    $(tbodyPedidos).html(txt);
}

function obter(ordem = "1") {
    $.ajax({
        type: "POST",
        url: "/representacoes/pedido/venda/obter.php",
        data: {
            ordem: ordem
        },
        async: false,
        success: function (response) {
            preencherTabela(response);
        },
        error: function (xhr, status, thrown) {

        }
    });
}

$(document).ready(function (event) {
    let prods = get("/representacoes/gerenciar/produto/obter.php");
    if (prods === null || prods.length === 0) {
        alert("Não existem produtos cadastrados!");
        location.href = "../../inicio";
    }
    obter();
});

function filtrar() {
    let filtro = textFiltro.value;
    let dataInicio = dateFiltroDataInicio.value;
    let dataFim = dateFiltroDataFim.value;
    let ordem = selectOrdem.value;

    let inicio = new Date(dataInicio + " 12:00:01");
    let fim = new Date(dataFim + " 12:00:01");

    if (filtro === "" && dataInicio === "" && dataFim === "") {
        obter(ordem);
    } else {
        if (filtro !== "" && dataInicio !== "" && dataFim !== "") {
            if (inicio > fim) {
                mostraDialogo(
                    "A data de início deve ser igual ou menor que a data final.",
                    "danger",
                    3000
                );
            } else {
                if (dataInicio === dataFim) {
                    $.ajax({
                        type: 'POST',
                        url: '/representacoes/pedido/venda/obter-por-filtro-data.php',
                        data: {
                            filtro: filtro,
                            data: dataInicio,
                            ordem: ordem
                        },
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
                    $.ajax({
                        type: 'POST',
                        url: '/representacoes/pedido/venda/obter-por-filtro-periodo.php',
                        data: {
                            filtro: filtro,
                            inicio: dataInicio,
                            fim: dataFim,
                            ordem: ordem
                        },
                        success: function (response) {
                            if (response != null && response !== ""){
                                preencherTabela(response);
                            }
                        },
                        error: function () {
                            alert("Ocorreu um erro ao comunicar-se com o servidor...");
                        }
                    });
                }
            }

        } else {
            if (filtro !== "" && dataInicio === "" && dataFim === "") {
                $.ajax({
                    type: 'POST',
                    url: '/representacoes/pedido/venda/obter-por-filtro.php',
                    data: {
                        filtro: filtro,
                        ordem: ordem
                    },
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
                if (filtro === "" && dataInicio !== "" && dataFim !== "") {
                    if (inicio > fim) {
                        mostraDialogo(
                            "A data de início deve ser igual ou menor que a data final.",
                            "danger",
                            3000
                        );
                    } else {
                        if (dataInicio === dataFim) {
                            $.ajax({
                                type: 'POST',
                                url: '/representacoes/pedido/venda/obter-por-data.php',
                                data: {
                                    filtro: filtro,
                                    data: dataInicio,
                                    ordem: ordem
                                },
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
                            $.ajax({
                                type: 'POST',
                                url: '/representacoes/pedido/venda/obter-por-periodo.php',
                                data: {
                                    filtro: filtro,
                                    inicio: dataInicio,
                                    fim: dataFim,
                                    ordem: ordem
                                },
                                success: function (response) {
                                    if (response != null && response !== ""){
                                        preencherTabela(response);
                                    }
                                },
                                error: function () {
                                    alert("Ocorreu um erro ao comunicar-se com o servidor...");
                                }
                            });
                        }
                    }
                } else {
                    if (dataInicio === "" || dataFim === "") {
                        mostraDialogo(
                            "A data de início e a data final devem estar preenchidas.",
                            "danger",
                            3000
                        );
                    }
                }
            }
        }
    }
}

function excluir(id) {
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
                        id: id
                    },
                    success: function (result) {
                        if (result === "") {
                            obter();
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

function alterar(id) {
    $.ajax({
        type: 'POST',
        url: '/representacoes/pedido/venda/enviar.php',
        data: {
            id: id
        },
        success: function (result) {
            if (result.length > 0) {
                mostraDialogo(
                    "<strong>Ocorreu um problema ao detalhar este pedido.</strong><br />" +
                    result,
                    "danger",
                    3000
                );
            } else {
                window.location.href = "../../pedido/venda/detalhes";
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
}
