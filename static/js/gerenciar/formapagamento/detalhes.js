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
        error: function (xhr, status, thrown) {
            console.error(thrown);
            alert(thrown);
        }
    });

    return res;
}

$(document).ready(function (event) {
    let dados = get("/representacoes/gerenciar/formapagamento/detalhes/obter.php");
    if (dados !== "") {
        _forma = dados.id;
        $("#desc").val(dados.descricao);
        $("#vinculo").val(dados.vinculo);
        $("#prazo").val(dados.prazo);
    }
});

function alterar() {
    let desc = $("#desc").val();
    let vinculo = $("#vinculo").val();
    let prazo = $("#prazo").val();

    let erros = 0;

    if (desc === "") {
        erros++;
        $("#msdesc").html('<span class="label label-danger">A descrição do tipo precisa ser preenchida!</span>');
    } else {
        $("#msdesc").html('');
    }

    if (vinculo === null || vinculo === "0") {
        erros++;
        $("#msvinculo").html('<span class="label label-danger">O vínculo desta forma de pagamento deve ser selecionada.</span>');
    } else {
        $("#msvinculo").html('');
    }

    if (prazo === "" || prazo === "0") {
        erros++;
        $("#msprazo").html('<span class="label label-danger">O prazo para pagamento precisa ser preenchido!</span>');
    } else {
        $("#msprazo").html('');
    }
    
    if (erros === 0) {
        let form = new FormData();
        form.append("forma", _forma);
        form.append("desc", desc);
        form.append("vinculo", vinculo);
        form.append("prazo", prazo);

        $.ajax({
            type: "POST",
            url: "/representacoes/gerenciar/formapagamento/detalhes/alterar.php",
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
    }
}

function limpar() {
    $("input[type='text']").val("");
    $("#vinculo").val("0");
    $("input[type='number']").val("0");
}