const textFiltro = document.getElementById("filtro");
const filtroData = document.getElementById("filtro_data");
const selectOrd = document.getElementById("cbord");
const tableOrcamentos = document.getElementById("table_orcamentos");
const tbodyOrcamentos = document.getElementById("tbody_orcamentos");

function preencherTabela(dados) {
    let txt = "";
    $.each(dados, function () {
        let cliente = this.cliente.tipo === 1 ? this.cliente.pessoaFisica.nome : this.cliente.pessoaJuridica.nomeFantasia;
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
                <td>'+ this.autor.funcionario.pessoa.nome +'</td>\
                <td>'+ valorFormat +'</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
            </tr>';
    });
    $(tbodyOrcamentos).html(txt);
}

function obter() {
    let data = get("/representacoes/orcamento/frete/obter.php");
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

function ordenar() {
    let ord = selectOrd.value;

    $.ajax({
        type: 'POST',
        url: '/representacoes/orcamento/frete/ordenar.php',
        async: false,
        data: { col : ord },
        success: function (response) { preencherTabela(response); },
        error: function () { alert("Ocorreu um problema ao comunicar-se com o servidor..."); }
    });
}

function filtrar() {
    let filtro = textFiltro.value;
    let data = filtroData.value;

    if (filtro === "" && data === "") {
        obter();
    } else {
        if (filtro !== "" && data !== "") {
            $.ajax({
                type: 'POST',
                url: '/representacoes/orcamento/frete/obter-por-filtro-data.php',
                data: { filtro: filtro, data: data },
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
                    url: '/representacoes/orcamento/frete/obter-por-filtro.php',
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
                if (data !== ""){
                    $.ajax({
                        type: 'POST',
                        url: '/representacoes/orcamento/frete/obter-por-data.php',
                        data: { data: data },
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
        message: "Confirma a exclusão deste orçamento?",
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
                    url: '/representacoes/orcamento/frete/excluir.php',
                    data: {
                        id: id
                    },
                    success: function (result) {
                        if (result === "") {
                            obter();
                        } else {
                            alert("Ocorreu um problema ao excluir este orçamento...");
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
        url: '/representacoes/orcamento/frete/enviar.php',
        data: {
            id: id
        },
        success: function (result) {
            if (result.length > 0) alert(result);
            else {
                window.location.href = "../../orcamento/frete/detalhes";
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