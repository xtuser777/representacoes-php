const textFiltro = document.getElementById("filtro");
const textFiltroCad = document.getElementById("filtro_cad");
const selectOrd = document.getElementById("cbord");
const tableProprietarios = document.getElementById("table_proprietarios");
const tbodyProprietarios = document.getElementById("tbody_proprietarios");

function preencherTabela(dados) {
    var txt = "";
    $.each(dados, function () {
        let nome = (this.tipo === 1) ? this.pessoaFisica.nome : this.pessoaJuridica.nomeFantasia;
        let doc = (this.tipo === 1) ? this.pessoaFisica.cpf : this.pessoaJuridica.cnpj;
        let email = (this.tipo === 1) ? this.pessoaFisica.contato.email : this.pessoaJuridica.contato.email;
        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                        <td>' + nome + '</td>\
                        <td>' + doc + '</td>\
                        <td>' + FormatarData(this.cadastro) + '</td>\
                        <td>'+ email +'</td>\
                        <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                        <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
                    </tr>';
    });
    $(tbodyProprietarios).html(txt);
}

function ordenar() {
    var ord = $(selectOrd).val();

    $.ajax({
        type: 'POST',
        url: '/gerenciar/proprietario/ordenar.php',
        async: false,
        data: { col : ord },
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
        error: function (XMLHttpRequest, txtStatus, errorThrown) { console.error(errorThrown); }
    });
    return res;
}

function obter() {
    var data = get("/gerenciar/proprietario/obter.php");
    preencherTabela(data);
}

$(document).ready(function (event) {
    obter();
});

function filtrar() {
    var filtro = textFiltro.value.toString();
    var cadastro = textFiltroCad.value.toString();

    if (filtro === "" && cadastro === "") {
        obter();
    } else {
        if (filtro !== "" && cadastro !== "") {
            $.ajax({
                type: 'POST',
                url: '/gerenciar/proprietario/obter-por-filtro-cad.php',
                data: { filtro: filtro, cad: cadastro },
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
                    url: '/gerenciar/proprietario/obter-por-filtro.php',
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
                if (cadastro !== ""){
                    $.ajax({
                        type: 'POST',
                        url: '/gerenciar/proprietario/obter-por-cadastro.php',
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

function excluir(id) {

    bootbox.confirm({
        message: "Confirma a exclusão deste proprietário?",
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
                    url: '/gerenciar/proprietario/excluir.php',
                    data: {id: id},
                    success: function (result) {
                        if (result.length > 0) {
                            obter();
                        } else {
                            alert("Ocorreu um problema ao excluir este proprietário...");
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
        url: '/gerenciar/proprietario/enviar.php',
        data: { id: id },
        success: function (result) {
            if (result.length > 0) alert(result);
            else {
                window.location.href = "../../gerenciar/proprietario/detalhes";
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