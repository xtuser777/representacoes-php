const textFiltro = document.getElementById("filtro");
const filtroData = document.getElementById("filtro_data");
const slOrdem = document.getElementById("slOrdem");
const tablePedidos = document.getElementById("table_pedidos");
const tbodyPedidos = document.getElementById("tbody_pedidos");

function preencherTabela(dados) {
    var txt = "";
    $.each(dados, function () {
        let cliente = (this.cliente.tipo === 1) ? this.cliente.pessoaFisica.nome : this.cliente.pessoaJuridica.nomeFantasia;

        let valorFormat = this.valor.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');
        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.descricao + '</td>\
                <td>' + cliente + '</td>\
                <td>' + FormatarData(this.data) + '</td>\
                <td>' + this.autor.funcionario.pessoa.nome + '</td>\
                <td>' + this.formaPagamento.descricao + '</td>\
                <td>'+ valorFormat +'</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
            </tr>';
    });
    $(tbodyPedidos).html(txt);
}

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

function obter() {
    let data = get("/representacoes/pedido/venda/obter.php");
    preencherTabela(data);
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
    let data = filtroData.value;
    let ordem = slOrdem.value;

    if (filtro === "" && data === "") {
        obter();
    } else {
        if (filtro !== "" && data !== "") {
            $.ajax({
                type: 'POST',
                url: '/representacoes/pedido/venda/obter-por-filtro-data.php',
                data: { filtro: filtro, data: data, ordem: ordem },
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
            if (filtro !== "") {
                $.ajax({
                    type: 'POST',
                    url: '/representacoes/pedido/venda/obter-por-filtro.php',
                    data: { filtro: filtro, ordem: ordem },
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
                if (data !== ""){
                    $.ajax({
                        type: 'POST',
                        url: '/representacoes/pedido/venda/obter-por-data.php',
                        data: { data: data, ordem: ordem },
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
                    "<strong>Ocorreu um problema ao alterar este pedido.</strong><br />" +
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
