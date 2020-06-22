const cbestado = document.getElementById("cbestado");
const cbcidade = document.getElementById("cbcidade");

var _rs = "";
var _nf = "";
var _cnpj = "";
var lista_estados = [];
var lista_cidades = [];
var erros = 0;

function limparEstados() {
    for (let i = cbestado.childElementCount - 1; i > 0; i--) {
        cbestado.children.item(i).remove();
    }
}

function carregarCidades() {
    let form = new FormData();
    form.append("estado", cbestado.value);

    $.ajax({
        type: 'POST',
        url: '/representacoes/cidade/obter-por-estado.php',
        data: form,
        contentType: false,
        processData: false,
        async: false,
        success: function (response) {lista_cidades = response;},
        error: function (xhr, status, thrown) {
            console.error(thrown);
            mostraDialogo(
                "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                "<br/>Um problema no servidor impediu sua comunicação...",
                "danger",
                2000
            );
        }
    });

    limparCidades();
    if (lista_cidades !== "") {
        for (var i = 0; i < lista_cidades.length; i++) {
            var option = document.createElement("option");
            option.value = lista_cidades[i].id;
            option.text = lista_cidades[i].nome;
            cbcidade.appendChild(option);
        }
    }
}

function onCbEstadoChange() {
    if (cbestado.value === "0") {
        limparCidades();
        cbcidade.disabled = true;
    } else {
        carregarCidades();
        cbcidade.disabled = false;
    }
}

function limparCidades() {
    for (let i = cbcidade.childElementCount - 1; i > 0; i--) {
        cbcidade.children.item(i).remove();
    }
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
        error: function (xhr, status, thrown) {
            console.error(thrown);
            mostraDialogo(
                "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                "<br/>Um problema no servidor impediu sua comunicação...",
                "danger",
                2000
            );
        }
    });
    return res;
}

$(document).ready(function () {
    $("#cnpj").mask('00.000.000/0000-00', {reverse: false});
    $("#cep").mask('00.000-000', {reverse: false});
    $("#tel").mask('(00) 0000-0000', {reverse: false});
    $("#cel").mask('(00) 00000-0000', {reverse: false});

    lista_estados = get('/representacoes/estado/obter.php');
    limparEstados();
    if (lista_estados !== "") {
        for (let i = 0; i < lista_estados.length; i++) {
            let option = document.createElement("option");
            option.value = lista_estados[i].id;
            option.text = lista_estados[i].nome;
            cbestado.appendChild(option);
        }
    }

    let response = get("/representacoes/gerenciar/representacao/addunidade/obter.php");
    if (response != null && response !== "") {
        _rs = response.pessoa.razaoSocial;
        $("#razao_social").val(response.pessoa.razaoSocial);
        _nf = response.pessoa.nomeFantasia;
        $("#nome_fantasia").val(response.pessoa.nomeFantasia);
        _cnpj = response.pessoa.cnpj;
        $("#cnpj").val(response.pessoa.cnpj);
    }
});

function limparCampos() {
    $("#rua").val("");
    $("#numero").val("");
    $("#bairro").val("");
    $("#complemento").val("");
    $("#cep").val("");
    cbestado.value = "0";
    cbcidade.value = "0";
    $("#tel").val("");
    $("#cel").val("");
    $("#email").val("");
}

function validarCNPJ(cnpj) {

    cnpj = cnpj.replace(/[^\d]+/g,'');

    if(cnpj === '') return false;

    if (cnpj.length !== 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj === "00000000000000" ||
        cnpj === "11111111111111" ||
        cnpj === "22222222222222" ||
        cnpj === "33333333333333" ||
        cnpj === "44444444444444" ||
        cnpj === "55555555555555" ||
        cnpj === "66666666666666" ||
        cnpj === "77777777777777" ||
        cnpj === "88888888888888" ||
        cnpj === "99999999999999")
        return false;

    // Valida DVs
    let tamanho = cnpj.length - 2;
    let numeros = cnpj.substring(0,tamanho);
    let digitos = cnpj.substring(tamanho);
    let soma = 0;
    let pos = tamanho - 7;
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado.toString().charAt(0) !== digitos.charAt(0))
        return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;

    return resultado.toString().charAt(0) === digitos.charAt(1);
}

function validacaoEmail(email) {
    let usuario = email.substring(0, email.indexOf("@"));
    let dominio = email.substring(email.indexOf("@")+ 1, email.length);

    return (
        (usuario.length >= 1) &&
        (dominio.length >= 3) &&
        (usuario.search("@") === -1) &&
        (dominio.search("@") === -1) &&
        (usuario.search(" ") === -1) &&
        (dominio.search(" ") === -1) &&
        (dominio.search(".") !== -1) &&
        (dominio.indexOf(".") >= 1) &&
        (dominio.lastIndexOf(".") < dominio.length - 1)
    );
}

function gravar() {
    let razaosocial = _rs;
    let nomefantasia = _nf;
    let cnpj = _cnpj;
    let rua = $("#rua").val();
    let numero = $("#numero").val();
    let bairro = $("#bairro").val();
    let complemento = $("#complemento").val();
    let cep = $("#cep").val();
    let cidade = cbcidade.value;
    let telefone = $("#tel").val();
    let celular = $("#cel").val();
    let email = $("#email").val();

    erros = 0;

    if (rua.length === 0) {
        erros++;
        $("#msrua").html('<span class="label label-danger">A Rua precisa ser preenchida!</span>');
    } else {
        $("#msrua").html('');
    }

    if (numero.length === 0) {
        erros++;
        $("#msnumero").html('<span class="label label-danger">O Número precisa ser preenchido!</span>');
    } else {
        $("#msnumero").html('');
    }

    if (bairro.length === 0) {
        erros++;
        $("#msbairro").html('<span class="label label-danger">O Bairro precisa ser preenchido!</span>');
    } else {
        $("#msbairro").html('');
    }

    if (cep.length === 0) {
        erros++;
        $("#mscep").html('<span class="label label-danger">O CEP precisa ser preenchido!</span>');
    } else
    if (cep.length < 10) {
        erros++;
        $("#mscep").html('<span class="label label-danger">O CEP informado é inválido...</span>');
    } else {
        $("#mscep").html('');
    }

    if (cbestado.value === "0") {
        erros++;
        $("#msestado").html('<span class="label label-danger">O Estado precisa ser selecionado!</span>');
    } else {
        $("#msestado").html('');
    }

    if (cbcidade.value === "0") {
        erros++;
        $("#mscidade").html('<span class="label label-danger">A Cidade precisa ser selecionada!</span>');
    } else {
        $("#mscidade").html('');
    }

    if (telefone.length === 0) {
        erros++;
        $("#mstel").html('<span class="label label-danger">O Telefone precisa ser preenchido!</span>');
    } else
    if (telefone.length < 14) {
        erros++;
        $("#mstel").html('<span class="label label-danger">O Telefone informado possui tamanho inválido...</span>');
    } else {
        $("#mstel").html('');
    }

    if (celular.length === 0) {
        erros++;
        $("#mscel").html('<span class="label label-danger">O Celular precisa ser preenchido!</span>');
    } else
    if (celular.length < 15) {
        erros++;
        $("#mscel").html('<span class="label label-danger">O Celular informado possui tamanho inválido...</span>');
    } else {
        $("#mscel").html('');
    }

    if (email.length === 0) {
        erros++;
        $("#msemail").html('<span class="label label-danger">O Email precisa ser preenchido!</span>');
    } else
    if (validacaoEmail(email) === false) {
        erros++;
        $("#msemail").html('<span class="label label-danger">O Email informado é inválido...</span>');
    } else {
        $("#msemail").html('');
    }

    if (erros === 0) {
        let form = new FormData();
        form.append("razaosocial", razaosocial);
        form.append("nomefantasia", nomefantasia);
        form.append("cnpj", cnpj);
        form.append("rua", rua);
        form.append("numero", numero);
        form.append("bairro", bairro);
        form.append("complemento", complemento);
        form.append("cep", cep);
        form.append("cidade", cidade);
        form.append("telefone", telefone);
        form.append("celular", celular);
        form.append("email", email);

        $.ajax({
            type: 'POST',
            url: '/representacoes/gerenciar/representacao/addunidade/gravar.php',
            data: form,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.length > 0) {
                    mostraDialogo(
                        "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                        "<br/>"+response,
                        "danger",
                        2000
                    );
                } else {
                    mostraDialogo(
                        "<strong>Unidade adicionada com sucesso!</strong>" +
                        "<br />A nova unidade foi salva com sucesso!",
                        "success",
                        2000
                    );
                }
            },
            error: function (xhr, status, thrown) {
                console.error(thrown);
                mostraDialogo(
                    "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                    "<br/>Um problema no servidor impediu sua comunicação...",
                    "danger",
                    2000
                );
            }
        });
    }
}