var _cat = 0;

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
    var dados = get("/gerenciar/categoria/detalhes/obter.php");
    if (dados !== "") {
        _cat= dados.id;
        $("#desc").val(dados.descricao);
    }
});

function gravar() {
    var desc = $("#desc").val();
    
    var erros = 0;

    if (desc === "") {
        erros++;
        $("#msdesc").html('<span class="label label-danger">A descrição da categoria precisa ser preenchida!</span>');
    } else {
        $("#msdesc").html('');
    }
    
    if (erros === 0) {
        var form = new FormData();
        form.append("categoria", _cat);
        form.append("desc", desc);

        $.ajax({
            type: "POST",
            url: "/gerenciar/categoria/detalhes/alterar.php",
            data: form,
            contentType: false,
            processData: false,
            async: false,
            success: function(response) {
                if (response === "") {
                    mostraDialogo(
                        "<strong>Alterações da Categoria foram salvas com sucesso!</strong>" +
                        "<br />As alterações da categoria foram salvos com sucesso no banco de dados.",
                        "success",
                        2000
                    );
                } else {
                    mostraDialogo(
                        "<strong>Problemas ao alterar a categoria...</strong>" +
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