const textFiltroProd = document.getElementById("text_filtro_prod");
const tbodyProduto = document.getElementById("tbody_produtos");
const textQtde = document.getElementById("text_qtde_prod");
const textProdSel = document.getElementById("text_prod_sel");
const textPesoTotal = document.getElementById("text_peso_total");

let selecionado = {
    id: 0,
    descricao: '',
    peso: 0.0,
    estado: 0,
    representacaoId: 0,
    representacaoNome: '',
    representacaoUn: ''
};

function preencheTabelaItens(dados) {
    let txt = "";

    $.each(dados, function () {
        txt +=
            '<tr>\
                <td>' + this.produto.descricao + '</td>\
                <td>' + this.produto.representacao.nomeFantasia + '</td>\
                <td>' + formatarPeso(this.produto.peso) + '</td>\
                <td>' + this.quantidade + '</td>\
                <td>' + this.peso + '</td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluirItem(' + this.produto.id +')"></a></td>\
            </tr>';
    });

    $(tbodyItens).html(txt);
}

function preencheTabelaEtapas(dados) {
    let txt = ``;

    for (let i = 0; i < dados.length; i++) {
        let status = '';

        switch (dados[i].status) {
            case 1: status = 'PENDENTE'; break;
            case 2: status = 'AUTORIZADO'; break;
            case 3: status = 'CARREGADO'; break;
        }

        txt +=
            `<tr>
                <td>${dados[i].ordem}</td>
                <td>${dados[i].representacao.nomeFantasia}</td>
                <td>${dados[i].representacao.unidade}</td>
                <td>${formatarPeso(dados[i].carga)}</td>
                <td>${status}</td>
            </tr>`;
    }

    tbodyEtapas.innerHTML = txt;
}

function preencheTabelaProd(dados) {
    let txt = "";

    $.each(dados, function () {
        txt +=
            '<tr>\
                <td>' + this.descricao + '</td>\
                <td>' + this.medida + '</td>\
                <td>' + this.representacao.pessoa.nomeFantasia + '</td>\
                <td>' + formatarPeso(this.peso) + '</td>\
                <td>\
                    <a \
                        role="button" \
                        class="glyphicon glyphicon-ok-circle" \
                        data-toggle="tooltip" \
                        data-placement="top" \
                        title="SELECIONAR" \
                        href="javascript:selecionar(' + this.id + ',\''+ this.descricao +'\','+ this.peso +','+ this.representacao.pessoa.contato.endereco.cidade.estado.id +','+ this.representacao.id +',\''+ this.representacao.pessoa.nomeFantasia +'\',\''+ this.representacao.unidade +'\');"\
                    ></a>\
                </td>\
            </tr>';
    });

    $(tbodyProduto).html(txt);
}

async function buttonFiltrarProdClick() {
    let filtro = textFiltroProd.value.toString();

    if (filtro.trim().length === 0) {
        obterProdutos();
    } else {
        let res = await postJSON(
            '/representacoes/pedido/frete/novo/item/obter-por-filtro.php',
            {
                representacao: Number.parseInt(selectRepresentacao.value),
                filtro: filtro
            }
        );

        if (res.status) {
            preencheTabelaProd(res.response);
        } else {
            mostraDialogo(
                res.error.message + "<br />" +
                "Status: "+res.error.code+" "+res.error.status,
                "danger",
                3000
            );
        }
    }
}

function selecionar(id,desc,peso,est,repId,rep,repUn) {
    if (itens.findIndex((element) => { return (element.produto.id === id); }) >= 0) {
        alert("Este produto já foi adicionado ao pedido.");
    } else {
        selecionado.id = id;
        selecionado.descricao = desc;
        selecionado.peso = peso;
        selecionado.estado = est;
        selecionado.representacaoId = repId;
        selecionado.representacaoNome = rep;
        selecionado.representacaoUn = repUn;

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
    selecionado.descricao = '';
    selecionado.peso = 0.0;
    selecionado.estado = 0;
    selecionado.representacaoId = 0;
    selecionado.representacaoNome = '';
    selecionado.representacaoUn = '';

    textQtde.value = 0;
    textProdSel.value = '';
    textPesoTotal.value = '0,0';

    erroQtde = true;
    $("#msqtdeprod").html('');

    erroProd = true;
    $("#msprodsel").html('');

    $.fancybox.close();
}

function excluirItem(id) {
    if (Number.parseInt(selectPedVenda.value) !== 0) {
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

        textPesoItens.value = formatarPeso(peso);

        deletarEtapa(itens[x]);

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

                textPesoTotal.value = formatarPeso(total);
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

        let res = await postJSON(
            '/representacoes/pedido/frete/novo/item/obter-tipos-por-item.php',
            { item: selecionado.id }
        );

        if (res.status) {
            let tmp = [];

            if (tipos.length === 0) {
                tmp = res.response;
                for (let i = 0; i < res.response.length; i++) {
                    let option = document.createElement("option");
                    option.value = res.response[i].id;
                    option.text = res.response[i].descricao;
                    selectTipoCam.appendChild(option);
                }
            } else {
                for (let i = 0; i < res.response.length; i++) {
                    if (tipos.findIndex((element) => { return (element.id === res.response[i].id); }) !== -1) {
                        tmp.push(res.response[i]);
                    }
                }
            }

            if (tmp.length === 0) {
                alert("Este item não poderá ser entregue no mesmo frete por falta de compatibilidade de tipo de caminhão.");
            } else {
                tipos = tmp;
                limparSelectTipo();
                for (let i = 0; i < tmp.length; i++) {
                    let option = document.createElement("option");
                    option.value = tmp[i].id;
                    option.text = tmp[i].descricao;
                    selectTipoCam.appendChild(option);
                }

                peso = Number.parseFloat(textPesoTotal.value.replace(",", "."));

                let item = {
                    produto: {
                        id: selecionado.id,
                        descricao: selecionado.descricao,
                        peso: selecionado.peso,
                        estado: selecionado.estado,
                        representacao: {
                            id: selecionado.representacaoId,
                            nomeFantasia: selecionado.representacaoNome,
                            unidade: selecionado.representacaoUn
                        }
                    },
                    quantidade: Number(qtde),
                    peso: peso
                };

                itens.push(item);

                preencheTabelaItens(itens);

                adicionarEtapa(item);

                let pesoItens = 0.0;
                for(let i = 0; i < itens.length; i++) {
                    pesoItens += itens[i].peso;
                }

                textPesoItens.value = formatarPeso(pesoItens);

                selecionado.id = 0;
                selecionado.descricao = '';
                selecionado.peso = 0.0;
                selecionado.estado = 0;
                selecionado.representacaoId = 0
                selecionado.representacaoNome = '';
                selecionado.representacaoUn = '';

                textQtde.value = 0;
                textProdSel.value = '';
                textPesoTotal.value = '0,0';

                erroQtde = true;
                $("#msqtdeprod").html('');

                erroProd = true;
                $("#msprodsel").html('');

                $.fancybox.close();
            }
        } else {
            mostraDialogo(
                res.error.message + "<br />" +
                "Status: "+res.error.code+" "+res.error.status,
                "danger",
                3000
            );
        }
    }
}

async function obterProdutos(representacao) {
    let res = await postJSON(
        '/representacoes/pedido/frete/novo/item/obter.php',
        { representacao: representacao }
    );

    if (res.status) {
        preencheTabelaProd(res.response);
    } else {
        mostraDialogo(
            res.error.message + "<br />" +
            "Status: "+res.error.code+" "+res.error.status,
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