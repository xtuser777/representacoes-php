const cbestado = document.getElementById("cbestado");
const cbcidade = document.getElementById("cbcidade");
const fisica = document.getElementById("fisica");
const juridica = document.getElementById("juridica");

var _tipo = 0;
var lista_estados = [];
var lista_cidades = [];
var erros = 0;
var cpf_atual = "";
var cnpj_atual = "";
var idendereco = 0;
var idcontato = 0;
var idpessoa = 0;
var idcliente = 0;
var _cadastro = "";

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

    let response = get("/representacoes/gerenciar/cliente/detalhes/obter.php");
    if (response != null && response !== "") {
        let pessoa = (response.tipo === 1) ? response.pessoaFisica : response.pessoaJuridica;
        idendereco = pessoa.contato.endereco.id;
        idcontato = pessoa.contato.id;
        idpessoa = pessoa.id;
        idcliente = response.id;
        _cadastro = response.cadastro;

        _tipo = response.tipo;
        $("#tipo").val(_tipo === 1 ? "PESSOA FÍSICA" : "PESSOA JURÍDICA");

        if (_tipo === 1) {
            $("#nome").val(pessoa.nome);
            $("#nasc").val(FormatarDataIso(pessoa.nascimento));
            $("#rg").val(pessoa.rg);
            $("#cpf").val(pessoa.cpf);
            cpf_atual = pessoa.cpf;
        } else {
            $("#razao_social").val(pessoa.razaoSocial);
            $("#nome_fantasia").val(pessoa.nomeFantasia);
            $("#cnpj").val(pessoa.cnpj);
            cnpj_atual = pessoa.cnpj;
        }
        $("#rua").val(pessoa.contato.endereco.rua);
        $("#numero").val(pessoa.contato.endereco.numero);
        $("#bairro").val(pessoa.contato.endereco.bairro);
        $("#complemento").val(pessoa.contato.endereco.complemento);
        $("#cep").val(pessoa.contato.endereco.cep);
        cbestado.value = pessoa.contato.endereco.cidade.estado.id;
        carregarCidades();
        cbcidade.value = pessoa.contato.endereco.cidade.id;
        $("#tel").val(pessoa.contato.telefone);
        $("#cel").val(pessoa.contato.celular);
        $("#email").val(pessoa.contato.email);
    }

    if (_tipo === 1) {
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

function limparCampos() {
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

function verificarCpf(cpf) {
    $.ajax({
        type: 'POST',
        url: '/representacoes/gerenciar/cliente/detalhes/verificar-cpf.php',
        data: { cpf: cpf },
        async: false,
        success: function (response) {
            if (response === true && cpf !== cpf_atual) {
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
        url: '/representacoes/gerenciar/cliente/detalhes/verificar-cnpj.php',
        data: { cnpj: cnpj },
        async: false,
        success: function (response) {
            if (response === true && cnpj !== cnpj_atual) {
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
    if (
        cpf.length !== 11 || cpf === "00000000000" || cpf === "11111111111" || cpf === "22222222222"
        || cpf === "33333333333" || cpf === "44444444444" || cpf === "55555555555" || cpf === "66666666666"
        || cpf === "77777777777" || cpf === "88888888888" || cpf === "99999999999"
    ) {
        return false;
    }
    // Valida 1o digito
    let add = 0;
    for (let i = 0; i < 9; i++) {
        add += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let rev = 11 - (add % 11);
    if (rev === 10 || rev === 11) {
        rev = 0;
    }
    if (rev !== parseInt(cpf.charAt(9))) {
        return false;
    }
    // Valida 2o digito
    add = 0;
    for (let i = 0; i < 10; i++) {
        add += parseInt(cpf.charAt(i)) * (11 - i);
    }
    rev = 11 - (add % 11);
    if (rev === 10 || rev === 11) {
        rev = 0;
    }

    return rev === parseInt(cpf.charAt(10));
}

function validarCNPJ(cnpj) {

    cnpj = cnpj.replace(/[^\d]+/g,'');

    if(cnpj === '') return false;

    if (cnpj.length !== 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (
        cnpj === "00000000000000" ||
        cnpj === "11111111111111" ||
        cnpj === "22222222222222" ||
        cnpj === "33333333333333" ||
        cnpj === "44444444444444" ||
        cnpj === "55555555555555" ||
        cnpj === "66666666666666" ||
        cnpj === "77777777777777" ||
        cnpj === "88888888888888" ||
        cnpj === "99999999999999"
    )
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
    let nome = $("#nome").val();
    let nasc = $("#nasc").val();
    let rg = $("#rg").val();
    let cpf = $("#cpf").val();
    let razaosocial = $("#razao_social").val();
    let nomefantasia = $("#nome_fantasia").val();
    let cnpj = $("#cnpj").val();
    let tipo = _tipo.toString();
    let rua = $("#rua").val();
    let numero = $("#numero").val();
    let bairro = $("#bairro").val();
    let complemento = $("#complemento").val();
    let cep = $("#cep").val();
    let cidade = cbcidade.value;
    let telefone = $("#tel").val();
    let celular = $("#cel").val();
    let email = $("#email").val();

    let dataNasc = new Date(nasc);

    erros = 0;

    if (_tipo === 1) {
        if (nome.length === 0) {
            erros++;
            $("#msnome").html('<span class="label label-danger">O Nome precisa ser preenchido!</span>');
        } else
        if (nome.length < 3) {
            erros++;
            $("#msnome").html('<span class="label label-danger">O Nome informado é inválido...</span>');
        } else {
            $("#msnome").html('');
        }

        if (nasc.length === 0) {
            erros++;
            $("#msnasc").html('<span class="label label-danger">A data de admissão precisa ser preenchida!</span>');
        } else
        if (dataNasc >= Date.now()) {
            erros++;
            $("#msnasc").html('<span class="label label-danger">A data de admissão informada é inválida...</span>');
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
        $("#mscidade").html('<span class="label label-danger">A Cidade precisa ser selecionada!</span>')
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
        form.append("endereco", idendereco);
        form.append("contato", idcontato);
        form.append("pessoa", idpessoa);
        form.append("cliente", idcliente);
        form.append("cadastro", _cadastro);
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
            url: '/representacoes/gerenciar/cliente/detalhes/alterar.php',
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
                    if (_tipo === 1) {
                        cpf_atual = cpf;
                    } else {
                        cnpj_atual = cnpj;
                    }
                    mostraDialogo(
                        "<strong>Alteração realizada com sucesso!</strong>" +
                        "<br />A alteração feita nos campos do cliente foram salvos com sucesso!",
                        "success",
                        2000
                    );
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