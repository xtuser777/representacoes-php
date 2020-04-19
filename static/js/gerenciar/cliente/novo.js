var cbestado = document.getElementById("cbestado");
var cbcidade = document.getElementById("cbcidade");
var fisica = document.getElementById("fisica");
var juridica = document.getElementById("juridica");

var lista_estados = [];
var lista_cidades = [];
var erros = 0;

function limparEstados() {
    for (var i = cbestado.childElementCount - 1; i > 0; i--) {
        cbestado.children.item(i).remove();
    }
}

function carregarCidades() {
    var form = new FormData();
    form.append("estado", cbestado.value);

    $.ajax({
        type: 'POST',
        url: '/cidade/obter-por-estado.php',
        data: form,
        contentType: false,
        processData: false,
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
        error: function (err) {
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
    $("#cpf").mask('000.000.000-00', {reverse: false});
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

    if ($("#cbtipo").val() === "1") {
        if (!juridica.classList.contains("hidden"))
            juridica.classList.add("hidden");
        if (fisica.classList.contains("hidden"))
            fisica.classList.remove("hidden");
    } else {
        if (juridica.classList.contains("hidden"))
            juridica.classList.remove("hidden");
        if (!fisica.classList.contains("hidden"))
            fisica.classList.add("hidden");
    }
});

function limpar() {
    $("#nome").val("");
    $("#nasc").val("");
    $("#rg").val("");
    $("#cpf").val("");
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
}

function onCbTipoChange() {
    if ($("#cbtipo").val() === "1") {
        if (!juridica.classList.contains("hidden"))
            juridica.classList.add("hidden");
        if (fisica.classList.contains("hidden"))
            fisica.classList.remove("hidden");
    } else {
        if (juridica.classList.contains("hidden"))
            juridica.classList.remove("hidden");
        if (!fisica.classList.contains("hidden"))
            fisica.classList.add("hidden");
    }
}

function verificarCpf(cpf) {
    $.ajax({
        type: 'POST',
        url: '/gerenciar/cliente/novo/verificar-cpf.php',
        data: { cpf: cpf },
        async: false,
        success: function (response) {
            if (response === true) {
                erros++;
                $("#mscpf").html('<span class="label label-danger">O CPF informado já existe no cadastro...</span>');
            } else {
                $("#mscpf").html('');
            }
        },
        error: function () {
            mostraDialogo(
                "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                "<br/>Um problema no servidor impediu sua comunicação...",
                "danger",
                2000
            );
        }
    });
}

function verificarCnpj(cnpj) {
    $.ajax({
        type: 'POST',
        url: '/gerenciar/cliente/novo/verificar-cnpj.php',
        data: { cnpj: cnpj },
        async: false,
        success: function (response) {
            if (response === true) {
                erros++;
                $("#mscnpj").html('<span class="label label-danger">O CNPJ informado já existe no cadastro...</span>')
            } else {
                $("#mscnpj").html('');
            }
        },
        error: function () {
            mostraDialogo(
                "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                "<br/>Um problema no servidor impediu sua comunicação...",
                "danger",
                2000
            );
        }
    });
}

function validarCpf(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf === '') {
        return false;
    }
    // Elimina CPFs invalidos conhecidos
    if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999") {
        return false;
    }
    // Valida 1o digito
    add = 0;
    for (i = 0; i < 9; i++) {
        add += parseInt(cpf.charAt(i)) * (10 - i);
    }
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11) {
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(9))) {
        return false;
    }
    // Valida 2o digito
    add = 0;
    for (i = 0; i < 10; i++) {
        add += parseInt(cpf.charAt(i)) * (11 - i);
    }
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11) {
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(10))) {
        return false;
    }
    return true;
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
    var tipo = $("#cbtipo").val();
    var nome = $("#nome").val();
    var nasc = $("#nasc").val();
    var rg = $("#rg").val();
    var cpf = $("#cpf").val();
    var razaosocial = $("#razao_social").val();
    var nomefantasia = $("#nome_fantasia").val();
    var cnpj = $("#cnpj").val();

    var rua = $("#rua").val();
    var numero = $("#numero").val();
    var bairro = $("#bairro").val();
    var complemento = $("#complemento").val();
    var cep = $("#cep").val();
    let cidade = cbcidade.value;
    var telefone = $("#tel").val();
    var celular = $("#cel").val();
    var email = $("#email").val();

    var dataNasc = new Date(nasc);

    erros = 0;

    if (tipo === "1") {
        if (nome.length === 0) {
            erros++;
            $("#msnome").html('<span class="label label-danger">O Nome precisa ser preenchido!</span>');
        } else {
            if (nome.length < 3) {
                erros++;
                $("#msnome").html('<span class="label label-danger">O Nome informado é inválido...</span>');
            } else {
                $("#msnome").html('');
            }
        }

        if (nasc.length === 0) {
            erros++;
            $("#msnasc").html('<span class="label label-danger">A data de nascimento precisa ser preenchida!</span>');
        } else
        if (dataNasc >= Date.now()) {
            erros++;
            $("#msnasc").html('<span class="label label-danger">A data de nascimento informada é inválida...</span>');
        } else {
            $("#msnasc").html('');
        }

        if (rg.length === 0) {
            erros++;
            $("#msrg").html('<span class="label label-danger">O RG precisa ser preenchido!</span>');
        } else {
            $("#msrg").html('');
        }

        if (cpf.length === 0) {
            erros++;
            $("#mscpf").html('<span class="label label-danger">O CPF precisa ser preenchido!</span>');
        } else
        if (!validarCpf(cpf)) {
            erros++;
            $("#mscpf").html('<span class="label label-danger">O CPF informado é inválido...</span>');
        } else {
            verificarCpf(cpf);
        }
    } else {
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
            verificarCnpj(cnpj);
        }
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
        form.append("nome", nome);
        form.append("nasc", nasc);
        form.append("rg", rg);
        form.append("cpf", cpf);
        form.append("razaosocial", razaosocial);
        form.append("nomefantasia", nomefantasia);
        form.append("cnpj", cnpj);
        form.append("tipo", tipo);
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
            url: '/gerenciar/cliente/novo/gravar.php',
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
                        "<strong>Cliente cadastrado com sucesso!</strong>" +
                        "<br/>O novo cliente foi salvo com sucesso no sistema...",
                        "success",
                        2000
                    );
                    limpar();
                }
            },
            error: function (error) {
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