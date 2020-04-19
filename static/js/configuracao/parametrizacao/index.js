var cbestado = document.getElementById("cbestado");
var cbcidade = document.getElementById("cbcidade");

var erros = 0;
var endereco = 0;
var contato = 0;
var pessoa = 0;
var lista_estados = [];
var lista_cidades = [];
var novo = true;

function limparEstados() {
    for (var i = cbestado.childElementCount - 1; i > 0; i--) {
        cbestado.children.item(i).remove();
    }
}

function carregarCidades() {
    $.ajax({
        type: 'POST',
        url: '/cidade/obter-por-estado.php',
        data: { estado: cbestado.value },
        async: false,
        success: function (response) {lista_cidades = response;},
        error: function (err) {
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
    for (var i = cbcidade.childElementCount - 1; i > 0; i--) {
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
        error: function (err) {alert(err.d);}
    });
    return res;
}

$(document).ready(function () {
    $("#cnpj").mask('00.000.000/0000-00', {reverse: false});
    $("#cep").mask('00.000-000', {reverse: false});
    $("#tel").mask('(00) 0000-0000', {reverse: false});
    $("#cel").mask('(00) 00000-0000', {reverse: false});

    lista_estados = get('/estado/obter.php');
    limparEstados();
    if (lista_estados !== "") {
        for (var i = 0; i < lista_estados.length; i++) {
            var option = document.createElement("option");
            option.value = lista_estados[i].id;
            option.text = lista_estados[i].nome;
            cbestado.appendChild(option);
        }
    }

    var response = get("/configuracao/parametrizacao/obter.php");
    if (response != null && response !== "") {
        endereco = response.pessoa.contato.endereco.id;
        contato = response.pessoa.contato.id;
        pessoa = response.pessoa.id;
        $("#razao_social").val(response.pessoa.razaoSocial);
        $("#nome_fantasia").val(response.pessoa.nomeFantasia);
        $("#cnpj").val(response.pessoa.cnpj);
        $("#rua").val(response.pessoa.contato.endereco.rua);
        $("#numero").val(response.pessoa.contato.endereco.numero);
        $("#bairro").val(response.pessoa.contato.endereco.bairro);
        $("#complemento").val(response.pessoa.contato.endereco.complemento);
        $("#cep").val(response.pessoa.contato.endereco.cep);
        cbestado.value = response.pessoa.contato.endereco.cidade.estado.id;
        carregarCidades();
        cbcidade.value = response.pessoa.contato.endereco.cidade.id;
        $("#tel").val(response.pessoa.contato.telefone);
        $("#cel").val(response.pessoa.contato.celular);
        $("#email").val(response.pessoa.contato.email);

        novo = false;
    }

    cbcidade.disabled = (cbestado.value === "0");
});

function limparCampos() {
    $("#razao_social").val("");
    $("#nome_fantasia").val("");
    $("#cnpj").val("");
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
    $("#logotipo").val("");
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
    tamanho = cnpj.length - 2;
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado.toString().charAt(0) !== digitos.charAt(0))
        return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado.toString().charAt(0) !== digitos.charAt(1))
        return false;

    return true;
}

function validacaoEmail(email) {
    usuario = email.substring(0, email.indexOf("@"));
    dominio = email.substring(email.indexOf("@")+ 1, email.length);
    if (
        (usuario.length >=1) &&
        (dominio.length >=3) &&
        (usuario.search("@")===-1) &&
        (dominio.search("@")===-1) &&
        (usuario.search(" ")===-1) &&
        (dominio.search(" ")===-1) &&
        (dominio.search(".")!==-1) &&
        (dominio.indexOf(".") >=1)&&
        (dominio.lastIndexOf(".") < dominio.length - 1)
    ) {
        return true;
    } else {
        return false;
    }
}

function gravar() {
    var razaosocial = $("#razao_social").val();
    var nomefantasia = $("#nome_fantasia").val();
    var cnpj = $("#cnpj").val();
    var rua = $("#rua").val();
    var numero = $("#numero").val();
    var bairro = $("#bairro").val();
    var complemento = $("#complemento").val();
    var cep = $("#cep").val();
    var telefone = $("#tel").val();
    var celular = $("#cel").val();
    var email = $("#email").val();

    var logotipo = document.getElementById("logotipo");

    erros = 0;

    if (razaosocial.length === 0) {
        erros++;
        $("#msrs").html('<span class="label label-danger">A Razão Social precisa ser preenchida!</span>');
    } else
    if (razaosocial.length < 3) {
        erros++;
        $("#msrs").html('<span class="label label-danger">A Razão Social informada é inválida...</span>');
    } else {
        $("#msrs").html('');
    }

    if (nomefantasia.length === 0) {
        erros++;
        $("#msnf").html('<span class="label label-danger">O Nome Fantasia precisa ser preenchido!</span>');
    } else {
        $("#msnf").html('');
    }

    if (cnpj.length === 0) {
        erros++;
        $("#mscnpj").html('<span class="label label-danger">O CNPJ precisa ser preenchido!</span>');
    } else
    if (!validarCNPJ(cnpj)) {
        erros++;
        $("#mscnpj").html('<span class="label label-danger">O CNPJ informado é inválido...</span>');
    } else {
        $("#mscnpj").html('');
    }

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
        var form = new FormData();
        form.append("endereco", endereco);
        form.append("contato", contato);
        form.append("pessoa", pessoa);
        form.append("razaosocial", razaosocial);
        form.append("nomefantasia", nomefantasia);
        form.append("cnpj", cnpj);
        form.append("rua", rua);
        form.append("numero", numero);
        form.append("bairro", bairro);
        form.append("complemento", complemento);
        form.append("cep", cep);
        form.append("cidade", cbcidade.value);
        form.append("telefone", telefone);
        form.append("celular", celular);
        form.append("email", email);

        if (logotipo.files.length > 0 && logotipo.files[0].size > 0) {
            form.append("logotipo", logotipo.files[0]);
        }

        if (novo === true) {
            $.ajax({
                type: 'POST',
                url: '/configuracao/parametrizacao/gravar.php',
                data: form,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.length > 0) {
                        mostraDialogo(
                            "<strong>Ocorreu um problema durante o salvamento.</strong>\
                            <br/>"+response,
                            "danger",
                            2000
                        );
                    } else {
                        mostraDialogo(
                            "<strong>Parametrização salva com sucesso!</strong>",
                            "success",
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
            $.ajax({
                type: 'POST',
                url: '/configuracao/parametrizacao/alterar.php',
                data: form,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.length > 0) {
                        mostraDialogo(
                            "<strong>Ocorreu um problema durante o salvamento.</strong>\
                            <br/>"+response,
                            "danger",
                            2000
                        );
                    } else {
                        mostraDialogo(
                            "<strong>Alteração salva com sucesso!</strong>",
                            "success",
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
}