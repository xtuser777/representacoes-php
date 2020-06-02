const selectCliente = document.getElementById("select_cliente");
const textNomeCli = document.getElementById("text_nome_cli");
const textDocCli = document.getElementById("text_doc_cli");
const textTelCli = document.getElementById("text_tel_cli");
const textCelCli = document.getElementById("text_cel_cli");
const textEmailCli = document.getElementById("text_email_cli");
const textDesc = document.getElementById("text_desc");
const selectVendedor = document.getElementById("select_vendedor");
const selectCidade = document.getElementById("select_cid_dest");
const selectEstado = document.getElementById("select_est_dest");
const tableItens = document.getElementById("table_itens");
const tbodyItens = document.getElementById("tbody_itens");
const textPesoItens = document.getElementById("text_peso_itens");
const textValorItens = document.getElementById("text_valor_itens");
const dateValidade = document.getElementById("date_validade");

var clientes = [];

var itens = [];

var erroNomeCli = true;
var erroDocCli = true;
var erroTelCli = true;
var erroCelCli = true;
var erroEmailcli = true;
var erroDesc = true;
var erroEstado = true;
var erroCidade = true;
var erroValidade = true;

function get(url_i) {
    let res;
    $.ajax({
        type: 'GET',
        url: url_i,
        async: false,
        contentType: 'application/json',
        dataType: 'json',
        success: function (result) {res = result;},
        error: function (err) {alert(err);}
    });

    return res;
}

function selectClienteChange() {
    let cliente = selectCliente.value;
    cliente = Number.parseInt(cliente);
    if (cliente !== 0) {
        textNomeCli.value = (clientes[cliente-1].tipo === 1) ? clientes[cliente-1].pessoaFisica.nome : clientes[cliente-1].pessoaJuridica.nomeFantasia;
        textDocCli.value = (clientes[cliente-1].tipo === 1) ? clientes[cliente-1].pessoaFisica.cpf : clientes[cliente-1].pessoaJuridica.cnpj;
        textTelCli.value = (clientes[cliente-1].tipo === 1) ? clientes[cliente-1].pessoaFisica.contato.telefone : clientes[cliente-1].pessoaJuridica.contato.telefone;
        textCelCli.value = (clientes[cliente-1].tipo === 1) ? clientes[cliente-1].pessoaFisica.contato.celular : clientes[cliente-1].pessoaJuridica.contato.celular;
        textEmailCli.value = (clientes[cliente-1].tipo === 1) ? clientes[cliente-1].pessoaFisica.contato.email : clientes[cliente-1].pessoaJuridica.contato.email;
    } else {
        textNomeCli.value = "";
        textDocCli.value = "";
        textTelCli.value = "";
        textCelCli.value = "";
        textEmailCli.value = "";
    }
}

function textNomeClienteBlur() {
    let nomeCli = textNomeCli.value;
    if (nomeCli.length === 0) {
        erroNomeCli = true;
        $("#msnomecli").html('<span class="label label-danger">O Nome precisa ser preenchido!</span>');
    } else {
        if (nomeCli.length < 3) {
            erroNomeCli = true;
            $("#msnomecli").html('<span class="label label-danger">O Nome informado é inválido...</span>');
        } else {
            erroNomeCli = false;
            $("#msnomecli").html('');
        }
    }
}

function validarCPF(cpf) {
    let resposta = false;
    $.ajax({
        type: "POST",
        url: "/orcamento/venda/novo/validar-cpf.php",
        data: { cpf: cpf },
        async: false,
        success: (response) => { resposta = response; },
        error: (XMLHttpRequest, txtStatus, errorThrown) => {
            alert(txtStatus+" - "+errorThrown);
        }
    });

    return resposta;
}

function validarCNPJ(cnpj) {
    let resposta = false;
    $.ajax({
        type: "POST",
        url: "/orcamento/venda/novo/validar-cnpj.php",
        data: { cnpj: cnpj },
        async: false,
        success: (response) => { resposta = response; },
        error: (XMLHttpRequest, txtStatus, errorThrown) => {
            alert(txtStatus+" - "+errorThrown);
        }
    });

    return resposta;
}

function textDocClienteBlur() {
    let docCli = textDocCli.value.toString();
    if (docCli.trim().length === 0) {
        erroDocCli = true;
        $("#msdoccli").html("<span class='label label-danger'>O CPF ou CNPJ do cliente precisa ser preenchido!</span>");
    } else {
        if (docCli.trim().length === 14 && !validarCPF(docCli)) {
            erroDocCli = true;
            $("#msdoccli").html("<span class='label label-danger'>O CPF ou CNPJ do cliente é inválido...</span>");
        } else {
            if (docCli.trim().length === 18 && !validarCNPJ(docCli)) {
                erroDocCli = true;
                $("#msdoccli").html("<span class='label label-danger'>O CPF ou CNPJ do cliente é inválido...</span>");
            } else {
                erroDocCli = false;
                $("#msdoccli").html("");
            }
        }
    }
}

function textTelCliBlur() {
    let telCli = textTelCli.value.toString();
    if (telCli.trim().length === 0) {
        erroTelCli = true;
        $("#mstelcli").html("<span class='label label-danger'>O Telefone do cliente precisa ser preenchido!</span>");
    } else {
        if (telCli.trim().length < 14 || telCli.trim().length > 14) {
            erroTelCli = true;
            $("#mstelcli").html("<span class='label label-danger'>O Telefone do cliente possui tamanho inválido...</span>");
        } else {
            erroTelCli = false;
            $("#mstelcli").html("");
        }
    }
}

function textCelCliBlur() {
    let celCli = textCelCli.value.toString();
    if (celCli.trim().length === 0) {
        erroCelCli = true;
        $("#mscelcli").html("<span class='label label-danger'>O Celular do cliente precisa ser preenchido!</span>");
    } else {
        if (celCli.trim().length < 15 || celCli.trim().length > 16) {
            erroCelCli = true;
            $("#mscelcli").html("<span class='label label-danger'>O Celular do cliente possui tamanho inválido...</span>");
        } else {
            erroCelCli = false;
            $("#mscelcli").html("");
        }
    }
}

function validarEmail(email) {
    let resposta = false;
    $.ajax({
        type: "POST",
        url: "/orcamento/venda/novo/validar-email.php",
        data: { email: email },
        async: false,
        success: (response) => { resposta = response; },
        error: (XMLHttpRequest, txtStatus, errorThrown) => {
            alert(txtStatus+" - "+errorThrown);
        }
    });

    return resposta;
}

function textEmailCliBlur() {
    let emailCli = textEmailCli.value.toString();
    if (emailCli.trim().length === 0) {
        erroEmailcli = true;
        $("#msemailcli").html("<span class='label label-danger'>O E-mail do cliente precisa ser preenchido!</span>");
    } else {
        if (!validarEmail(emailCli)) {
            erroEmailcli = true;
            $("#msemailcli").html("<span class='label label-danger'>O E-mail do cliente informado é inválido.</span>");
        } else {
            erroEmailcli = false;
            $("#msemailcli").html("");
        }
    }
}

function textDescBlur() {
    let desc = textDesc.value.toString();
    if (desc.trim().length === 0) {
        erroDesc = true;
        $("#msdesc").html("<span class='label label-danger'>A Descrição do orçamento precisa ser preenchida.</span>");
    } else {
        erroDesc = false;
        $("#msdesc").html("");
    }
}

function selectEstadoBlur() {
    let estado = selectEstado.value.toString();
    if (estado === "0") {
        erroEstado = true;
        $("#msest").html("<span class='label label-danger'>O Estado de destino precisa ser preenchido.</span>");
    } else {
        erroEstado = false;
        $("#msest").html("");
    }
}

function selectCidadeBlur() {
    let cidade = selectCidade.value.toString();
    if (cidade === "0") {
        erroCidade = true;
        $("#mscid").html("<span class='label label-danger'>A Cidade de destino precida ser preenchida.</span>");
    } else {
        erroCidade = false;
        $("#mscid").html("");
    }
}

function limparEstados() {
    for (let i = selectEstado.childElementCount - 1; i > 0; i--) {
        selectEstado.children.item(i).remove();
    }
}

function limparCidades() {
    for (let i = selectCidade.childElementCount - 1; i > 0; i--) {
        selectCidade.children.item(i).remove();
    }
}

function carregarCidades() {
    let cidades = [];

    $.ajax({
        type: 'POST',
        url: '/cidade/obter-por-estado.php',
        data: {
            estado: selectEstado.value
        },
        async: false,
        success: function (response) {
            cidades = response;
        },
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
        for (var i = 0; i < cidades.length; i++) {
            var option = document.createElement("option");
            option.value = cidades[i].id;
            option.text = cidades[i].nome;
            selectCidade.appendChild(option);
        }
    }
}

function selectEstadoChange() {
    if (selectEstado.value === "0") {
        limparCidades();
        selectCidade.disabled = true;
    } else {
        carregarCidades();
        selectCidade.disabled = false;
    }
}

function dateValidadeBlur() {
    let validade = dateValidade.value.toString();
    if (validade.trim().length === 0) {
        erroValidade = true;
        $("#msvalid").html("<span class='label label-danger'>A data de vencimento do orçamento precisa ser preenchida.</span>");
    } else {
        let dateVal = new Date(validade);
        if (dateVal < Date.now()) {
            erroValidade = true;
            $("#msvalid").html("<span class='label label-danger'>A data de vencimento é inválida (menor que a atual).</span>");
        } else {
            erroValidade = false;
            $("#msvalid").html("");
        }
    }
}

function buttonClrItensClick() {
    itens = [];
    $(tbodyItens).html('');
    textPesoItens.value = 0.0;
    textValorItens.value = 0.0;
}

function validar() {
    textNomeClienteBlur();
    textDocClienteBlur();
    textTelCliBlur();
    textCelCliBlur();
    textEmailCliBlur();
    textDescBlur();
    selectEstadoBlur();
    selectCidadeBlur();
    dateValidadeBlur();

    return (
        !erroNomeCli && !erroDocCli && !erroTelCli && !erroCelCli && !erroEmailcli
        && !erroDesc && !erroEstado && !erroCidade && !erroValidade
    );
}

function buttonCancelarClick() {
    buttonLimparClick();
    location.href = '../venda';
}

function buttonLimparClick() {
    selectCliente.value = 0;
    textNomeCli.value = "";
    textDocCli.value = "";
    textTelCli.value = "";
    textCelCli.value = "";
    textEmailCli.value = "";
    textDesc.value = "";
    selectVendedor.value = 0;
    selectEstado.value = 0;
    selectCidade.value = 0;
    itens = [];
    $(tbodyItens).html("");
    textPesoItens.value = 0.0;
    textValorItens.value = 0.0;
    dateValidade.value = "";

    erroNomeCli = true;
    erroDocCli = true;
    erroTelCli = true;
    erroCelCli = true;
    erroEmailcli = true;
    erroDesc = true;
    erroEstado = true;
    erroCidade = true;
    erroValidade = true;
}

function buttonSalvarClick() {
    let cli = 0;
    let nc = "";
    let dc = "";
    let tc = "";
    let cc = "";
    let ec = "";
    let desc = "";
    let vdd = 0;
    let est = 0;
    let cid = 0;
    let peso = 0.0;
    let valor = 0.0;
    let venc = "";

    if (validar()) {
        if (itens.length > 0) {
            cli = selectCliente.value;
            nc = textNomeCli.value;
            dc = textDocCli.value;
            tc = textTelCli.value;
            cc = textCelCli.value;
            ec = textEmailCli.value;
            desc = textDesc.value;
            vdd = selectVendedor.value;
            cid = selectCidade.value;
            peso = textPesoItens.value;
            valor = textValorItens.value;
            venc = dateValidade.value;

            let frm = new FormData();
            frm.append("cli", cli);
            frm.append("nc", nc);
            frm.append("dc", dc);
            frm.append("tc", tc);
            frm.append("cc", cc);
            frm.append("ec", ec);
            frm.append("desc", desc);
            frm.append("vdd", vdd);
            frm.append("cid", cid);
            frm.append("peso", peso);
            frm.append("valor", valor);
            frm.append("venc", venc);
            frm.append("itens", JSON.stringify(itens));

            $.ajax({
                type: "POST",
                url: "/orcamento/venda/novo/gravar.php",
                data: frm,
                contentType: false,
                processData: false,
                async: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response === "") {
                        mostraDialogo(
                            "<strong>Orçamento de venda gravado com sucesso!</strong>" +
                            "<br />Os dados do novo orçamento de venda foram salvos com sucesso no banco de dados.",
                            "success",
                            2000
                        );
                        buttonLimparClick();
                    } else {
                        mostraDialogo(
                            "<strong>Problemas ao salvar o novo orçamento...</strong>" +
                            "<br/>"+response,
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
            mostraDialogo(
                "Adicione produtos ao orçamento.",
                "warn",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Preencha os campos obrigatórios.",
            "warn",
            3000
        );
    }
}

$(document).ready((event) => {
    clientes = get("/orcamento/venda/novo/obter-clientes.php");
    if (clientes !== "" || clientes !== [] || clientes !== null) {
        for (let i = 0; i < clientes.length; i++) {
            let option = document.createElement("option");
            option.value = clientes[i].id;
            option.text = (clientes[i].tipo === 1) ? clientes[i].pessoaFisica.nome : clientes[i].pessoaJuridica.nomeFantasia;
            selectCliente.appendChild(option);
        }
    }

    let vendedores = get('/orcamento/venda/novo/obter-vendedores.php');
    if (vendedores !== "" || vendedores !== [] || vendedores !== null) {
        for (let i = 0; i < vendedores.length; i++) {
            let option = document.createElement("option");
            option.value = vendedores[i].id;
            option.text = vendedores[i].pessoa.nome;
            selectVendedor.appendChild(option);
        }
    }

    let estados = get('/estado/obter.php');
    limparEstados();
    if (estados !== "" || estados !== [] || estados !== null) {
        for (let i = 0; i < estados.length; i++) {
            let option = document.createElement("option");
            option.value = estados[i].id;
            option.text = estados[i].nome;
            selectEstado.appendChild(option);
        }
    }

    buttonLimparClick();
});