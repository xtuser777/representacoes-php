const textFiltro = document.getElementById("textFiltro");
const dateFiltroInicio = document.getElementById("dateFiltroInicio");
const dateFiltroFim = document.getElementById("dateFiltroFim");
const dateVencimento = document.getElementById('dateVencimento');
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
    await obter();
});

async function obterPorSituacao(situacao, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/pagar/obter-por-situacao.php',
        {
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

async function obterPorVencimento(venc, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/pagar/obter-por-vencimento.php',
        {
            venc: venc,
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

async function obterPorVencimentoSituacao(venc, situacao, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/pagar/obter-por-vencimento-situacao.php',
        {
            venc: venc,
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

async function obterPorFiltro(filtro, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/pagar/obter-por-filtro.php',
        {
            filtro: filtro,
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

async function obterPorFiltroSituacao(filtro, situacao, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/pagar/obter-por-filtro-situacao.php',
        {
            filtro: filtro,
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

async function obterPorFiltroVencimento(filtro, venc, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/pagar/obter-por-filtro-vencimento.php',
        {
            filtro: filtro,
            venc: venc,
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

async function obterPorFiltroVencimentoSituacao(filtro, venc, situacao, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/conta/pagar/obter-por-filtro-vencimento-situacao.php',
        {
            filtro: filtro,
            venc: venc,
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

async function obterPorPeriodo(inicio, fim, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser igual ou menor que a data fim.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/conta/pagar/obter-por-periodo.php',
            {
                inicio: inicio,
                fim: fim,
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
}

async function obterPorPeriodoSituacao(inicio, fim, situacao, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser igual ou menor que a data fim.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/conta/pagar/obter-por-periodo-situacao.php',
            {
                inicio: inicio,
                fim: fim,
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
}

async function obterPorPeriodoVencimento(inicio, fim, venc, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser igual ou menor que a data fim.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/conta/pagar/obter-por-periodo-vencimento.php',
            {
                inicio: inicio,
                fim: fim,
                venc: venc,
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
}

async function obterPorPeriodoVencimentoSituacao(inicio, fim, venc, situacao, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser igual ou menor que a data fim.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/conta/pagar/obter-por-periodo-vencimento-situacao.php',
            {
                inicio: inicio,
                fim: fim,
                venc: venc,
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
}

async function obterPorFiltroPeriodo(filtro, inicio, fim, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser igual ou menor que a data fim.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/conta/pagar/obter-por-filtro-periodo.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim: fim,
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
}

async function obterPorFiltroPeriodoSituacao(filtro, inicio, fim, situacao, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser igual ou menor que a data fim.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/conta/pagar/obter-por-filtro-periodo-situacao.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim: fim,
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
}

async function obterPorFiltroPeriodoVencimento(filtro, inicio, fim, venc, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser igual ou menor que a data fim.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/conta/pagar/obter-por-filtro-periodo-vencimento.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim: fim,
                venc: venc,
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
}

async function obterPorFiltroPeriodoVencimentoSituacao(filtro, inicio, fim, venc, situacao, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser igual ou menor que a data fim.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/conta/pagar/obter-por-filtro-periodo-vencimento-situacao.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim: fim,
                venc: venc,
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
}

async function filtrar() {
    let filtro = textFiltro.value;
    let inicio = dateFiltroInicio.value;
    let fim = dateFiltroFim.value;
    let venc = dateVencimento.value;
    let situacao = Number.parseInt(selectSituacao.value);
    let ordem = selectOrdem.value;

    if (filtro === '' && inicio === '' && fim === '' && venc === '' && situacao === 0) {
        await obter(ordem);
    } else {
        if (filtro !== '' && inicio !== '' && fim !== '' && venc !== '' && situacao !== 0) {
            await obterPorFiltroPeriodoVencimentoSituacao(filtro, inicio, fim, venc, situacao, ordem);
        } else {
            if (filtro !== '' && inicio !== '' && fim !== '' && venc !== '' && situacao === 0) {
                await obterPorFiltroPeriodoVencimento(filtro, inicio, fim, venc, ordem);
            } else {
                if (filtro !== '' && inicio !== '' && fim !== '' && venc === '' && situacao !== 0) {
                    await obterPorFiltroPeriodoSituacao(filtro, inicio, fim, situacao, ordem);
                } else {
                    if (filtro !== '' && inicio !== '' && fim !== '' && venc === '' && situacao === 0) {
                        await obterPorFiltroPeriodo(filtro, inicio, fim, ordem);
                    } else {
                        if (filtro !== '' && inicio === '' && fim === '' && venc !== '' && situacao !== 0) {
                            await obterPorFiltroVencimentoSituacao(filtro, venc, situacao, ordem);
                        } else {
                            if (filtro !== '' && inicio === '' && fim === '' && venc !== '' && situacao === 0) {
                                await obterPorFiltroVencimento(filtro, venc, ordem);
                            } else {
                                if (filtro !== '' && inicio === '' && fim === '' && venc === '' && situacao !== 0) {
                                    await obterPorFiltroSituacao(filtro, situacao, ordem);
                                } else {
                                    if (filtro !== '' && inicio === '' && fim === '' && venc === '' && situacao === 0) {
                                        await obterPorFiltro(filtro, ordem);
                                    } else {
                                        if (filtro === '' && inicio !== '' && fim !== '' && venc !== '' && situacao !== 0) {
                                            await obterPorPeriodoVencimentoSituacao(inicio, fim, venc, situacao, ordem);
                                        } else {
                                            if (filtro === '' && inicio !== '' && fim !== '' && venc !== '' && situacao === 0) {
                                                await obterPorPeriodoVencimento(inicio, fim, venc, ordem);
                                            } else {
                                                if (filtro === '' && inicio !== '' && fim !== '' && venc === '' && situacao !== 0) {
                                                    await obterPorPeriodoSituacao(inicio, fim, situacao, ordem);
                                                } else {
                                                    if (filtro === '' && inicio !== '' && fim !== '' && venc === '' && situacao === 0) {
                                                        await obterPorPeriodo(inicio, fim, ordem);
                                                    } else {
                                                        if (filtro === '' && inicio === '' && fim === '' && venc !== '' && situacao !== 0) {
                                                            await obterPorVencimentoSituacao(venc, situacao, ordem);
                                                        } else {
                                                            if (filtro === '' && inicio === '' && fim === '' && venc !== '' && situacao === 0) {
                                                                await obterPorVencimento(venc, ordem);
                                                            } else {
                                                                if (filtro === '' && inicio === '' && fim === '' && venc === '' && situacao !== 0) {
                                                                    await obterPorSituacao(situacao, ordem);
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
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

function emitir() {
    let filtro = textFiltro.value;
    let inicio = dateFiltroInicio.value;
    let fim = dateFiltroFim.value;
    let venc = dateVencimento.value;
    let situacao = Number.parseInt(selectSituacao.value);

    const guia = window.open(`/representacoes/relatorio/conta/pagar/emitir.php?filtro=${filtro}&inicio=${inicio}&fim=${fim}&venc=${venc}&situacao=${situacao}&ordem=1`, '_blank');
    guia.focus();
}