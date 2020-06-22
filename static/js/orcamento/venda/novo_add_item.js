const textFiltroProd = document.getElementById("text_filtro_prod");
const tableProdutos = document.getElementById("table_produtos");
const tbodyProduto = document.getElementById("tbody_produtos");
const textQtde = document.getElementById("text_qtde_prod");
const textProdSel = document.getElementById("text_prod_sel");

var selecionado = {
    id: 0,
    descricao: "",
    peso: 0.0,
    preco: 0.0,
    precoOut: 0.0,
    estado: 0,
    representacao: ""
};

function preencheTabelaItens(dados) {
    var txt = "";
    $.each(dados, function () {
        let valorFormat = this.valor.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');

        let precoFormat = this.produto.preco.toString();
        precoFormat = precoFormat.replace('.', '#');
        if (precoFormat.search('#') === -1) precoFormat += ',00';
        else precoFormat = precoFormat.replace('#', ',');
        txt +=
            '<tr>\
                <td>' + this.produto.descricao + '</td>\
                <td>' + this.produto.representacao + '</td>\
                <td>' + precoFormat + '</td>\
                <td>' + this.quantidade + '</td>\
                <td>' + valorFormat + '</td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluirItem(' + this.produto.id +')"></a></td>\
            </tr>';
    });
    $(tbodyItens).html(txt);
}

function preencheTabelaProd(dados) {
    var txt = "";
    $.each(dados, function () {
        let valorFormat = this.preco.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');
        txt +=
            '<tr>\
                <td>' + this.descricao + '</td>\
                <td>' + this.medida + '</td>\
                <td>' + this.representacao.pessoa.nomeFantasia + '</td>\
                <td>' + valorFormat + '</td>\
                <td><a role="button" class="glyphicon glyphicon-ok-circle" data-toggle="tooltip" data-placement="top" title="SELECIONAR" href="javascript:selecionar(' + this.id + ',\''+ this.descricao +'\','+ this.peso +','+ this.preco +','+ this.precoOut +','+ this.representacao.pessoa.contato.endereco.cidade.estado.id +',\''+ this.representacao.pessoa.nomeFantasia +'\');"></a></td>\
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
            url: "/representacoes/orcamento/venda/novo/item/obter-por-filtro.php",
            data: { filtro: filtro },
            async: false,
            success: (response) => { preencheTabelaProd(response); },
            error: (XMLHttpRequest, txtStatus, thrown) => { alert("Código: "+txtStatus+"\n Erro: "+thrown); }
        });
    }
}

function selecionar(id,desc,peso,preco,precoOut,est,rep) {
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

    textQtde.value = 0;
    textProdSel.value = "";

    erroQtde = true;
    $("#msqtdeprod").html('');

    erroProd = true;
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
    pesoFormat = peso.toString().replace('.', ',');
    valorFormat = valor.toString();
    valorFormat = valorFormat.replace('.', '#');
    if (valorFormat.search('#') === -1) valorFormat += ',00';
    else valorFormat = valorFormat.replace('#', ',');

    textPesoItens.value = pesoFormat;
    textValorItens.value = valorFormat;
    itens = temp;
    preencheTabelaItens(itens);
}

function adicionarItem() {
    let erroQtde = true;
    let erroProd = true;
    let qtde = textQtde.value;
    let prod = textProdSel.value;

    if (qtde.toString().trim().length === 0 || qtde.toString() === "0") {
        erroQtde = true;
        $("#msqtdeprod").html('<span class="label label-danger">A quantidade precisa ser preenchida.</span>');
    } else {
        erroQtde = false;
        $("#msqtdeprod").html('');
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
        let valor = 0.0;
        if (Number(selectEstado.value) !== selecionado.estado) {
            peso = selecionado.peso * Number(qtde);
            valor = selecionado.precoOut * Number(qtde);
        } else {
            peso = selecionado.peso * Number(qtde);
            valor = selecionado.preco * Number(qtde);
        }
        itens.push({
            orcamento: 0,
            produto: {
                id: selecionado.id,
                descricao: selecionado.descricao,
                peso: selecionado.peso,
                preco: selecionado.preco,
                precoOut: selecionado.precoOut,
                estado: selecionado.estado,
                representacao: selecionado.representacao
            },
            quantidade: Number(qtde),
            valor: truncate(valor),
            peso: peso
        });

        preencheTabelaItens(itens);

        let valorItens = 0.0;
        let pesoItens = 0.0;
        for(let i = 0; i < itens.length; i++) {
            valorItens += itens[i].valor;
            pesoItens += itens[i].peso;
        }

        let valorFormat = valorItens.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');

        let pesoFormat = pesoItens.toString();
        pesoFormat = pesoFormat.replace('.', ',');

        textValorItens.value = valorFormat;
        textPesoItens.value = pesoFormat;

        selecionado.id = 0;
        selecionado.descricao = "";
        selecionado.peso = 0.0;
        selecionado.preco = 0.0;
        selecionado.precoOut = 0.0;
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