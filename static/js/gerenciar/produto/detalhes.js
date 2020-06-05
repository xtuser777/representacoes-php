var _produto = 0;

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
    var dados = get("/gerenciar/produto/detalhes/obter.php");
    if (dados === null || dados === {}) {
        alert("Nenhum produto selecionado");
        location.href = "../index.php";
    }

    $('#peso').mask('000,000.0', { reverse: true });
    $('#preco').mask('00,000,000.00', { reverse: true });
    $('#preco_out').mask('00,000,000.00', { reverse: true });

    var representacao = document.getElementById("representacao");
    var representacoes = get("/gerenciar/produto/detalhes/obter-representacoes.php");
    if (representacoes != null && representacoes !== "") {
        for (var i = 0; i < representacoes.length; i++) {
            var option = document.createElement("option");
            option.value = representacoes[i].id;
            option.text = representacoes[i].pessoa.nomeFantasia + " (" + representacoes[i].unidade + ")";
            representacao.appendChild(option);
        }
    }

    if (dados !== "") {
        _produto = dados.id;
        $("#desc").val(dados.descricao);
        $("#medida").val(dados.medida);
        $("#peso").val(dados.peso);
        $("#preco").val(dados.preco);
        $("#preco_out").val(dados.precoOut);
        $("#representacao").val(dados.representacao.id);
    }
});

function gravar() {
    let desc = $("#desc").val();
    let medida = $("#medida").val();
    let peso = $("#peso").val();
    let preco = $("#preco").val();
    let preco_out = $("#preco_out").val();
    let rep = $("#representacao").val();

    let erros = 0;

    if (desc === "") {
        erros++;
        $("#msdesc").html('<span class="label label-danger">A descrição do produto precisa ser preenchida!</span>');
    } else {
        $("#msdesc").html('');
    }

    if (medida === "") {
        erros++;
        $("#msmedida").html('<span class="label label-danger">A unidade de medida precisa ser preenchida!</span>');
    } else {
        $("#msmedida").html('');
    }

    if (peso === "" || peso === "0") {
        erros++;
        $("#mspeso").html('<span class="label label-danger">O peso deve ser preenchido!</span>');
    } else {
        $("#mspeso").html('');
    }

    if (preco === "" || preco === "0") {
        erros++;
        $("#mspreco").html('<span class="label label-danger">O preço deve ser preenchido!</span>');
    } else {
        $("#mspreco").html('');
    }

    if (rep === "0") {
        erros++;
        $("#msrep").html('<span class="label label-danger">A representação precia ser selecionada!</span>');
    } else {
        $("#msrep").html('');
    }

    if (erros === 0) {
        var form = new FormData();
        form.append("produto", _produto);
        form.append("descricao", desc);
        form.append("medida", medida);
        form.append("peso", peso);
        form.append("preco", preco);
        form.append("preco_out", preco_out);
        form.append("representacao", rep);

        $.ajax({
            type: "POST",
            url: "/gerenciar/produto/detalhes/alterar.php",
            data: form,
            contentType: false,
            processData: false,
            async: false,
            success: function(response) {
                if (response === "") {
                    mostraDialogo(
                        "<strong>Alterações salvas com sucesso!</strong>" +
                        "<br />As alterações do produto foram salvas com sucesso no banco de dados.",
                        "success",
                        2000
                    );
                } else {
                    mostraDialogo(
                        "<strong>Problemas ao salvar as alterações...</strong>" +
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
        $("#medida").val(medida);
        $("#preco").val(preco);
        $("#preco_out").val(preco_out);
        $("#representacao").val(rep);
    }
}

function limpar() {
    $("input[type='text']").val("");
    $("#peso").val("0");
    $("#preco").val("0");
    $("#preco_out").val("0");
    $("#representacao").val("0");
}