function gravar() {
    let desc = $("#desc").val();
    
    let erros = 0;

    if (desc === "") {
        erros++;
        $("#msdesc").html('<span class="label label-danger">A descrição da categoria precisa ser preenchida!</span>');
    } else {
        $("#msdesc").html('');
    }
    
    if (erros === 0) {
        let form = new FormData();
        form.append("desc", desc);

        $.ajax({
            type: "POST",
            url: "/representacoes/gerenciar/categoria/novo/gravar.php",
            data: form,
            contentType: false,
            processData: false,
            async: false,
            success: function(response) {
                if (response === "") {
                    mostraDialogo(
                        "<strong>Categoria de Contas gravada com sucesso!</strong>" +
                        "<br />Os dados da nova categoria foram salvos com sucesso no banco de dados.",
                        "success",
                        2000
                    );
                    limpar();
                } else {
                    mostraDialogo(
                        "<strong>Problemas ao salvar a nova categoria...</strong>" +
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
    }
}

function limpar() {
    $("input[type='text']").val("");
}