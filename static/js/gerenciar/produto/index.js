function preencherTabela(dados) {
    var txt = "";
    $.each(dados, function () {
        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                        <td>' + this.descricao + '</td>\
                        <td>' + this.medida + '</td>\
                        <td>' + this.preco + '</td>\
                        <td>' + this.representacao.pessoa.nomeFantasia + '</td>\
                        <td><a role="button" class="glyphicon glyphicon-plus" data-toggle="tooltip" data-placement="top" title="VINCULAR TIPOS DE CAMINHÃO" href="javascript:vincularTipos('+ this.id +')"></a></td>\
                        <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                        <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
                    </tr>';
    });
    $("#tbody_produtos").html(txt);
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
        error: function (err) {alert(err.d);}
    });
    return res;
}

function obterProdutos() {
    var dados = get("/gerenciar/produto/obter.php");
    preencherTabela(dados);
}

$(document).ready(function (event) {
    obterProdutos();

    var representacao = document.getElementById("representacao");
    var representacoes = get("/gerenciar/produto/obter-representacoes.php");
    if (representacoes != null && representacoes !== "") {
        for (var i = 0; i < representacoes.length; i++) {
            var option = document.createElement("option");
            option.value = representacoes[i].id;
            option.text = representacoes[i].pessoa.nomeFantasia + " (" + representacoes[i].unidade + ")";
            representacao.appendChild(option);
        }
    }
});

function filtrarProdutos() {
    var filtro = $("#filtro").val().trim();
    var rep = $("#representacao").val();

    if (filtro === "" && rep === "0") {
        obterProdutos();
    } else {
        if (filtro !== "" && rep !== "0") {
            $.ajax({
                type: "POST",
                url: "/gerenciar/produto/obter-por-chave-representacao.php",
                data: { filtro: filtro, representacao: rep },
                async: false,
                success: function (result) {
                    preencherTabela(result);
                },
                error: function (XMLHttpRequest, txtStatus, errorThrown) {
                    mostraDialogo(
                        "<strong>Ocorreu um erro ao se comunicar com o servidor...</strong>" +
                        "<br/>" + errorThrown,
                        "danger",
                        2000
                    );
                }
            });
        } else {
            if (filtro !== "") {
                $.ajax({
                    type: "POST",
                    url: "/gerenciar/produto/obter-por-chave.php",
                    data: { filtro: filtro },
                    async: false,
                    success: function (result) {
                        preencherTabela(result);
                    },
                    error: function (XMLHttpRequest, txtStatus, errorThrown) {
                        mostraDialogo(
                            "<strong>Ocorreu um erro ao se comunicar com o servidor...</strong>" +
                            "<br/>" + errorThrown,
                            "danger",
                            2000
                        );
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "/gerenciar/produto/obter-por-representacao.php",
                    data: { representacao: rep },
                    async: false,
                    success: function (result) {
                        preencherTabela(result);
                    },
                    error: function (XMLHttpRequest, txtStatus, errorThrown) {
                        mostraDialogo(
                            "<strong>Ocorreu um erro ao se comunicar com o servidor...</strong>" +
                            "<br/>" + errorThrown,
                            "danger",
                            2000
                        );
                    }
                });
            }
        }
    }
}

function ordenarProdutos() {
    var ord = $("#cbord").val();

    $.ajax({
        type: "POST",
        url: "/gerenciar/produto/ordenar.php",
        data: { col: ord },
        async: false,
        success: function (result) {
            preencherTabela(result);
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

function vincularTipos(id) {
    $.ajax({
        type: 'POST',
        url: '/gerenciar/produto/enviar.php',
        data: { id: id },
        success: function (result) {
            if (result.length > 0) alert(result);
            else {
                window.location.href = "../../gerenciar/produto/tipocaminhao";
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

function alterar(id) {
    $.ajax({
        type: 'POST',
        url: '/gerenciar/produto/enviar.php',
        data: { id: id },
        success: function (result) {
            if (result.length > 0) alert(result);
            else {
                window.location.href = "../../gerenciar/produto/detalhes";
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

function excluir(id) {
    bootbox.confirm({
        message: "Confirma a exclusão deste registro?",
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
                    url: '/gerenciar/produto/excluir.php',
                    data: { id: id },
                    success: function (result) {
                        if (result === "") {
                            obterProdutos();
                        }
                        else {
                            mostraDialogo(
                                "<strong>Ocorreu um erro ao se comunicar com o servidor...</strong>" +
                                "<br/>"+result,
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
            }
        }
    });
}