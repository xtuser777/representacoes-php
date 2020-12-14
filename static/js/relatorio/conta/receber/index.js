const textFiltro = document.getElementById("textFiltro");
const dateFiltroInicio = document.getElementById("dateFiltroInicio");
const dateFiltroFim = document.getElementById("dateFiltroFim");
const dateVencimento = document.getElementById('dateVencimento');
const selectComissao = document.getElementById("selectComissao");
const selectRepresentacao = document.getElementById("selectRepresentacao");
const selectOrdem = document.getElementById("selectOrdem");
const selectSituacao = document.getElementById("selectSituacao");
const tableContas = document.getElementById("tableContas");
const tbodyContas = document.getElementById("tbodyContas");

function preencherTabela(dados) {
    let txt = ``;
    $.each(dados, function () {
        let sit = "";
        switch (this.situacao) {
            case 1:
                sit = "PENDENTE";
                break;
            case 2:
                sit = "PAGO PARC.";
                break;
            case 3:
                sit = "PAGO";
                break;
        }

        txt +=
            `<tr>
                <td class="hidden">${this.id}</td>
                <td>${this.conta}</td>
                <td>${this.descricao}</td>
                <td>${formatarValor(this.valor)}</td>
                <td>${FormatarData(this.data)}</td>
                <td>${FormatarData(this.vencimento)}</td>
                <td>${formatarValor(this.valorRecebido)}</td>
                <td>${( (this.dataRecebimento === "") ? "" : FormatarData(this.dataRecebimento) )}</td>
                <td>${sit}</td>
            </tr>`;
    });
    $(tbodyContas).html(txt);
}

function selecionarComissao() {
    let comissao = Number.parseInt(selectComissao.value);

    if (comissao === null || isNaN(comissao) || comissao === 0 || comissao === 2) {
        selectRepresentacao.value = "0";
        selectRepresentacao.disabled = true;
    } else {
        selectRepresentacao.disabled = false;
    }
}

async function obter(ordem = "1") {
    let params = new FormData();
    params.append('ordem', ordem);

    let res = await post(
        '/representacoes/relatorio/conta/receber/obter.php',
        params
    );

    if (res.status) {
        preencherTabela(res.response);
    } else {
        mostraDialogo(
            res.error.message + "<br />" +
            "Status: "+res.error.code+" "+res.error.status,
            "danger",
            3000
        );
    }
}

$(document).ready(async function (event) {
    let representacoes = get("/representacoes/relatorio/conta/receber/obter-representacoes.php");
    if (representacoes !== null && representacoes.length > 0) {
        for (let i = 0; i < representacoes.length; i++) {
            let option = document.createElement("option");
            option.text = representacoes[i].pessoa.nomeFantasia;
            option.value = representacoes[i].id;

            selectRepresentacao.appendChild(option);
        }
    }

    selecionarComissao();

    await obter();
});

async function obterPorFiltros(filtro, inicio, fim, venc, comissao, representacao, situacao, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/receber/obter-por-filtros.php',
        {
            filtro: filtro,
            inicio: inicio,
            fim: fim,
            venc: venc,
            comissao: comissao,
            representacao: representacao,
            situacao: situacao,
            ordem: ordem
        }
    );

    if (res.status) {
        preencherTabela(res.response);
    } else {
        mostraDialogo(
            res.error.message + "<br />" +
            "Status: "+res.error.code+" "+res.error.status,
            "danger",
            3000
        );
    }
}

async function filtrar() {
    let filtro = textFiltro.value;
    let inicio = dateFiltroInicio.value;
    let fim = dateFiltroFim.value;
    let venc = dateVencimento.value;
    let comissao = Number.parseInt(selectComissao.value);
    let representacao = Number.parseInt(selectRepresentacao.value);
    let situacao = Number.parseInt(selectSituacao.value);
    let ordem = selectOrdem.value;

    if (filtro === '' && inicio === '' && fim === '' && venc === '' && comissao === 0 && representacao === 0 && situacao === 0) {
        await obter(ordem);
    } else {
        if (filtro !== '' || inicio !== '' || fim !== '' || venc !== '' || comissao !== 0 || representacao !== 0 || situacao !== 0) {
            await obterPorFiltros(filtro, inicio, fim, venc, comissao, representacao, situacao, ordem);
        } else {
            if ((inicio !== '' && fim === '') || (inicio === '' && fim !== '')) {
                mostraDialogo(
                    'As duas datas de lançamento devem estar preenchidas.',
                    'warning',
                    3000
                );
            }
        }
    }
}

function emitir() {
    let filtro = textFiltro.value;
    let inicio = dateFiltroInicio.value;
    let fim = dateFiltroFim.value;
    let venc = dateVencimento.value;
    let comissao = Number.parseInt(selectComissao.value);
    let representacao = Number.parseInt(selectRepresentacao.value);
    let situacao = Number.parseInt(selectSituacao.value);

    const guia = window.open(`/representacoes/relatorio/conta/receber/emitir.php?filtro=${filtro}&inicio=${inicio}&fim=${fim}&venc=${venc}&comissao=${comissao}&representacao=${representacao}&situacao=${situacao}&ordem=1`, '_blank');
    guia.focus();
}