function ordenarRepresentacoes() {
    var ord = $("#cbord").val();

    $.ajax({
        type: 'POST',
        url: '/gerenciar/representacao/ordenar.php',
        async: false,
        data: { col : ord },
        success: function (response) { preencherTabela(response); },
        error: function () { alert("Ocorreu um problema ao comunicar-se com o servidor..."); }
    });
}

function preencherTabela(dados) {
    var txt = "";
    $.each(dados, function () {
        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                        <td>' + this.pessoa.nomeFantasia + '</td>\
                        <td>' + this.pessoa.cnpj + '</td>\
                        <td>' + FormatarData(this.cadastro) + '</td>\
                        <td>' + this.unidade + '</td>\
                        <td>'+ this.pessoa.contato.email +'</td>\
                        <td><a role="button" class="glyphicon glyphicon-plus" data-toggle="tooltip" data-placement="top" title="ADICIONAR UNIDADE" href="javascript:adicionarUnidade('+ this.id +')"></a></td>\
                        <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                        <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
                    </tr>';
    });
    $("#tbody_representacoes").html(txt);
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

function obterRepresentacoes() {
    var data = get("/gerenciar/representacao/obter.php");
    preencherTabela(data);
}

$(document).ready(function(event) {
    obterRepresentacoes();
});

function filtrarRepresentacoes() {
    var filtro = $("#filtro").val();
    var cadastro = $("#filtro_cad").val();

    if (filtro === "" && cadastro === "") {
        obterRepresentacoes();
    } else {
        if (filtro !== "" && cadastro !== "") {
            $.ajax({
                type: 'POST',
                url: '/gerenciar/representacao/obter-por-chave-cad.php',
                data: { chave: filtro, cad: cadastro },
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
                    url: '/gerenciar/representacao/obter-por-chave.php',
                    data: { chave: filtro },
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
                if (cadastro !== ""){
                    $.ajax({
                        type: 'POST',
                        url: '/gerenciar/representacao/obter-por-cadastro.php',
                        data: { cad: cadastro },
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

function adicionarUnidade(id) {
    $.ajax({
        type: 'POST',
        url: '/gerenciar/representacao/enviar.php',
        data: {id: id},
        async: false,
        success: function (response) {
            if (response.length > 0) {
                alert(response);
            } else {
                window.location.href = "../../gerenciar/representacao/addunidade";
            }
        },
        erros: function (XMLHttpRequest, txtStatus, errorThrown) {
            alert("Status: " + txtStatus);
            alert("Error: " + errorThrown);
        }
    });
}

function excluir(id) {
    bootbox.confirm({
        message: "Confirma a exclusão desta representação?",
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
                    url: '/gerenciar/representacao/excluir.php',
                    data: {id: id},
                    success: function (result) {
                        if (result.length > 0) {
                            mostraDialogo(
                                "<strong>"+result+"</strong>",
                                "danger",
                                2000
                            );
                        } else {
                            obterRepresentacoes();
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
        url: '/gerenciar/representacao/enviar.php',
        data: {id: id},
        async: false,
        success: function (response) {
            if (response.length > 0) {
                mostraDialogo(
                    "Ocorreu um problema ao enviar as informações da representação...",
                    "danger",
                    2000
                );
            } else {
                window.location.href = "../../gerenciar/representacao/detalhes";
            }
        }
    });
}