function gravar() {
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
        form.append("desc", desc);
        form.append("vinculo", vinculo);
        form.append("prazo", prazo);

        $.ajax({
            type: "POST",
            url: "/representacoes/gerenciar/formapagamento/novo/gravar.php",
            data: form,
            contentType: false,
            processData: false,
            async: false,
            success: function(response) {
                if (response === "") {
                    mostraDialogo(
                        "<strong>Forma de pagamento gravada com sucesso!</strong>" +
                        "<br />Os dados do nova forma de pagamento foram salvos com sucesso no banco de dados.",
                        "success",
                        2000
                    );
                    limpar();
                } else {
                    mostraDialogo(
                        "<strong>Problemas ao salvar a nova forma de pagamento...</strong>" +
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
    $("#vinculo").val("0");
    $("input[type='number']").val("0");
}