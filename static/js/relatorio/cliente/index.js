const textFiltro = document.getElementById('textFiltro');
const dateCadastroInicio = document.getElementById('dateCadastroInicio');
const dateCadastroFim = document.getElementById('dateCadastroFim');
const selectTipo = document.getElementById('selectTipo');
const selectOrdem = document.getElementById('selectOrdem');
const tableClientes = document.getElementById('tableClientes');
const tbodyClientes = document.getElementById('tbodyClientes');

function preencherTabela(dados) {
    let txt = ``;
    $.each(dados, function () {
        let nome = (this.tipo === 1) ? this.pessoaFisica.nome : this.pessoaJuridica.nomeFantasia;
        let doc = (this.tipo === 1) ? this.pessoaFisica.cpf : this.pessoaJuridica.cnpj;
        let tel = (this.tipo === 1) ? this.pessoaFisica.contato.telefone : this.pessoaJuridica.contato.telefone;
        let cel = (this.tipo === 1) ? this.pessoaFisica.contato.celular : this.pessoaJuridica.contato.celular;
        let email = (this.tipo === 1) ? this.pessoaFisica.contato.email : this.pessoaJuridica.contato.email;
        let tipo = (this.tipo === 1) ? "Física" : "Jurídica";
        txt +=
            `<tr>
                <td class="hidden">${this.id}</td>
                <td>${nome}</td>
                <td>${doc}</td>
                <td>${FormatarData(this.cadastro)}</td>
                <td>${tel}</td>
                <td>${cel}</td>
                <td>${tipo}</td>
                <td>${email}</td>
            </tr>`;
    });
    $(tbodyClientes).html(txt);
}

async function obter(ordem = '1') {
    let res = await postJSON(
        '/representacoes/relatorio/cliente/obter.php',
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
    await obter();
});

async function obterPorFiltroPediodoTipo(filtro, inicio, fim, tipo, ordem) {
    let dataInicio = new Date(inicio + '05:00:59');
    let dataFim = new Date(fim + '05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser menor que a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/cliente/obter-por-filtro-periodo-tipo.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim: fim,
                tipo: tipo,
                ordem: ordem
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
}

async function obterPorFiltroPediodo(filtro, inicio, fim, ordem) {
    let dataInicio = new Date(inicio + '05:00:59');
    let dataFim = new Date(fim + '05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser menor que a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/cliente/obter-por-filtro-periodo.php',
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
                `Código: ${res.error.code}. <br />
                Erro: ${res.error.message}`,
                'danger',
                3000
            );
        }
    }
}

async function obterPorFiltroTipo(filtro, tipo, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/cliente/obter-por-filtro-tipo.php',
        {
            filtro: filtro,
            tipo: tipo,
            ordem: ordem
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

async function obterPorFiltro(filtro, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/cliente/obter-por-filtro.php',
        {
            filtro: filtro,
            ordem: ordem
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

async function obterPorPediodoTipo(inicio, fim, tipo, ordem) {
    let dataInicio = new Date(inicio + '05:00:59');
    let dataFim = new Date(fim + '05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser menor que a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/cliente/obter-por-periodo-tipo.php',
            {
                inicio: inicio,
                fim: fim,
                tipo: tipo,
                ordem: ordem
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
}

async function obterPorPediodo(inicio, fim, ordem) {
    let dataInicio = new Date(inicio + '05:00:59');
    let dataFim = new Date(fim + '05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'A data de início deve ser menor que a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/cliente/obter-por-periodo.php',
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
                `Código: ${res.error.code}. <br />
                Erro: ${res.error.message}`,
                'danger',
                3000
            );
        }
    }
}

async function obterPorTipo(tipo, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/cliente/obter-por-tipo.php',
        {
            tipo: tipo,
            ordem: ordem
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
    let inicio = dateCadastroInicio.value;
    let fim = dateCadastroFim.value;
    let tipo = Number.parseInt(selectTipo.value);
    let ordem = selectOrdem.value;

    if (filtro === '' && inicio === '' && fim === '' && tipo === 0) {
        await obter(ordem);
    } else {
        if (filtro !== '' && inicio !== '' && fim !== '' && tipo !== 0) {
            await obterPorFiltroPediodoTipo(filtro, inicio, fim, tipo, ordem);
        } else {
            if (filtro !== '' && inicio !== '' && fim !== '' && tipo === 0) {
                await obterPorFiltroPediodo(filtro, inicio, fim, ordem);
            } else {
                if (filtro !== '' && inicio === '' && fim === '' && tipo !== 0) {
                    await obterPorFiltroTipo(filtro, tipo, ordem);
                } else {
                    if (filtro !== '' && inicio === '' && fim === '' && tipo === 0) {
                        await obterPorFiltro(filtro, ordem);
                    } else {
                        if (filtro === '' && inicio !== '' && fim !== '' && tipo !== 0) {
                            await obterPorPediodoTipo(inicio, fim, tipo, ordem);
                        } else {
                            if (filtro === '' && inicio !== '' && fim !== '' && tipo === 0) {
                                await obterPorPediodo(inicio, fim, ordem);
                            } else {
                                if (filtro === '' && inicio === '' && fim === '' && tipo !== 0) {
                                    await obterPorTipo(tipo, ordem);
                                } else {
                                    mostraDialogo(
                                        'As datas de início e fim precisam estar preenchidas.',
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

function emitir() {
    let filtro = textFiltro.value;
    let inicio = dateCadastroInicio.value;
    let fim = dateCadastroFim.value;
    let tipo = Number.parseInt(selectTipo.value);
    let ordem = selectOrdem.value;

    const guia = window.open(`/representacoes/relatorio/cliente/emitir.php?filtro=${filtro}&inicio=${inicio}&fim=${fim}&tipo=${tipo}&ordem=${ordem}`, '_blank');
    guia.focus();
}