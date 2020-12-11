const textFiltro = document.getElementById("textFiltro");
const dateFiltroDataInicio = document.getElementById("dateFiltroDataInicio");
const dateFiltroDataFim = document.getElementById("dateFiltroDataFim");
const selectCliente = document.getElementById('selectCliente');
const selectOrdem = document.getElementById("selectOrdem");
const tablePedidos = document.getElementById("table_pedidos");
const tbodyPedidos = document.getElementById("tbody_pedidos");

function preencherTabela(dados) {
    let txt = ``;

    $.each(dados, function () {
        let cliente = (this.cliente.tipo === 1)
            ? this.cliente.pessoaFisica.nome
            : this.cliente.pessoaJuridica.nomeFantasia;

        let vendedor = this.vendedor ? this.vendedor.pessoa.nome : '';
        let destino = `${this.destino.nome}/${this.destino.estado.sigla}`;

        txt +=
            `<tr>
                <td class="hidden">${this.id}</td>
                <td>${this.descricao}</td>
                <td>${cliente}</td>
                <td>${vendedor}</td>
                <td>${FormatarData(this.data)}</td>
                <td>${destino}</td>
                <td>${this.autor.funcionario.pessoa.nome}</td>
                <td>${this.formaPagamento.descricao}</td>
                <td>${formatarValor(this.valor)}</td>
            </tr>`;
    });
    $(tbodyPedidos).html(txt);
}

async function obter(ordem = "1") {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/venda/obter.php',
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
    let prods = get("/representacoes/gerenciar/produto/obter.php");
    if (prods === null || prods.length === 0) {
        alert("Não existem produtos cadastrados!");
        location.href = "../../inicio";
    }

    let clientes = get('/representacoes/relatorio/pedido/venda/obter-clientes.php');
    if (clientes !== null && clientes.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < clientes.length; i++) {
            let nome = clientes[i].tipo === 1 ? clientes[i].pessoaFisica.nome : clientes[i].pessoaJuridica.nomeFantasia;
            options +=
                `<option value="${clientes[i].id}">${nome}</option>`;
        }

        selectCliente.innerHTML = options;
    }

    await obter();
});

async function obterPorFiltroPeriodoCliente(filtro, inicio, fim, cliente, ordem) {
    let dateInicio = new Date(inicio + ' 05:00:59');
    let dateFim = new Date(fim + ' 05:00:59');

    if (dateInicio > dateFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/venda/obter-por-filtro-periodo-cliente.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim : fim,
                cliente: cliente,
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

async function obterPorFiltroPeriodo(filtro, inicio, fim, ordem) {
    let dateInicio = new Date(inicio + ' 05:00:59');
    let dateFim = new Date(fim + ' 05:00:59');

    if (dateInicio > dateFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/venda/obter-por-filtro-periodo.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim : fim,
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

async function obterPorFiltroCliente(filtro, cliente, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/venda/obter-por-filtro-cliente.php',
        {
            filtro: filtro,
            cliente: cliente,
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
        '/representacoes/relatorio/pedido/venda/obter-por-filtro.php',
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

async function obterPorPeriodoCliente(inicio, fim, cliente, ordem) {
    let dateInicio = new Date(inicio + ' 05:00:59');
    let dateFim = new Date(fim + ' 05:00:59');

    if (dateInicio > dateFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/venda/obter-por-periodo-cliente.php',
            {
                inicio: inicio,
                fim : fim,
                cliente: cliente,
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

async function obterPorPeriodo(inicio, fim, ordem) {
    let dateInicio = new Date(inicio + ' 05:00:59');
    let dateFim = new Date(fim + ' 05:00:59');

    if (dateInicio > dateFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/venda/obter-por-periodo.php',
            {
                inicio: inicio,
                fim : fim,
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

async function obterPorCliente(cliente, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/venda/obter-por-cliente.php',
        {
            cliente: cliente,
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
    let inicio = dateFiltroDataInicio.value;
    let fim = dateFiltroDataFim.value;
    let cliente = Number.parseInt(selectCliente.value);
    let ordem = selectOrdem.value;

    if (filtro === '' && inicio === '' && fim === '' && cliente === 0) {
        await obter(ordem);
    } else {
        if (filtro !== '' && inicio !== '' && fim !== '' && cliente !== 0) {
            await obterPorFiltroPeriodoCliente(filtro, inicio, fim, cliente, ordem);
        } else {
            if (filtro !== '' && inicio !== '' && fim !== '' && cliente === 0) {
                await obterPorFiltroPeriodo(filtro, inicio, fim, ordem);
            } else {
                if (filtro !== '' && inicio === '' && fim === '' && cliente !== 0) {
                    await obterPorFiltroCliente(filtro, cliente, ordem);
                } else {
                    if (filtro !== '' && inicio === '' && fim === '' && cliente === 0) {
                        await obterPorFiltro(filtro, ordem);
                    } else {
                        if (filtro === '' && inicio !== '' && fim !== '' && cliente !== 0) {
                            await obterPorPeriodoCliente(inicio, fim, cliente, ordem);
                        } else {
                            if (filtro === '' && inicio !== '' && fim !== '' && cliente === 0) {
                                await obterPorPeriodo(inicio, fim, ordem);
                            } else {
                                if (filtro === '' && inicio === '' && fim === '' && cliente !== 0) {
                                    await obterPorCliente(cliente, ordem);
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
    let inicio = dateFiltroDataInicio.value;
    let fim = dateFiltroDataFim.value;
    let cliente = Number.parseInt(selectCliente.value);

    const guia = window.open(`/representacoes/relatorio/pedido/venda/emitir.php?filtro=${filtro}&inicio=${inicio}&fim=${fim}&cliente=${cliente}&ordem=1`, '_blank');
    guia.focus();
}