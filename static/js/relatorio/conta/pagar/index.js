const textFiltro = document.getElementById("textFiltro");
const dateFiltroInicio = document.getElementById("dateFiltroInicio");
const dateFiltroFim = document.getElementById("dateFiltroFim");
const dateVencimento = document.getElementById('dateVencimento');
const selectComissao = document.getElementById("selectComissao");
const selectVendedor = document.getElementById("selectVendedor");
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
                <td>${this.parcela}</td>
                <td>${formatarValor(this.valor)}</td>
                <td>${FormatarData(this.data)}</td>
                <td>${FormatarData(this.vencimento)}</td>
                <td>${formatarValor(this.valorPago)}</td>
                <td>${( (this.dataPagamento === "") ? "" : FormatarData(this.dataPagamento) )}</td>
                <td>${sit}</td>
            </tr>`;
    });
    $(tbodyContas).html(txt);
}

function selecionarComissao() {
    let comissao = Number.parseInt(selectComissao.value);

    if (comissao === 1) {
        selectVendedor.disabled = false;
        selectVendedor.value = "0";
    } else {
        selectVendedor.value = "0";
        selectVendedor.disabled = true;
    }
}

async function obter(ordem = "1") {
    let params = new FormData();
    params.append('ordem', ordem);

    let res = await post(
        '/representacoes/relatorio/conta/pagar/obter.php',
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
    let vendedores = get('/representacoes/relatorio/conta/pagar/obter-vendedores.php');
    if (vendedores !== null && vendedores.length > 0) {
        for (let i = 0; i < vendedores.length; i++) {
            let option = document.createElement('option');
            option.value = vendedores[i].id;
            option.text = vendedores[i].pessoa.nome;
            selectVendedor.appendChild(option);
        }
    }

    selecionarComissao();

    await obter();
});

async function obterPorFiltros(filtro, inicio, fim, venc, comissao, vendedor, situacao, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/pagar/obter-por-filtros.php',
        {
            filtro: filtro,
            inicio: inicio,
            fim: fim,
            venc: venc,
            comissao: comissao,
            vendedor: vendedor,
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
    let vendedor = Number.parseInt(selectVendedor.value);
    let situacao = Number.parseInt(selectSituacao.value);
    let ordem = selectOrdem.value;

    if (filtro === '' && inicio === '' && fim === '' && venc === '' && comissao === 0 && vendedor === 0 && situacao === 0) {
        await obter(ordem);
    } else {
        if (filtro !== '' || inicio !== '' || fim !== '' || venc !== '' || comissao > 0 || vendedor > 0 || situacao !== 0) {
            await obterPorFiltros(filtro, inicio, fim, venc, comissao, vendedor, situacao, ordem);
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
    let vendedor = Number.parseInt(selectVendedor.value);
    let situacao = Number.parseInt(selectSituacao.value);

    const guia = window.open(`/representacoes/relatorio/conta/pagar/emitir.php?filtro=${filtro}&inicio=${inicio}&fim=${fim}&venc=${venc}&comissao=${comissao}&vendedor=${vendedor}&situacao=${situacao}&ordem=1`, '_blank');
    guia.focus();
}