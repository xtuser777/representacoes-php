const textFiltroProd = document.getElementById("text_filtro_prod");
const tbodyProduto = document.getElementById("tbody_produtos");
const textQtde = document.getElementById("text_qtde_prod");
const textProdSel = document.getElementById("text_prod_sel");
const textPesoTotal = document.getElementById("text_peso_total");

var selecionado = {
    id: 0,
    descricao: "",
    peso: 0.0,
    estado: 0,
    representacao: ""
};

function preencheTabelaItens(dados) {
    let txt = "";
    $.each(dados, function () {
        let pesoFormat = this.produto.peso.toString();
        pesoFormat = pesoFormat.replace('.', '#');
        if (pesoFormat.search('#') === -1) pesoFormat += ',0';
        else pesoFormat = pesoFormat.replace('#', ',');
        txt +=
            '<tr>\
                <td>' + this.produto.descricao + '</td>\
                <td>' + this.produto.representacao + '</td>\
                <td>' + pesoFormat + '</td>\
                <td>' + this.quantidade + '</td>\
                <td>' + this.peso + '</td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluirItem(' + this.produto.id +')"></a></td>\
            </tr>';
    });
    $(tbodyItens).html(txt);
}

function preencheTabelaProd(dados) {
    let txt = "";
    $.each(dados, function () {
        let pesoFormat = this.peso.toString();
        pesoFormat = pesoFormat.replace('.', '#');
        if (pesoFormat.search('#') === -1) pesoFormat += ',0';
        else pesoFormat = pesoFormat.replace('#', ',');
        txt +=
            '<tr>\
                <td>' + this.descricao + '</td>\
                <td>' + this.medida + '</td>\
                <td>' + this.representacao.pessoa.nomeFantasia + '</td>\
                <td>' + pesoFormat + '</td>\
                <td><a role="button" class="glyphicon glyphicon-ok-circle" data-toggle="tooltip" data-placement="top" title="SELECIONAR" href="javascript:selecionar(' + this.id + ',\''+ this.descricao +'\','+ this.peso +','+ this.representacao.pessoa.contato.endereco.cidade.estado.id +',\''+ this.representacao.pessoa.nomeFantasia +'\');"></a></td>\
            </tr>';
    });
    $(tbodyProduto).html(txt);
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
            alert(thrown);
        }
    });

    return res;
}

function buttonFiltrarProdClick() {
    let filtro = textFiltroProd.value.toString();
    if (filtro.trim().length === 0) {
        obterProdutos();
    } else {
        $.ajax({
            type: "POST",
            url: "/representacoes/orcamento/frete/detalhes/item/obter-por-representacao-filtro.php",
            data: { representacao: Number.parseInt(selectRepresentacao.value), filtro: filtro },
            async: false,
            success: (response) => { preencheTabelaProd(response); },
            error: (XMLHttpRequest, txtStatus, thrown) => { alert("Código: "+txtStatus+"\n Erro: "+thrown); }
        });
    }
}

function selecionar(id,desc,peso,est,rep) {
    if (itens.findIndex((element) => { return (element.produto.id === id); }) >= 0) {
        alert("Este produto já foi adicionado ao orçamento.");
    } else {
        selecionado.id = id;
        selecionado.descricao = desc;
        selecionado.peso = peso;
        selecionado.estado = est;
        selecionado.representacao = rep;

        textProdSel.value = desc;
    }
}

function abrirAdicionarItem() {
    if (Number.parseInt(selectRepresentacao.value) === 0) {
        mostraDialogo(
            "Selecione uma representação primeiro.",
            "warning",
            3000
        );
    } else {
        $.fancybox.open({ src : '#fbFrmAddProduto', type : 'inline' });
        obterProdutos(Number.parseInt(selectRepresentacao.value));
    }
}

function cancelarAdicao() {
    selecionado.id = 0;
    selecionado.descricao = "";
    selecionado.peso = 0.0;
    selecionado.estado = 0;
    selecionado.representacao = "";

    textQtde.value = 0;
    textProdSel.value = "";

    erroQtde = true;
    $("#msqtdeprod").html('');

    erroProd = true;
    $("#msprodsel").html('');

    $.fancybox.close();
}

function excluirItem(id) {
    if (Number.parseInt(selectOrcVenda.value) !== 0) {
        mostraDialogo(
            "Não é possível remover este item.",
            "warning",
            3000
        );
    } else {
        let temp = [];
        let x = 0;
        for (let i = 0; i < itens.length; i++) {
            if (itens[i].produto.id !== id) {
                temp.push(itens[i]);
            } else {
                x = i;
            }
        }
        let pesoFormat = textPesoItens.value;
        let peso = Number.parseFloat(pesoFormat.replace(',', '.'));
        peso -= itens[x].peso;
        pesoFormat = peso.toString().replace('.', ',');

        textPesoItens.value = pesoFormat;
        itens = temp;

        if (temp.length === 0) {
            tipos = [];
            limparSelectTipo();
        }

        preencheTabelaItens(itens);
    }
}

function calcularPesoTotal() {
    let qtdeText = textQtde.value.toString();

    if (textProdSel.value === null || textProdSel.value === "") {
        $("#msprodsel").html('<span class="label label-danger">Selecione um produto.</span>');
    } else {
        $("#msprodsel").html('');

        if (qtdeText.search("e") > -1) {
            $("#msqtdeprod").html('<span class="label label-danger">Informe uma quantidade válida.</span>');
        } else {
            $("#msqtdeprod").html('');

            let qtde = Number.parseInt(qtdeText);
            if (qtde === 0 || isNaN(qtde)) {
                $("#msqtdeprod").html('<span class="label label-danger">Informe uma quantidade válida.</span>');
            } else {
                $("#msqtdeprod").html('');

                let total = selecionado.peso * qtde;
                //total = truncate(total);
                let totalText = total.toString();
                totalText = totalText.replace(".", "#");
                /*if (totalText.split("#")[1].length === 1) {
                    totalText = totalText.concat("0");
                }*/
                totalText = totalText.replace("#", ",");

                textPesoTotal.value = totalText;
            }
        }
    }
}

async function adicionarItem() {
    let erroQtde = true;
    let erroProd = true;
    let erroTipos = true;
    let qtde = Number.parseInt(textQtde.value);
    let prod = textProdSel.value;

    if (qtde === 0 || isNaN(qtde)) {
        erroQtde = true;
        $("#msqtdeprod").html('<span class="label label-danger">A quantidade precisa ser preenchida.</span>');
    } else {
        if (qtde < 0) {
            erroQtde = true;
            $("#msqtdeprod").html('<span class="label label-danger">A quantidade precisa ser maior que 0.</span>');
        } else {
            erroQtde = false;
            $("#msqtdeprod").html('');
        }
    }

    if (prod.toString().trim().length === 0) {
        erroProd = true;
        $("#msprodsel").html('<span class="label label-danger">Um produto precisa ser selecionado.</span>');
    } else {
        erroProd = false;
        $("#msprodsel").html('');
    }

    if (!erroQtde && !erroProd) {
        let peso = 0.0;

        let request = new XMLHttpRequest();
        request.open('POST', '/representacoes/orcamento/frete/detalhes/item/obter-tipos-por-item.php', false);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(encodeURI('item='+selecionado.id));

        if (request.DONE === 4 && request.status === 200) {
            let res = JSON.parse(request.responseText);
            if (res !== null && typeof res !== "string" && res.length !== 0) {
                let tmp = [];

                if (tipos.length === 0) {
                    tmp = res;
                    for (let i = 0; i < res.length; i++) {
                        let option = document.createElement("option");
                        option.value = res[i].id;
                        option.text = res[i].descricao;
                        selectTipoCam.appendChild(option);
                    }
                } else {
                    for (let i = 0; i < res.length; i++) {
                        if (tipos.findIndex((element) => { return (element.id === res[i].id); }) !== -1) {
                            tmp.push(res[i]);
                        }
                    }
                }

                if (tmp.length === 0) {
                    //erroTipos = true;
                    alert("Este item não poderá ser entregue no mesmo frete por falta de compatibilidade de tipo de caminhão.");
                } else {
                    //erroTipos = false;
                    tipos = tmp;
                    limparSelectTipo();
                    for (let i = 0; i < tmp.length; i++) {
                        let option = document.createElement("option");
                        option.value = tmp[i].id;
                        option.text = tmp[i].descricao;
                        selectTipoCam.appendChild(option);
                    }

                    peso = Number.parseFloat(textPesoTotal.value.replace(",", "."));
                    itens.push({
                        orcamento: 0,
                        produto: {
                            id: selecionado.id,
                            descricao: selecionado.descricao,
                            peso: selecionado.peso,
                            estado: selecionado.estado,
                            representacao: selecionado.representacao
                        },
                        quantidade: Number(qtde),
                        peso: peso
                    });

                    preencheTabelaItens(itens);

                    let pesoItens = 0.0;
                    for(let i = 0; i < itens.length; i++) {
                        pesoItens += itens[i].peso;
                    }

                    let pesoFormat = pesoItens.toString();
                    pesoFormat = pesoFormat.replace('.', ',');

                    textPesoItens.value = pesoFormat;

                    selecionado.id = 0;
                    selecionado.descricao = "";
                    selecionado.peso = 0.0;
                    selecionado.estado = 0;
                    selecionado.representacao = "";

                    textQtde.value = 0;
                    textProdSel.value = "";

                    erroQtde = true;
                    $("#msqtdeprod").html('');

                    erroProd = true;
                    $("#msprodsel").html('');

                    $.fancybox.close();
                }

            } else {
                mostraDialogo(
                    res,
                    "danger",
                    3000
                );
            }
        } else {
            mostraDialogo(
                "Erro na requisição da URL /representacoes/orcamento/venda/novo/item/obter-tipos-por-item.php. <br />" +
                "Status: "+request.status+" "+request.statusText,
                "danger",
                3000
            );
        }
    }
}

function obterProdutos(representacao) {
    let request = new XMLHttpRequest();
    request.open('POST', '/representacoes/orcamento/frete/novo/item/obter.php', false);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(encodeURI('representacao='+representacao));

    if (request.DONE === 4 && request.status === 200) {
        let response = JSON.parse(request.responseText);
        if (response !== null && typeof response !== "string") {
            preencheTabelaProd(response);
        } else {
            mostraDialogo(
                response,
                "danger",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Erro na requisição da URL /representacoes/orcamento/venda/novo/item/obter-tipos-por-item.php. <br />" +
            "Status: "+request.status+" "+request.statusText,
            "danger",
            3000
        );
    }
}

function truncate(valor) {
    let numbers = valor.toString();
    numbers = numbers.replace('.', '#');
    if (numbers.search('#') === -1 || numbers.substring(numbers.search('#'), numbers.length).length <= 2) return valor;
    let numbersTruncated = numbers.substring(0, numbers.search('#')+3);
    numbersTruncated = numbersTruncated.replace('#', '.');

    return Number.parseFloat(numbersTruncated);
}