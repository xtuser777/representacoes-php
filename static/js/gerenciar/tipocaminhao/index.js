function preencherTabela(dados) {
    let txt = "";
    $.each(dados, function () {
        txt += 
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.descricao + '</td>\
                <td>' + this.eixos + '</td>\
                <td>' + this.capacidade + '</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
            </tr>';
    });
    $("#tbody_tipos").html(txt);
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

function obterTipos() {
    let dados = get("/representacoes/gerenciar/tipocaminhao/obter.php");

    preencherTabela(dados);
}

$(document).ready(function (event) {
    obterTipos();
});

function filtrarTipos() {
    let filtro = $("#filtro").val();
    
    if (filtro === "") {
        obterTipos();
    } else {
        $.ajax({
            type: "POST",
            url: "/representacoes/gerenciar/tipocaminhao/obter-por-chave.php",
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
    }
}

function ordenarTipos() {
    let ord = $("#cbord").val();

    $.ajax({
        type: "POST",
        url: "/representacoes/gerenciar/tipocaminhao/ordenar.php",
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

function alterar(id) {
    $.ajax({
        type: 'POST',
        url: '/representacoes/gerenciar/tipocaminhao/enviar.php',
        data: { id: id },
        success: function (result) {
            if (result.length > 0) alert(result);
            else {
                window.location.href = "../../gerenciar/tipocaminhao/detalhes";
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
                    url: '/representacoes/gerenciar/tipocaminhao/excluir.php',
                    data: { id: id },
                    success: function (result) {
                        if (result === "") {
                            obterTipos();
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