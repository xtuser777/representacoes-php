const textFiltroProd = document.getElementById("text_filtro_prod");
const tableProdutos = document.getElementById("table_produtos");
const tbodyProduto = document.getElementById("tbody_produtos");
const textQtde = document.getElementById("text_qtde_prod");
const textValor = document.getElementById("text_valor_prod");
const textValorTotal = document.getElementById("text_total_prod");
const textProdSel = document.getElementById("text_prod_sel");

var selecionado = {
    id: 0,
    descricao: "",
    peso: 0.0,
    preco: 0.0,
    precoOut: 0.0,
    estado: 0,
    representacao: "",
    representacaoId: 0
};

let comissao = {
    representacao: {
        id: 0,
        nomeFantasia: ""
    },
    valor: 0.0,
    porcentagem: 0
};

var tipos = [];

function preencheTabelaItens(dados) {
    let txt = "";

    $.each(dados, function () {
        txt +=
            '<tr>\
                <td>' + this.produto.descricao + '</td>\
                <td>' + this.produto.representacao + '</td>\
                <td>' + formatarValor(this.produto.preco) + '</td>\
                <td>' + this.quantidade + '</td>\
                <td>' + formatarValor(this.valor) + '</td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluirItem(' + this.produto.id +')"></a></td>\
            </tr>';
    });
    $(tbodyItens).html(txt);
}

function preencheTabelaProd(dados) {
    let txt = "";

    $.each(dados, function () {
        txt +=
            '<tr>\
                <td>' + this.descricao + '</td>\
                <td>' + this.medida + '</td>\
                <td>' + this.representacao.pessoa.nomeFantasia + '</td>\
                <td>' + formatarValor(this.preco) + '</td>\
                <td><a role="button" class="glyphicon glyphicon-ok-circle" data-toggle="tooltip" data-placement="top" title="SELECIONAR" href="javascript:selecionar(' + this.id + ',\''+ this.descricao +'\','+ this.peso +','+ this.preco +','+ this.precoOut +','+ this.representacao.pessoa.contato.endereco.cidade.estado.id +',\''+ this.representacao.pessoa.nomeFantasia +'\','+ this.representacao.id +');"></a></td>\
            </tr>';
    });
    $(tbodyProduto).html(txt);
}

function preencheTabelaComissoes(dados) {
    let txt = "";

    for (let i = 0; i < dados.length; i++) {
        txt +=
            '<tr>\
                <td>' + dados[i].representacao.nomeFantasia + '</td>\
                <td>' + formatarValor(dados[i].valor) + '</td>\
                <td>' + dados[i].porcentagem + '</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="EDITAR PORCENTAGEM" href="javascript:editarPorcentagemComissao(' + dados[i].representacao.id +')"></a></td>\
            </tr>';
    }
    $(tbodyComissoes).html(txt);
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
            url: "/representacoes/orcamento/venda/novo/item/obter-por-filtro.php",
            data: { filtro: filtro },
            async: false,
            success: (response) => { preencheTabelaProd(response); },
            error: (XMLHttpRequest, txtStatus, thrown) => { alert("Código: "+txtStatus+"\n Erro: "+thrown); }
        });
    }
}

function selecionar(id,desc,peso,preco,precoOut,est,rep,repId) {
    if (itens.findIndex((element) => { return (element.produto.id === id); }) >= 0) {
        alert("Este produto já foi adicionado ao orçamento.");
    } else {
        selecionado.id = id;
        selecionado.descricao = desc;
        selecionado.peso = peso;
        selecionado.preco = preco;
        selecionado.precoOut = precoOut;
        selecionado.estado = est;
        selecionado.representacao = rep;
        selecionado.representacaoId = repId;

        if (Number.parseInt(selectEstado.value) !== Number.parseInt(est)) {
            $("#msvalorout").html('<span class="label label-info">Preço fora do estado.</span>');
        } else {
            $("#msvalorout").html('');
        }

        let valor = (Number.parseInt(selectEstado.value) === Number.parseInt(est)) ? preco : precoOut;

        textValor.value = formatarValor(valor);
        textProdSel.value = desc;
    }
}

function abrirAdicionarItem() {
    if (selectEstado.value === "0") {
        alert("Selecione o estado de destino primeiro.");
    } else {
        $.fancybox.open({ src : '#fbFrmAddProduto', type : 'inline' });
        obterProdutos();
    }
}

function cancelarAdicao() {
    selecionado.id = 0;
    selecionado.descricao = "";
    selecionado.peso = 0.0;
    selecionado.preco = 0.0;
    selecionado.precoOut = 0.0;
    selecionado.estado = 0;
    selecionado.representacao = "";
    selecionado.representacaoId = 0;

    textQtde.value = 0;
    textValor.value = 0.0;
    textProdSel.value = "";
    textValorTotal.value = "0,00";

    $("#msqtdeprod").html('');

    $("#msvalorprod").html('');

    $("#msprodsel").html('');

    $.fancybox.close();
}

function excluirItem(id) {
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
    let valorFormat = textValorItens.value;

    let peso = Number.parseFloat(pesoFormat.replace(',', '.'));
    let valor = Number.parseFloat(valorFormat.replace(',', '.'));

    peso -= itens[x].peso;
    valor -= itens[x].valor;

    textPesoItens.value = formatarPeso(peso);
    textValorItens.value = formatarValor(truncate(valor));

    let itemComissao = {
        produto: {
            id: itens[x].produto.id,
            descricao: itens[x].produto.descricao,
            peso: itens[x].produto.peso,
            preco: itens[x].produto.preco,
            precoOut: itens[x].produto.precoOut,
            estado: itens[x].produto.estado,
            representacao: {
                id: itens[x].produto.representacaoId,
                nomeFantasia: itens[x].produto.representacao
            }
        },
        valor: itens[x].valor,
        peso: itens[x].peso
    };

    deletarComissao(itemComissao);

    itens = temp;

    if (temp.length === 0) {
        tipos = [];
    }

    preencheTabelaItens(itens);
}

function calcularTotalItem() {
    let valorText = textValor.value.toString();
    let qtdeText = textQtde.value.toString();

    valorText = valorText.replace(",", ".");
    let un = Number.parseFloat(valorText);

    if (textProdSel.value === null || textProdSel.value === "") {
        $("#msprodsel").html('<span class="label label-danger">Selecione um produto.</span>');
    } else {
        $("#msprodsel").html('');

        if (un === 0 || isNaN(un)) {
            $("#msvalorprod").html('<span class="label label-danger">Informe um valor válido.</span>');
        } else {
            $("#msvalorprod").html('');

            if (qtdeText.search("e") > -1) {
                $("#msqtdeprod").html('<span class="label label-danger">Informe uma quantidade válida.</span>');
            } else {
                $("#msqtdeprod").html('');

                let qtde = Number.parseInt(qtdeText);
                if (qtde === 0 || isNaN(qtde)) {
                    $("#msqtdeprod").html('<span class="label label-danger">Informe uma quantidade válida.</span>');
                } else {
                    $("#msqtdeprod").html('');

                    let total = un * qtde;
                    total = truncate(total);

                    textValorTotal.value = formatarValor(total);
                }
            }
        }
    }

}

function adicionarItem() {
    let erroQtde = true;
    let erroValor = true;
    let erroProd = true;
    let erroTipos = true;
    let qtde = Number.parseInt(textQtde.value);
    let valorProd = Number.parseFloat(textValor.value);
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

    if (valorProd === 0.0 || isNaN(valorProd)) {
        erroValor = true;
        $("#msvalorprod").html('<span class="label label-danger">O valor unitário deve ser preenchido!</span>');
    } else {
        if (valorProd < 0.0) {
            erroValor = true;
            $("#msvalorprod").html('<span class="label label-danger">O valor unitário deve ser maior que 0.</span>');
        } else {
            erroValor = false;
            $("#msvalorprod").html('');
        }
    }

    if (prod.toString().trim().length === 0) {
        erroProd = true;
        $("#msprodsel").html('<span class="label label-danger">Um produto precisa ser selecionado.</span>');
    } else {
        erroProd = false;
        $("#msprodsel").html('');
    }

    if (!erroQtde && !erroValor && !erroProd) {
        let peso = 0.0;
        let valor = 0.0;

        let request = new XMLHttpRequest();
        request.open('POST', '/representacoes/pedido/venda/novo/item/obter-tipos-por-item.php', false);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(encodeURI('item='+selecionado.id));

        if (request.DONE === 4 && request.status === 200) {
            let res = JSON.parse(request.responseText);
            if (res !== null && typeof res !== "string" && res.length !== 0) {
                let tmp = [];

                if (tipos.length === 0) {
                    tmp = res;
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

                    peso = selecionado.peso * qtde;
                    valor = Number.parseFloat(textValorTotal.value.replace(",", "."));

                    itens.push({
                        orcamento: 0,
                        produto: {
                            id: selecionado.id,
                            descricao: selecionado.descricao,
                            peso: selecionado.peso,
                            preco: selecionado.preco,
                            precoOut: selecionado.precoOut,
                            estado: selecionado.estado,
                            representacao: selecionado.representacao,
                            representacaoId: selecionado.representacaoId
                        },
                        quantidade: qtde,
                        valor: valor,
                        peso: peso
                    });

                    preencheTabelaItens(itens);

                    let valorItens = 0.0;
                    let pesoItens = 0.0;
                    for(let i = 0; i < itens.length; i++) {
                        valorItens += itens[i].valor;
                        pesoItens += itens[i].peso;
                    }

                    textValorItens.value = formatarValor(truncate(valorItens));
                    textPesoItens.value = formatarPeso(pesoItens);

                    let itemComissao = {
                        produto: {
                            id: selecionado.id,
                            descricao: selecionado.descricao,
                            peso: selecionado.peso,
                            preco: selecionado.preco,
                            precoOut: selecionado.precoOut,
                            estado: selecionado.estado,
                            representacao: {
                                id: selecionado.representacaoId,
                                nomeFantasia: selecionado.representacao
                            }
                        },
                        valor: valor,
                        peso: peso
                    };

                    adicionarComissao(itemComissao);

                    selecionado.id = 0;
                    selecionado.descricao = "";
                    selecionado.peso = 0.0;
                    selecionado.preco = 0.0;
                    selecionado.precoOut = 0.0;
                    selecionado.estado = 0;
                    selecionado.representacao = "";

                    textQtde.value = 0;
                    textValor.value = 0.0;
                    textProdSel.value = "";
                    textValorTotal.value = "0,00";

                    erroQtde = true;
                    $("#msqtdeprod").html('');

                    erroValor = true;
                    $("#msvalorprod").html('');

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

function adicionarComissao(item) {
    let i;
    let achou = false;

    for (i = 0; i < comissoes.length; i++) {
        if (comissoes[i].representacao.id === item.produto.representacao.id) {
            achou = true;
            comissoes[i].valor = truncate(comissoes[i].valor + item.valor);
        }
    }

    if (i === comissoes.length && !achou) {
        comissoes.push({
            representacao: {
                id: item.produto.representacao.id,
                nomeFantasia: item.produto.representacao.nomeFantasia
            },
            valor: item.valor,
            porcentagem: 5
        });
    }

    preencheTabelaComissoes(comissoes);
}

function deletarComissao(item) {
    let achou = false;

    for (let i = 0; i < comissoes.length && !achou; i++) {
        if (comissoes[i].representacao.id === item.produto.representacao.id && comissoes[i].valor > item.valor) {
            achou = true;
            comissoes[i].valor = truncate(comissoes[i].valor - item.valor);
        } else {
            if (comissoes[i].representacao.id === item.produto.representacao.id) {
                achou = true;
                let tmp = [];

                for (let x = 0; x < comissoes.length; x++) {
                    if (comissoes[x].representacao.id !== item.produto.representacao.id) {
                        tmp.push(comissoes[x]);
                    }
                }

                comissoes = tmp;
            }
        }
    }

    preencheTabelaComissoes(comissoes);
}

function editarPorcentagemComissao(representacao) {
    let i = 0;

    while(i < comissoes.length && comissoes[i].representacao.id !== representacao)
        i++;

    let porc = prompt("Informe a porcentagem de comissão a ser recebida desta representação:", "10");
    let porcInt = Number.parseInt(porc);
    while (porcInt === null || isNaN(porcInt) || porcInt <= 0) {
        porc = prompt("Por favor, informe uma porcentagem de comissão válida:", "10");
        porcInt = Number.parseInt(porc);
    }

    comissoes[i].porcentagem = porcInt;

    preencheTabelaComissoes(comissoes);
}

function obterProdutos() {
    let produtos = get("/representacoes/orcamento/venda/novo/item/obter.php");
    preencheTabelaProd(produtos);
}

function truncate(valor) {
    let numbers = valor.toString();
    numbers = numbers.replace('.', '#');
    if (numbers.search('#') === -1 || numbers.substring(numbers.search('#'), numbers.length).length <= 2) return valor;
    let numbersTruncated = numbers.substring(0, numbers.search('#')+3);
    numbersTruncated = numbersTruncated.replace('#', '.');

    return Number.parseFloat(numbersTruncated);
}