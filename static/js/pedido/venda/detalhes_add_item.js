const textFiltroProd = document.getElementById("text_filtro_prod");
const tableProdutos = document.getElementById("table_produtos");
const tbodyProduto = document.getElementById("tbody_produtos");

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
                <td>' + this.produto.representacao.pessoa.nomeFantasia + '</td>\
                <td>' + formatarValor(this.produto.preco) + '</td>\
                <td>' + this.quantidade + '</td>\
                <td>' + formatarValor(this.valor) + '</td>\
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