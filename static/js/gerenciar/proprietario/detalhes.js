const selectMotorista = document.getElementById("select_motorista");
const selectTipo = document.getElementById("select_tipo");
const fisica = document.getElementById("fisica");
const juridica = document.getElementById("juridica");
const textNome = document.getElementById("text_nome");
const textRg = document.getElementById("text_rg");
const textCpf = document.getElementById("text_cpf");
const dateNasc = document.getElementById("date_nasc");
const textRazaoSocial = document.getElementById("text_razao_social");
const textNomeFantasia = document.getElementById("text_nome_fantasia");
const textCnpj = document.getElementById("text_cnpj");
const textRua = document.getElementById("text_rua");
const textNumero = document.getElementById("text_numero");
const textBairro = document.getElementById("text_bairro");
const textComplemento = document.getElementById("text_complemento");
const textCep = document.getElementById("text_cep");
const selectCidade = document.getElementById("select_cidade");
const selectEstado = document.getElementById("select_estado");
const textTelefone = document.getElementById("text_tel");
const textCelular = document.getElementById("text_cel");
const textEmail = document.getElementById("text_email");

const msNome = document.getElementById("msnome");
const msRg = document.getElementById("msrg");
const msCpf = document.getElementById("mscpf");
const msNasc = document.getElementById("msnasc");
const msRs = document.getElementById("msrs");
const msNf = document.getElementById("msnf");
const msCnpj = document.getElementById("mscnpj");
const msRua = document.getElementById("msrua");
const msNumero = document.getElementById("msnumero");
const msBairro = document.getElementById("msbairro");
const msCep = document.getElementById("mscep");
const msEstado = document.getElementById("msestado");
const msCidade = document.getElementById("mscidade");
const msTel = document.getElementById("mstel");
const msCel = document.getElementById("mscel");
const msEmail = document.getElementById("msemail");

let _motorista = 0;
let _tipo = 0;
let _pessoa = 0;
let _contato = 0;
let _endereco = 0;

let erroNome = true;
let erroRg = true;
let erroCpf = true;
let erroNasc = true;
let erroRs = true;
let erroNf = true;
let erroCnpj = true;
let erroRua = true;
let erroNumero = true;
let erroBairro = true;
let erroCep = true;
let erroEstado = true;
let erroCidade = true;
let erroTel = true;
let erroCel = true;
let erroEmail = true;

let motoristas = [];

function selectMotoristaChange() {
    let motoId = Number.parseInt($(selectMotorista).val());
    if (motoId !== 0) {
        $(selectTipo).val(1);
        selectTipoChange();
        selectTipo.disabled = true;
        textNome.readOnly = true;
        textRg.readOnly = true;
        textCpf.readOnly = true;
        dateNasc.readOnly = true;
        textRua.readOnly = true;
        textNumero.readOnly = true;
        textBairro.readOnly = true;
        textComplemento.readOnly = true;
        selectEstado.disabled = true;
        selectCidade.disabled = true;
        textCep.readOnly = true;
        textTelefone.readOnly = true;
        textCelular.readOnly = true;
        textEmail.readOnly = true;
        $(textNome).val(motoristas[motoId-1].pessoa.nome);
        $(textRg).val(motoristas[motoId-1].pessoa.rg);
        $(textCpf).val(motoristas[motoId-1].pessoa.cpf);
        $(dateNasc).val(motoristas[motoId-1].pessoa.nascimento);
        $(textRua).val(motoristas[motoId-1].pessoa.contato.endereco.rua);
        $(textNumero).val(motoristas[motoId-1].pessoa.contato.endereco.numero);
        $(textBairro).val(motoristas[motoId-1].pessoa.contato.endereco.bairro);
        $(textComplemento).val(motoristas[motoId-1].pessoa.contato.endereco.complemento);
        $(selectEstado).val(motoristas[motoId-1].pessoa.contato.endereco.cidade.estado.id);
        selectEstadoChange();
        $(selectCidade).val(motoristas[motoId-1].pessoa.contato.endereco.cidade.id);
        $(textCep).val(motoristas[motoId-1].pessoa.contato.endereco.cep);
        $(textTelefone).val(motoristas[motoId-1].pessoa.contato.telefone);
        $(textCelular).val(motoristas[motoId-1].pessoa.contato.celular);
        $(textEmail).val(motoristas[motoId-1].pessoa.contato.email);
    } else {
        selectTipo.disabled = false;
        textNome.readOnly = false;
        textRg.readOnly = false;
        textCpf.readOnly = false;
        dateNasc.readOnly = false;
        textRua.readOnly = false;
        textNumero.readOnly = false;
        textBairro.readOnly = false;
        textComplemento.readOnly = false;
        selectEstado.disabled = false;
        selectCidade.disabled = false;
        textCep.readOnly = false;
        textTelefone.readOnly = false;
        textCelular.readOnly = false;
        textEmail.readOnly = false;
        $(textNome).val("");
        $(textRg).val("");
        $(textCpf).val("");
        $(dateNasc).val("");
        $(textRua).val("");
        $(textNumero).val("");
        $(textBairro).val("");
        $(textComplemento).val("");
        $(selectEstado).val(0);
        selectEstadoChange();
        $(selectCidade).val(0);
        $(textCep).val("");
        $(textTelefone).val("");
        $(textCelular).val("");
        $(textEmail).val("");
    }
}

function selectTipoChange() {
    if ($(selectTipo).val() === "1") {
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

function get(url_i) {
    let res;
    $.ajax({
        type: 'GET',
        url: url_i,
        async: false,
        contentType: 'application/json',
        dataType: 'json',
        success: function (result) {res = result;},
        error: function (XMLHttpRequest, txtStatus, errorThrown) {
            console.error(errorThrown);
        }
    });

    return res;
}

function textNomeBlur() {
    if (Number.parseInt($(selectTipo).val()) === 1) {
        let nome = $(textNome).val();
        if (nome.trim().length === 0) {
            erroNome = true;
            $(msNome).html('<span class="label label-danger">O nome do proprietário precisa ser preenchido.</span>');
        } else {
            if (nome.trim().length <= 3) {
                erroNome = true;
                $(msNome).html('<span class="label label-danger">O nome do proprietário possui tamanho inválido.</span>');
            } else {
                erroNome = false;
                $(msNome).html('');
            }
        }
    }
}

function textRgBlur() {
    if (Number.parseInt($(selectTipo).val()) === 1) {
        let rg = $(textRg).val();
        if (rg.trim().length === 0) {
            erroRg = true;
            $(msRg).html('<span class="label label-danger">O RG do proprietário precisa ser preenchido.</span>');
        } else {
            if (rg.trim().length < 9) {
                erroRg = true;
                $(msRg).html('<span class="label label-danger">O RG do proprietário possui tamanho inválido.</span>');
            } else {
                erroRg = false;
                $(msRg).html('');
            }
        }
    }
}

async function validarCpf(cpf) {
    let valid = false;
    await $.ajax({
        type: "POST",
        url: "/representacoes/gerenciar/proprietario/novo/validar-cpf.php",
        data: { cpf: cpf },
        success: function (response) {
            valid = response;
        },
        error: function (XMLHttpRequest, txtStatus, errorThrown) {
            console.error(errorThrown);
        }
    });

    return valid;
}

async function textCpfBlur() {
    if (Number.parseInt($(selectTipo).val()) === 1) {
        let cpf = $(textCpf).val();
        if (cpf.trim().length === 0) {
            erroCpf = true;
            $(msCpf).html('<span class="label label-danger">O CPF do proprietário precisa ser preenchido.</span>');
        } else {
            if (!await validarCpf(cpf)) {
                erroCpf = true;
                $(msCpf).html('<span class="label label-danger">O CPF do proprietário preenchido é inválido.</span>');
            } else {
                erroCpf = false;
                $(msCpf).html('');
            }
        }
    }
}

function dateNascBlur() {
    if (Number.parseInt($(selectTipo).val()) === 1) {
        let nasc = $(dateNasc).val();
        let data = new Date(nasc+" 23:55:00");
        if (nasc.length === 0) {
            erroNasc = true;
            $(msNasc).html('<span class="label label-danger">A data de nascimento do proprietário precisa ser preenchida.</span>');
        } else {
            let now = new Date();
            if (data >= now) {
                erroNasc = true;
                $(msNasc).html('<span class="label label-danger">A data de nascimento do proprietário é inválida.</span>');
            } else {
                erroNasc = false;
                $(msNasc).html('');
            }
        }
    }
}

function textRazaoSocialBlur() {
    if (Number.parseInt($(selectTipo).val()) === 2) {
        let rs = $(textRazaoSocial).val();
        if (rs.trim().length === 0) {
            erroRs = true;
            $(msRs).html('<span class="label label-danger">A razão social do proprietário precisa ser preenchida.</span>');
        } else {
            if (rs.trim().length < 5) {
                erroRs = true;
                $(msRs).html('<span class="label label-danger">A razão social do proprietário possui tamanho inválido.</span>');
            } else {
                erroRs = false;
                $(msRs).html('');
            }
        }
    }
}

function textNomeFantasiaBlur() {
    if (Number.parseInt($(selectTipo).val()) === 2) {
        let nf = $(textNomeFantasia).val();
        if (nf.trim().length === 0) {
            erroNf = true;
            $(msNf).html('<span class="label label-danger">O nome fantasia precisa ser preenchido.</span>');
        } else {
            if (nf.trim().length < 5) {
                erroNf = true;
                $(msNf).html('<span class="label label-danger">O nome fantasia preenchido possui tamanho inválido.</span>');
            } else {
                erroNf = false;
                $(msNf).html('');
            }
        }
    }
}

async function validarCnpj(cnpj) {
    let valid = false;
    await $.ajax({
        type: "POST",
        url: "/representacoes/gerenciar/proprietario/novo/validar-cnpj.php",
        data: { cnpj: cnpj },
        success: function (response) {
            valid = response;
        },
        error: function (XMLHttpRequest, txtStatus, errorThrown) {
            console.error(errorThrown);
        }
    });

    return valid;
}

async function textCnpjBlur() {
    if (Number.parseInt($(selectTipo).val()) === 2) {
        let cnpj = $(textCnpj).val();
        if (cnpj.trim().length === 0) {
            erroCnpj = true;
            $(msCnpj).html('<span class="label label-danger">O CNPJ do proprietário precisa ser preenchido.</span>');
        } else {
            if (!await validarCnpj(cnpj)) {
                erroCnpj = true;
                $(msCnpj).html('<span class="label label-danger">O CNPJ preenchido é inválido.</span>');
            } else {
                erroCnpj = false;
                $(msCnpj).html('');
            }
        }
    }
}

function textRuaBlur() {
    let rua = $(textRua).val();
    if (rua.trim().length === 0) {
        erroRua = true;
        $(msRua).html('<span class="label label-danger">A rua precisa ser preenchida.</span>');
    } else {
        erroRua = false;
        $(msRua).html('');
    }
}

function textNumeroBlur() {
    let numero = $(textNumero).val();
    if (numero.trim().length === 0) {
        erroNumero = true;
        $(msNumero).html('<span class="label label-danger">O Número precisa ser preenchido.</span>');
    } else {
        erroNumero = false;
        $(msNumero).html('');
    }
}

function textBairroBlur() {
    let bairro = $(textBairro).val();
    if (bairro.trim().length === 0) {
        erroBairro = true;
        $(msBairro).html('<span class="label label-danger">O Bairro precisa ser preenchido.</span>');
    } else {
        erroBairro = false;
        $(msBairro).html('');
    }
}

function selectEstadoBlur() {
    let estado = Number.parseInt($(selectEstado).val());
    if (estado === 0) {
        erroEstado = true;
        $(msEstado).html('<span class="label label-danger">O Estado precisa ser preenchido.</span>');
    } else {
        erroEstado = false;
        $(msEstado).html('');
    }
}

function selectCidadeBlur() {
    let cidade = Number.parseInt($(selectCidade).val());
    if (cidade === 0) {
        erroCidade = true;
        $(msCidade).html('<span class="label label-danger">A Cidade precisa ser selecionada.</span>');
    } else {
        erroCidade = false;
        $(msCidade).html('');
    }
}

function textCepBlur() {
    let cep = $(textCep).val();
    if (cep.trim().length === 0) {
        erroCep = true;
        $(msCep).html('<span class="label label-danger">O CEP precisa ser preechido.</span>');
    } else {
        if (cep.trim().length < 10) {
            erroCep = true;
            $(msCep).html('<span class="label label-danger">O CEP precisa preechido possui tamanho inválido.</span>');
        } else {
            erroCep = false;
            $(msCep).html('');
        }
    }
}

function textTelefoneBlur() {
    let telefone = $(textTelefone).val();
    if (telefone.trim().length === 0) {
        erroTel = true;
        $(msTel).html('<span class="label label-danger">O Telefone do proprietário precisa ser preenchido.</span>');
    } else {
        if (telefone.trim().length < 14) {
            erroTel = true;
            $(msTel).html('<span class="label label-danger">O Telefone preenchido possui tamanho inválido.</span>');
        } else {
            erroTel = false;
            $(msTel).html('');
        }
    }
}

function textCelularBlur() {
    let celular = $(textCelular).val();
    if (celular.trim().length === 0) {
        erroCel = true;
        $(msCel).html('<span class="label label-danger">O Celular do proprietário precisa ser prenchido.</span>');
    } else {
        if (celular.trim().length < 15) {
            erroCel = true;
            $(msCel).html('<span class="label label-danger">O Celular preenchido possui tamanho inválido.</span>');
        } else {
            erroCel = false;
            $(msCel).html('');
        }
    }
}

async function validarEmail(email) {
    let valid = false;
    await $.ajax({
        type: "POST",
        url: "/representacoes/gerenciar/proprietario/novo/validar-email.php",
        data: { email: email },
        success: function (response) { valid = response; },
        error: function (XMLHttpRequest, txtStatus, errorThrown) { console.error(errorThrown); }
    });

    return valid;
}

async function textEmailBlur() {
    let email = $(textEmail).val();
    if (email.trim().length === 0) {
        erroEmail = true;
        $(msEmail).html('<span class="label label-danger">O E-mail do proprietário precisa ser preenchido.</span>');
    } else {
        if (!await validarEmail(email)) {
            erroEmail = true;
            $(msEmail).html('<span class="label label-danger">O E-mail preenchido é inválido.</span>');
        } else {
            erroEmail = false;
            $(msEmail).html('');
        }
    }
}

function limparEstados() {
    for (let i = selectEstado.childElementCount - 1; i > 0; i--) {
        selectEstado.children.item(i).remove();
    }
}

function carregarCidades() {
    let form = new FormData();
    form.append("estado", selectEstado.value);
    let cidades = [];

    $.ajax({
        type: 'POST',
        url: '/representacoes/cidade/obter-por-estado.php',
        data: form,
        contentType: false,
        processData: false,
        async: false,
        success: function (response) {cidades = response;},
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
    if (cidades !== "") {
        for (let i = 0; i < cidades.length; i++) {
            let option = document.createElement("option");
            option.value = cidades[i].id;
            option.text = cidades[i].nome;
            selectCidade.appendChild(option);
        }
    }
}

function selectEstadoChange() {
    if (selectEstado.value === "0") {
        limparCidades();
        //selectCidade.disabled = true;
    } else {
        carregarCidades();
        //selectCidade.disabled = false;
    }
}

function limparCidades() {
    for (let i = selectCidade.childElementCount - 1; i > 0; i--) {
        selectCidade.children.item(i).remove();
    }
}

function limpar() {
    $(selectMotorista).val(0);
    selectTipo.disabled = false;
    $(selectTipo).val(1);
    selectTipoChange();

    textNome.disabled = false;
    erroNome = true;
    $(msNome).html('');
    $(textNome).val("");

    dateNasc.disabled = false;
    erroNasc = true;
    $(msNasc).html('');
    $(dateNasc).val("");

    textRg.disabled = false;
    erroRg = true;
    $(msRg).html('');
    $(textRg).val("");

    textCpf.disabled = false;
    erroCpf = true;
    $(msCpf).html('');
    $(textCpf).val("");

    erroRs = true;
    $(msRs).html('');
    $(textRazaoSocial).val("");

    erroNf = true;
    $(msNf).html('');
    $(textNomeFantasia).val("");

    erroCnpj = true;
    $(msCnpj).html('');
    $(textCnpj).val("");

    textRua.disabled = false;
    erroRua = true;
    $(msRua).html('');
    $(textRua).val("");

    textNumero.disabled = false;
    erroNumero = true;
    $(msNumero).html('');
    $(textNumero).val("");

    textBairro.disabled = false;
    erroBairro = false;
    $(msBairro).html('');
    $(textBairro).val("");

    textComplemento.disabled = false;
    $(textComplemento).val("");

    textCep.disabled = false;
    erroCep = true;
    $(msCep).html('');
    $(textCep).val("");

    selectEstado.disabled = false;
    erroEstado = true;
    $(msEstado).html('');
    selectEstado.value = "0";
    selectEstadoChange();

    selectCidade.disabled = false;
    erroCidade = true;
    $(msCidade).html('');
    selectCidade.value = "0";

    textTelefone.disabled = false;
    erroTel = true;
    $(msTel).html('');
    $(textTelefone).val("");

    textCelular.disabled = false;
    erroCel = true;
    $(msCel).html('');
    $(textCelular).val("");

    textEmail.disabled = false;
    erroEmail = true;
    $(msEmail).html('');
    $(textEmail).val("");
}

async function validar() {
    if (Number.parseInt($(selectTipo).val()) === 1) {
        textNomeBlur();
        textRgBlur();
        await textCpfBlur();
        dateNascBlur();
        textRuaBlur();
        textNumeroBlur();
        textBairroBlur();
        selectEstadoBlur();
        selectCidadeBlur();
        textCepBlur();
        textTelefoneBlur();
        textCelularBlur();
        await textEmailBlur();

        return (
            !erroNome && !erroRg && !erroCpf && !erroNasc
            && !erroRua && !erroNumero && !erroBairro && !erroEstado && !erroCidade && !erroCep
            && !erroTel && !erroCel && !erroEmail
        );
    } else {
        textRazaoSocialBlur();
        textNomeFantasiaBlur();
        await textCnpjBlur();
        textRuaBlur();
        textNumeroBlur();
        textBairroBlur();
        selectEstadoBlur();
        selectCidadeBlur();
        textCepBlur();
        textTelefoneBlur();
        textCelularBlur();
        await textEmailBlur();

        return (
            !erroRs && !erroNf && !erroCnpj
            && !erroRua && !erroNumero && !erroBairro && !erroEstado && !erroCidade && !erroCep
            && !erroTel && !erroCel && !erroEmail
        );
    }
}

async function alterar() {
    let motorista = 0;
    let tipo = 0;
    let nome = "";
    let rg = "";
    let cpf = "";
    let nasc = "";
    let razaoSocial = "";
    let nomeFantasia = "";
    let cnpj = "";
    let rua = "";
    let numero = "";
    let bairro = "";
    let complemento = "";
    let cep = "";
    let estado = 0;
    let cidade = 0;
    let telefone = "";
    let celular = "";
    let email = "";

    if (await validar()) {
        motorista = Number.parseInt($(selectMotorista).val());
        tipo = _tipo;
        if (tipo === 1) {
            nome = $(textNome).val();
            rg = $(textRg).val();
            cpf = $(textCpf).val();
            nasc = $(dateNasc).val();
        } else {
            razaoSocial = $(textRazaoSocial).val();
            nomeFantasia = $(textNomeFantasia).val();
            cnpj = $(textCnpj).val();
        }
        rua = $(textRua).val();
        numero = $(textNumero).val();
        bairro = $(textBairro).val();
        complemento = $(textComplemento).val();
        estado = Number.parseInt($(selectEstado).val());
        cidade = Number.parseInt($(selectCidade).val());
        cep = $(textCep).val();
        telefone = $(textTelefone).val();
        celular = $(textCelular).val();
        email = $(textEmail).val();

        let frm = new FormData();
        frm.append("motorista", motorista);
        frm.append("tipo", tipo);
        frm.append("prop", _prop);
        frm.append("pessoa", _pessoa);
        frm.append("contato", _contato);
        frm.append("endereco", _endereco);
        frm.append("nome", nome);
        frm.append("rg", rg);
        frm.append("cpf", cpf);
        frm.append("nasc", nasc);
        frm.append("razaosocial", razaoSocial);
        frm.append("nomefantasia", nomeFantasia);
        frm.append("cnpj", cnpj);
        frm.append("rua", rua);
        frm.append("numero", numero);
        frm.append("bairro", bairro);
        frm.append("complemento", complemento);
        frm.append("cidade", cidade);
        frm.append("cep", cep);
        frm.append("telefone", telefone);
        frm.append("celular", celular);
        frm.append("email", email);

        await $.ajax({
            type: "POST",
            url: "/representacoes/gerenciar/proprietario/detalhes/alterar.php",
            data: frm,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.length > 0) {
                    mostraDialogo(
                        "<strong>Ocorreu um problema ao executar a operação...</strong>" +
                        "<br/>"+response,
                        "danger",
                        2000
                    );
                } else {
                    mostraDialogo(
                        "<strong>Proprietário alterado com sucesso!</strong>" +
                        "<br/>O proprietário foi alterado com sucesso no sistema...",
                        "success",
                        2000
                    );
                }
            },
            error: function (XMLHttpRequest, txtStatus, errorThrown) {
                console.error(errorThrown);
                mostraDialogo(
                    "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                    "<br/>Um problema no servidor impediu sua comunicação...",
                    "danger",
                    2000
                );
            }
        });
    } else {
        mostraDialogo(
            "Por favor, preencha os campos obrigatórios primeiro.",
            "info",
            3000
        );
    }
}

$(document).ready((event) => {
    $(textCpf).mask('000.000.000-00', {reverse: false});
    $(textCnpj).mask('00.000.000/0000-00', {reverse: false});
    $(textCep).mask('00.000-000', {reverse: false});
    $(textTelefone).mask('(00) 0000-0000', {reverse: false});
    $(textCelular).mask('(00) 00000-0000', {reverse: false});

    motoristas = get("/representacoes/gerenciar/proprietario/novo/obter-motoristas.php");
    for (let i = 0; i < motoristas.length; i++) {
        let option = document.createElement("option");
        option.value = motoristas[i].id;
        option.text = motoristas[i].pessoa.nome;
        selectMotorista.appendChild(option);
    }

    let estados = get('/representacoes/estado/obter.php');
    limparEstados();
    if (estados !== []) {
        for (let i = 0; i < estados.length; i++) {
            let option = document.createElement("option");
            option.value = estados[i].id;
            option.text = estados[i].nome;
            selectEstado.appendChild(option);
        }
    }

    let prop = get("/representacoes/gerenciar/proprietario/detalhes/obter.php");
    if (prop !== null || prop !== "") {
        _motorista = (prop.motorista !== null) ? prop.motorista.id : 0;
        _tipo = prop.tipo;
        _prop = prop.id;
        let pes = (_tipo === 1) ? prop.pessoaFisica : prop.pessoaJuridica;
        _pessoa = pes.id;
        _contato = pes.contato.id;
        _endereco = pes.contato.endereco.id;

        if (_tipo === 2) {
            selectMotorista.disabled = true;
        }

        $(selectMotorista).val(_motorista);
        selectMotoristaChange();
        $(selectTipo).val(_tipo);
        selectTipoChange();
        if (_tipo === 1) {
            $(textNome).val(pes.nome);
            $(textRg).val(pes.rg);
            $(textCpf).val(pes.cpf);
            $(dateNasc).val(pes.nascimento);
        } else {
            $(textRazaoSocial).val(pes.razaoSocial);
            $(textNomeFantasia).val(pes.nomeFantasia);
            $(textCnpj).val(pes.cnpj);
        }
        $(textRua).val(pes.contato.endereco.rua);
        $(textNumero).val(pes.contato.endereco.numero);
        $(textBairro).val(pes.contato.endereco.bairro);
        $(textComplemento).val(pes.contato.endereco.complemento);
        $(selectEstado).val(pes.contato.endereco.cidade.estado.id);
        selectEstadoChange();
        $(selectCidade).val(pes.contato.endereco.cidade.id);
        $(textCep).val(pes.contato.endereco.cep);
        $(textTelefone).val(pes.contato.telefone);
        $(textCelular).val(pes.contato.celular);
        $(textEmail).val(pes.contato.email);
    }
});