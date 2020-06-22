const tipo = document.getElementById('tipo');
const proprietario = document.getElementById('proprietario');

var idcaminhao = 0;

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
    let caminhao = get('/representacoes/gerenciar/caminhao/detalhes/obter.php');
    if (caminhao === null || caminhao === "") {
        alert("Caminhão não selecionado.");
        location.href = "../caminhao";
    }

    $("#anofab").mask('0000', {reverse: false});
    $("#anomod").mask('0000', {reverse: false});
    
    let tipos = get('/representacoes/gerenciar/caminhao/detalhes/obter-tipos.php');
    if (tipos !== null && tipos !== "") {
        for (let i = 0; i < tipos.length; i++) {
            let option = document.createElement("option");
            option.value = tipos[i].id;
            option.text = tipos[i].descricao;
            tipo.appendChild(option);
        }
    }

    let props = get('/representacoes/gerenciar/caminhao/detalhes/obter-proprietarios.php');
    if (props !== null && props !== "") {
        for (let i = 0; i < props.length; i++) {
            let option = document.createElement("option");
            option.value = props[i].id;
            option.text = (props[i].tipo === 1) ? props[i].pessoaFisica.nome : props[i].pessoaJuridica.nomeFantasia;
            proprietario.appendChild(option);
        }
    }

    if (caminhao !== null && caminhao !== "") {
        idcaminhao = caminhao.id;
        $('#placa').val(caminhao.placa);
        $('#marca').val(caminhao.marca);
        $('#modelo').val(caminhao.modelo);
        $("#cor").val(caminhao.cor);
        $('#anofab').val(caminhao.anoFabricacao);
        $("#anomod").val(caminhao.anoModelo);
        $('#tipo').val(caminhao.tipo.id);
        $('#proprietario').val(caminhao.proprietario.id);
    }
});

function gravar() {
    let placa = $("#placa").val();
    let marca = $("#marca").val();
    let modelo = $("#modelo").val();
    let cor = $("#cor").val();
    let anofab = $("#anofab").val();
    let anomod = $("#anomod").val();
    let tipo = $("#tipo").val();
    let prop = $("#proprietario").val();

    let erros = 0;

    if (placa === "") {
        erros++;
        $("#msplaca").html('<span class="label label-danger">A placa do caminhão precisa ser preenchida!</span>');
    } else {
        let regex = /[^a-zA-Z0-9-]/g;
        if (regex.test(placa) || placa.length < 7) {
            erros++;
            $("#msplaca").html('<span class="label label-danger">A placa prenchida possui caracteres ou tamanho inválido.</span>');
        } else {
            $("#msplaca").html('');
        }
    }

    if (marca === "") {
        erros++;
        $("#msmarca").html('<span class="label label-danger">A marca do caminhão precisa ser preenchida!</span>');
    } else {
        if (/[^ \bA-Za-z]/g.test(marca) || marca.length < 3) {
            erros++;
            $("#msmarca").html('<span class="label label-danger">A marca preenchida possui caracteres ou tamanho inválido.</span>');
        } else {
            $("#msmarca").html('');
        }
    }

    if (modelo === "") {
        erros++;
        $("#msmodelo").html('<span class="label label-danger">O modelo do caminhão deve ser preenchido!</span>');
    } else {
        if (/[^ \bA-Za-z0-9]/g.test(modelo) || modelo.length < 4) {
            erros++;
            $("#msmodelo").html('<label class="label label-danger">O modelo preenchido possui caracteres ou tamanho inválido.</label>');
        } else {
            $("#msmodelo").html('');
        }
    }

    if (cor === "") {
        erros++;
        $("#mscor").html('<span class="label label-danger">A cor do caminhão precisa ser preenchida!</span>');
    } else {
        if (/[^ \bA-Za-z]/g.test(cor) || cor.length <= 4) {
            erros++;
            $("#mscor").html('<span class="label label-danger">A cor preenchida possui caracteres ou tamanho inválido.</span>');
        } else {
            $("#mscor").html('');
        }
    }

    if (anofab === "") {
        erros++;
        $("#msanofab").html('<span class="label label-danger">O ano deve ser preenchido!</span>');
    } else {
        $("#msanofab").html('');
    }

    if (anomod === "") {
        erros++;
        $("#msanomod").html('<span class="label label-danger">O ano deve ser preenchido!</span>');
    } else {
        $("#msanomod").html('');
    }

    if (tipo === "0"){
        erros++;
        $("#mstipo").html('<span class="label label-danger">O tipo precisa ser selecionado!</span>');
    } else {
        $("#mstipo").html('');
    }

    if (prop === "0"){
        erros++;
        $("#msprop").html('<span class="label label-danger">O proprietário do caminhão precisa ser selecionado!</span>');
    } else {
        $("#msprop").html('');
    }

    if (erros === 0) {
        let form = new FormData();
        form.append("caminhao", idcaminhao);
        form.append("placa", placa);
        form.append("marca", marca);
        form.append("modelo", modelo);
        form.append("cor", cor);
        form.append("anofab", anofab);
        form.append("anomod", anomod);
        form.append("tipo", tipo);
        form.append("proprietario", prop);

        $.ajax({
            type: "POST",
            url: "/representacoes/gerenciar/caminhao/detalhes/alterar.php",
            data: form,
            contentType: false,
            processData: false,
            async: false,
            success: function(response) {
                if (response === "") {
                    mostraDialogo(
                        "<strong>Caminhão alterado com sucesso!</strong>" +
                        "<br />Os dados do caminhão foram salvos com sucesso no banco de dados.",
                        "success",
                        2000
                    );
                } else {
                    mostraDialogo(
                        "<strong>Problemas ao alterar o caminhão...</strong>" +
                        "<br/>response",
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
    } else {
        $("#placa").val(placa);
        $("#marca").val(marca);
        $("#modelo").val(modelo);
        $("#anofab").val(anofab);
        $("#anomod").val(anomod);
        $("#tipo").val(tipo);
        $("#proprietario").val(prop);
    }
}

function limpar() {
    $("input[type='text']").val("");
    $("#tipo").val("0");
    $("#proprietario").val("0");
}