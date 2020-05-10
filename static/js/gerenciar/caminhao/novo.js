var tipo = document.getElementById('tipo');
var proprietario = document.getElementById('proprietario');

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
    $("#anofab").mask('0000', {reverse: false});
    $("#anomod").mask('0000', {reverse: false});
    
    var tipos = get('/gerenciar/tipocaminhao/obter.php');
    if (tipos !== null && tipos !== "") {
        for (var i = 0; i < tipos.length; i++) {
            var option = document.createElement("option");
            option.value = tipos[i].id;
            option.text = tipos[i].descricao;
            tipo.appendChild(option);
        }
    }

    var props = get('/gerenciar/motorista/obter.php');
    if (props !== null && props !== "") {
        for (var i = 0; i < props.length; i++) {
            var option = document.createElement("option");
            option.value = props[i].id;
            option.text = props[i].pessoa.nome;
            proprietario.appendChild(option);
        }
    }
});

function gravar() {
    var placa = $("#placa").val();
    var marca = $("#marca").val();
    var modelo = $("#modelo").val();
    var anofab = $("#anofab").val();
    var anomod = $("#anomod").val();
    var tipo = $("#tipo").val();
    var prop = $("#proprietario").val();

    var erros = 0;

    if (placa === "") {
        erros++;
        $("#msplaca").html('<span class="label label-danger">A placa do caminhão precisa ser preenchida!</span>');
    } else {
        $("#msplaca").html('');
    }

    if (marca === "") {
        erros++;
        $("#msmarca").html('<span class="label label-danger">A marca do caminhão precisa ser preenchida!</span>');
    } else {
        $("#msmarca").html('');
    }

    if (modelo === "") {
        erros++;
        $("#msmodelo").html('<span class="label label-danger">O modelo do caminhão deve ser preenchido!</span>');
    } else {
        $("#msmodelo").html('');
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
        var form = new FormData();
        form.append("placa", placa);
        form.append("marca", marca);
        form.append("modelo", modelo);
        form.append("anofab", anofab);
        form.append("anomod", anomod);
        form.append("tipo", tipo);
        form.append("proprietario", prop);

        $.ajax({
            type: "POST",
            url: "/gerenciar/caminhao/novo/gravar.php",
            data: form,
            contentType: false,
            processData: false,
            async: false,
            success: function(response) {
                if (response === "") {
                    mostraDialogo(
                        "<strong>Caminhão gravado com sucesso!</strong>" +
                        "<br />Os dados do novo caminhão foram salvos com sucesso no banco de dados.",
                        "success",
                        2000
                    );
                    limpar();
                } else {
                    mostraDialogo(
                        "<strong>Problemas ao salvar o novo caminhão...</strong>" +
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