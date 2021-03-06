const cbestado = document.getElementById("cbestado");
const cbcidade = document.getElementById("cbcidade");

var lista_estados = [];
var lista_cidades = [];
var erros = 0;
var cnpj_atual = "";
var idendereco = 0;
var idcontato = 0;
var idpessoa = 0;
var idrepresentacao = 0;

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
        for (let i = 0; i < lista_cidades.length; i++) {
            let option = document.createElement("option");
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

    let response = get("/representacoes/gerenciar/representacao/detalhes/obter.php");
    if (response != null && response !== "") {
        idendereco = response.pessoa.contato.endereco.id;
        idcontato = response.pessoa.contato.id;
        idpessoa = response.pessoa.id;
        idrepresentacao = response.id;

        $("#razao_social").val(response.pessoa.razaoSocial);
        $("#nome_fantasia").val(response.pessoa.nomeFantasia);
        $("#cnpj").val(response.pessoa.cnpj);
        cnpj_atual = response.pessoa.cnpj;
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
    }
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
}

function verificarCnpj(cnpj) {
    $.ajax({
        type: 'POST',
        url: '/representacoes/gerenciar/representacao/detalhes/verificar-cnpj.php',
        data: { cnpj: cnpj },
        async: false,
        success: function (response) {
            if (response === true && cnpj !== cnpj_atual) {
                erros++;
                $("#mscnpj").html('<span class="label label-danger">O CNPJ informado já existe no cadastro...</span>');
            } else {
                $("#mscnpj").html('');
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
    let razaosocial = $("#razao_social").val();
    let nomefantasia = $("#nome_fantasia").val();
    let cnpj = $("#cnpj").val();
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
        form.append("endereco", idendereco);
        form.append("contato", idcontato);
        form.append("pessoa", idpessoa);
        form.append("representacao", idrepresentacao);
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
            url: '/representacoes/gerenciar/representacao/detalhes/alterar.php',
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
                    cnpj_atual = cnpj;
                    mostraDialogo(
                        "<strong>Alteração realizada com sucesso!</strong>" +
                        "<br />A alteração feita nos campos da representação foram salvos com sucesso!",
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