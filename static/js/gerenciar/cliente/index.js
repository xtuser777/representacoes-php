function preencherTabela(dados) {
    let txt = "";
    $.each(dados, function () {
        let nome = (this.tipo === 1) ? this.pessoaFisica.nome : this.pessoaJuridica.nomeFantasia;
        let doc = (this.tipo === 1) ? this.pessoaFisica.cpf : this.pessoaJuridica.cnpj;
        let email = (this.tipo === 1) ? this.pessoaFisica.contato.email : this.pessoaJuridica.contato.email;
        let tipo = (this.tipo === 1) ? "Física" : "Jurídica";
        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + nome + '</td>\
                <td>' + doc + '</td>\
                <td>' + FormatarData(this.cadastro) + '</td>\
                <td>'+ tipo +'</td>\
                <td>'+ email +'</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
            </tr>';
    });
    $("#tbody_clientes").html(txt);
}

function ordenar() {
    let ord = $("#cbord").val();

    $.ajax({
        type: 'POST',
        url: '/representacoes/gerenciar/cliente/ordenar.php',
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
        error: function (err) {alert(err.d);}
    });
    return res;
}

function obter() {
    let data = get("/representacoes/gerenciar/cliente/obter.php");
    preencherTabela(data);
}

$(document).ready(function (event) {
    obter();
});

function filtrar() {
    let filtro = $("#filtro").val();
    let cadastro = $("#filtro_cad").val();

    if (filtro === "" && cadastro === "") {
        obter();
    } else {
        if (filtro !== "" && cadastro !== "") {
            $.ajax({
                type: 'POST',
                url: '/representacoes/gerenciar/cliente/obter-por-chave-cad.php',
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
                    url: '/representacoes/gerenciar/cliente/obter-por-chave.php',
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
                        url: '/representacoes/gerenciar/cliente/obter-por-cadastro.php',
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
        message: "Confirma a exclusão deste cliente?",
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
                    url: '/representacoes/gerenciar/cliente/excluir.php',
                    data: {id: id},
                    success: function (result) {
                        if (result === "") {
                            obter();
                        } else {
                            alert("Ocorreu um problema ao excluir este cliente...");
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
        url: '/representacoes/gerenciar/cliente/enviar.php',
        data: { id: id },
        success: function (result) {
            if (result.length > 0) alert(result);
            else {
                window.location.href = "../../gerenciar/cliente/detalhes";
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