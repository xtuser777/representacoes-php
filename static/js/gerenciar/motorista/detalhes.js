const cbestado = document.getElementById("cbestado");
const cbcidade = document.getElementById("cbcidade");

var lista_estados = [];
var lista_cidades = [];
var erros = 0;
var cpf_atual = "";
var idendereco = 0;
var idcontato = 0;
var idpessoa = 0;
var iddados = 0;
var idmotorista = 0;

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

$(document).ready(function () {
    $("#cpf").mask('000.000.000-00', {reverse: false});
    $("#cnh").mask('00000000000', {reverse: false});
    $("#banco").mask('000-0', { reverse: false });
    $("#agencia").mask('0000-0', { reverse: false });
    $("#conta").mask('0000000000-0', { reverse: true });
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

    let response = get("/representacoes/gerenciar/motorista/detalhes/obter.php");
    if (response != null && response !== "") {
        idendereco = response.pessoa.contato.endereco.id;
        idcontato = response.pessoa.contato.id;
        idpessoa = response.pessoa.id;
        iddados = response.dadosBancarios.id;
        idmotorista = response.id;

        $("#nome").val(response.pessoa.nome);
        $("#nasc").val(FormatarDataIso(response.pessoa.nascimento));
        $("#rg").val(response.pessoa.rg);
        $("#cpf").val(response.pessoa.cpf);
        cpf_atual = response.pessoa.cpf;
        $("#cnh").val(response.cnh);
        $("#banco").val(response.dadosBancarios.banco);
        $("#agencia").val(response.dadosBancarios.agencia);
        $("#conta").val(response.dadosBancarios.conta);
        $("#tipo").val(response.dadosBancarios.tipo);
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
    $("#nome").val("");
    $("#nasc").val("");
    $("#rg").val("");
    $("#cpf").val("");
    $("#cnh").val("");
    $("#banco").val("");
    $("#agencia").val("");
    $("#conta").val("");
    $("#tipo").val("0");
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
        url: '/representacoes/gerenciar/motorista/detalhes/verificar-cpf.php',
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
    let cnh = $("#cnh").val();
    let banco = $("#banco").val();
    let agencia = $("#agencia").val();
    let conta = $("#conta").val();
    let tipo = $("#tipo").val();
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

    if (cnh.length === 0) {
        erros++;
        $("#mscnh").html('<span class="label label-danger">A CNH do motorista precisa ser preenchida!</span>');
    } else {
        $("#mscnh").html('');
    }

    if (banco.length === 0) {
        erros++;
        $("#msbanco").html('<span class="label label-danger">O Banco precisa ser preenchido!</span>');
    } else {
        $("#msbanco").html('');
    }

    if (agencia.length === 0) {
        erros++;
        $("#msagencia").html('<span class="label label-danger">A agência precisa ser preenchida!</span>');
    } else {
        $("#msagencia").html('');
    }

    if (conta.length === 0) {
        erros++;
        $("#msconta").html('<span class="label label-danger">A conta precisa ser preenchida!</span>');
    } else {
        $("#msconta").html('');
    }

    if (tipo === "0") {
        erros++;
        $("#mstipo").html('<span class="label label-danger">O CPF precisa ser preenchido!</span>');
    } else {
        $("#mstipo").html('');
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
        form.append("dados", iddados);
        form.append("motorista", idmotorista);

        form.append("nome", nome);
        form.append("nasc", nasc);
        form.append("rg", rg);
        form.append("cpf", cpf);
        form.append("cnh", cnh);
        form.append("banco", banco);
        form.append("agencia", agencia);
        form.append("conta", conta);
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
            url: '/representacoes/gerenciar/motorista/detalhes/alterar.php',
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
                    cpf_atual = cpf;
                    mostraDialogo(
                        "<strong>Alteração realizada com sucesso!</strong>" +
                        "<br />A alteração feita nos campos do motorista foram salvos com sucesso!",
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