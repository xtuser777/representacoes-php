function preencherTabela(dados) {
    var txt = "";
    $.each(dados, function () {
        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.descricao + '</td>\
                <td>' + this.eixos + '</td>\
                <td>' + this.capacidade + '</td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir('+ this.id +')"></a></td>\
            </tr>';
    });
    $("#tbody_vinculos").html(txt);
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

$(document).ready(function (event) {
    var dados = get("/gerenciar/produto/tipocaminhao/obter.php");
    if (dados === null) {
        alert("Produto não selecionado.");
        return location.href = "../produto/index.php";
    }

    preencherTabela(dados);

    var tipos = get("/gerenciar/tipocaminhao/obter.php");
    if (tipos === null || tipos.length === 0) {
        alert("Não há tipos de caminhão cadastrados");
        location.href = "../produto/index.php";
    }

    var tipo = document.getElementById("select_tipo");

    if (tipos != null && tipos !== "") {
        for (var i = 0; i < tipos.length; i++) {
            var option = document.createElement("option");
            option.value = tipos[i].id;
            option.text = tipos[i].descricao;
            tipo.appendChild(option);
        }
    }
});

function filtrarVinculos() {
    var filtro = $("#filtro").val();

    if (filtro === "") {
        obterVinculos();
    } else {
        $.ajax({
            type: "POST",
            url: "/gerenciar/produto/tipocaminhao/obter-por-chave.php",
            data: {filtro: filtro},
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

function ordenarVinculos() {
    var ord = $("#cbord").val();

    $.ajax({
        type: "POST",
        url: "/gerenciar/produto/tipocaminhao/ordenar.php",
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

function excluir(t) {
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
                    url: '/gerenciar/produto/tipocaminhao/excluir.php',
                    data: { tipo: t},
                    success: function (result) {
                        if (result === "") {
                            obterVinculos();
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

function verificarTipo(tipo) {
    var res = true;

    $.ajax({
        type: "POST",
        url: "/gerenciar/produto/tipocaminhao/verificar-tipo.php",
        data: { tipo: tipo },
        async: false,
        success: function (result) {
            res = result;
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

    return res;
}

function adicionarTipo() {
    var tipo = $("#select_tipo").val();

    if (tipo !== "0") {
        if (verificarTipo(tipo) === false) {
            $.ajax({
                type: "POST",
                url: "/gerenciar/produto/tipocaminhao/adicionar.php",
                data: { tipo: tipo },
                async: false,
                success: function (result) {
                    if (result === "") {
                        obterVinculos();
                        mostraDialogo(
                            "Tipo de caminhão adicionado com sucesso!",
                            "success",
                            2000
                        );
                    } else {
                        mostraDialogo(
                            result,
                            "danger",
                            2000
                        );
                    }
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
            mostraDialogo(
                "<strong>Este vínculo já existe!</strong>",
                "info",
                2000
            );
        }
    }
}