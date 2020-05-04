var _forma = 0;

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
    var dados = get("/gerenciar/formapagamento/detalhes/obter.php");
    if (dados !== "") {
        _forma = dados.id;
        $("#desc").val(dados.descricao);
        $("#prazo").val(dados.prazo);
    }
});

function gravar() {
    var desc = $("#desc").val();
    var prazo = $("#prazo").val();
    
    var erros = 0;

    if (desc === "") {
        erros++;
        $("#msdesc").html('<span class="label label-danger">A descrição do tipo precisa ser preenchida!</span>');
    } else {
        $("#msdesc").html('');
    }

    if (prazo === "" || prazo === "0") {
        erros++;
        $("#msprazo").html('<span class="label label-danger">O prazo para pagamento precisa ser preenchido!</span>');
    } else {
        $("#msprazo").html('');
    }
    
    if (erros === 0) {
        var form = new FormData();
        form.append("forma", _forma);
        form.append("desc", desc);
        form.append("prazo", prazo);

        $.ajax({
            type: "POST",
            url: "/gerenciar/formapagamento/detalhes/alterar.php",
            data: form,
            contentType: false,
            processData: false,
            async: false,
            success: function(response) {
                if (response === "") {
                    mostraDialogo(
                        "<strong>Alterações da forma de pagamento foram salvas com sucesso!</strong>" +
                        "<br />As alterações da forma de pagamento foram salvos com sucesso no banco de dados.",
                        "success",
                        2000
                    );
                } else {
                    mostraDialogo(
                        "<strong>Problemas ao alterar a forma de pagamento...</strong>" +
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
        $("#desc").val(desc);
        $("#prazo").val(prazo);
    }
}

function limpar() {
    $("input[type='text']").val("");
    $("input[type='number']").val("0");
}