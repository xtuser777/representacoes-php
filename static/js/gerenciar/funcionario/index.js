var nivel_atual = "";
        
function preencherTabela(dados) {
    var txt = "";
    $.each(dados, function () {
        var tipo = (this.funcionario.tipo === 1) ? "Interno" : "Vendedor";
        var ativo = (this.ativo === true) ? "Sim" : "Não";
        txt += 
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.funcionario.pessoa.nome + '</td>\
                <td>' + this.login + '</td>\
                <td>' + this.nivel.descricao + '</td>\
                <td>' + this.funcionario.pessoa.cpf + '</td>\
                <td>' + FormatarData(this.funcionario.admissao) + '</td>\
                <td>' + tipo + '</td>\
                <td>' + ativo + '</td>\
                <td>' + this.funcionario.pessoa.contato.email + '</td>';
                if (ativo === "Sim") {
                    txt += '<td><a role="button" class="glyphicon glyphicon-off" data-toggle="tooltip" data-placement="top" title="DESATIVAR" href="javascript:desativar('+ this.id +',\''+ this.nivel.descricao +'\')"></a></td>';
                } else {
                    txt += '<td><a role="button" class="glyphicon glyphicon-off" data-toggle="tooltip" data-placement="top" title="REATIVAR" href="javascript:reativar('+ this.id +')"></a></td>';          
                }
                txt += '<td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ',\''+this.nivel.descricao +'\')"></a></td>\
            </tr>';
    });
    $("#tbody_funcionarios").html(txt);
}

function ordenarLista() {
    var ord = $("#cbord").val();

    $.ajax({
        type: 'POST',
        url: '/gerenciar/funcionario/ordenar.php',
        async: false,
        data: { col: ord },
        success: function (response) { preencherTabela(response); },
        error: function () { alert("Ocorreu um problema ao comunicar-se com o servidor..."); }
    });
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

function obterFuncionarios() {
    var data = get("/gerenciar/funcionario/obter.php");
    preencherTabela(data);
}

$(document).ready(function (event) {
    obterFuncionarios();
});

function verificarAdmin() {
    var data = get("/gerenciar/funcionario/is-last-admin.php");
    return (data === true && nivel_atual === "Administrador");
}

function filtrarFuncionarios() {
    var filtro = $("#filtro").val();
    var admissao = $("#filtro_adm").val();

    if (filtro === "" && admissao === "") {
        obterFuncionarios();
    } else {
        if (filtro !== "" && admissao !== "") {
            $.ajax({
                type: 'POST',
                url: '/gerenciar/funcionario/obter-por-chave-adm.php',
                data: { chave: filtro, adm: admissao },
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
                    url: '/gerenciar/funcionario/obter-por-chave.php',
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
                if (admissao !== ""){
                    $.ajax({
                        type: 'POST',
                        url: '/gerenciar/funcionario/obter-por-adm.php',
                        data: { adm: admissao },
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

function excluir(id, nivel) {
    nivel_atual = nivel;

    if (verificarAdmin() === true) {
        alert("Não é possível excluir o último administrador.");
    } else {
        bootbox.confirm({
            message: "Confirma o excluir deste funcionário?",
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
                        url: '/gerenciar/funcionario/excluir.php',
                        data: {id: id},
                        success: function (result) {
                            if (result.length > 0) {
                                mostraDialogo(
                                    "<strong>Ocorreu um problema ao excluir o funcionário...</strong>\
                                    <br/>"+result,
                                    "danger",
                                    2000
                                );
                            } else {
                                obterFuncionarios();
                            }
                        },
                        error: function (XMLHttpRequest, txtStatus, errorThrown) {
                            alert("Status: " + txtStatus);
                            alert("Error: " + errorThrown);
                            $("#divLoading").hide(300);
                        }
                    });
                }
            }
        });
    }
}

function desativar(id, nivel) {
    nivel_atual = nivel;

    if (verificarAdmin() === true) {
        alert("Não é possível excluir o último administrador.");
    } else {
        bootbox.confirm({
            message: "Confirma o desligamento deste funcionário?",
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
                        url: '/gerenciar/funcionario/desativar.php',
                        data: {id: id},
                        success: function (result) {
                            if (result === '') {
                                obterFuncionarios();
                            } else {
                                alert(result);
                            }
                        },
                        error: function (XMLHttpRequest, txtStatus, errorThrown) {
                            alert("Status: " + txtStatus);
                            alert("Error: " + errorThrown);
                            $("#divLoading").hide(300);
                        }
                    });
                }
            }
        });
    }
}

function reativar(id) {
    bootbox.confirm({
        message: "Confirma a Reativação deste funcionário?",
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
                    url: '/gerenciar/funcionario/reativar.php',
                    data: { id: id },
                    success: function (result) {
                        if (result === '') {
                            obterFuncionarios();
                        }
                        else {
                            alert(result);
                        }
                    },
                    error: function (XMLHttpRequest, txtStatus, errorThrown) {
                        alert("Status: " + txtStatus); alert("Error: " + errorThrown);
                        $("#divLoading").hide(300);
                    }
                });
            }
        }
    });   
}

function alterar(id) {
    $.ajax({
        type: 'POST',
        url: '/gerenciar/funcionario/enviar.php',
        data: { id: id },
        success: function (result) {
            if (result.length > 0) alert(result);
            else {
                window.location.href = "../../gerenciar/funcionario/detalhes.php";
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