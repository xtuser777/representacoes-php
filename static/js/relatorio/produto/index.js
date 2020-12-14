const textFiltro = document.getElementById('textFiltro');
const textUnidade = document.getElementById('textUnidade');
const selectRepresentacao = document.getElementById('selectRepresentacao');
const selectOrdem = document.getElementById('selectOrdem');
const tableProdutos = document.getElementById('tableProdutos');
const tbodyProdutos = document.getElementById('tbodyProdutos');

function preencherTabela(dados) {
    let txt = ``;
    $.each(dados, function () {
        txt +=
            `<tr>
                <td class="hidden">${this.id}</td>
                <td>${this.descricao}</td>
                <td>${this.medida}</td>
                <td>${formatarPeso(this.peso)}</td>
                <td>${formatarValor(this.preco)}</td>
                <td>${this.representacao.pessoa.nomeFantasia}</td>
            </tr>`;
    });
    $(tbodyProdutos).html(txt);
}

async function obter(ordem = '1') {
    let res = await postJSON(
        '/representacoes/relatorio/produto/obter.php',
        { ordem: ordem }
    );

    if (res.status) {
        preencherTabela(res.response);
    } else {
        mostraDialogo(
            `Código: ${res.error.code}. <br />
            Erro: ${res.error.message}`,
            'danger',
            3000
        );
    }
}

$(document).ready(async function (event) {
    let representacoes = get("/representacoes/gerenciar/produto/obter-representacoes.php");
    if (representacoes !== null && representacoes.length > 0) {
        for (let i = 0; i < representacoes.length; i++) {
            let option = document.createElement("option");
            option.value = representacoes[i].id;
            option.text = representacoes[i].pessoa.nomeFantasia + " (" + representacoes[i].unidade + ")";
            selectRepresentacao.appendChild(option);
        }
    }

    await obter();
});

async function obterPorFiltros(filtro, unidade, representacao, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/produto/obter-por-filtros.php',
        {
            filtro: filtro,
            unidade: unidade,
            representacao: representacao,
            ordem: ordem,
        }
    );

    if (res.status) {
        preencherTabela(res.response);
    } else {
        mostraDialogo(
            `Código: ${res.error.code}. <br />
            Erro: ${res.error.message}`,
            'danger',
            3000
        );
    }
}

async function filtrar() {
    let filtro = textFiltro.value;
    let unidade = textUnidade.value;
    let representacao = Number.parseInt(selectRepresentacao.value);
    let ordem = selectOrdem.value;

    if (filtro === '' && unidade === '' && representacao === 0) {
        await obter(ordem);
    } else {
        await obterPorFiltros(filtro, unidade, representacao, ordem);
    }
}

function emitir() {
    let filtro = textFiltro.value;
    let unidade = textUnidade.value;
    let representacao = Number.parseInt(selectRepresentacao.value);
    let ordem = selectOrdem.value;

    const guia = window.open(`/representacoes/relatorio/produto/emitir.php?filtro=${filtro}&unidade=${unidade}&representacao=${representacao}&ordem=${ordem}`, '_blank');
    guia.focus();
}