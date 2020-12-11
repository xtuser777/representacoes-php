const textFiltro = document.getElementById("textFiltro");
const dateFiltroDataInicio = document.getElementById("dateFiltroDataInicio");
const dateFiltroDataFim = document.getElementById("dateFiltroDataFim");
const selectStatus = document.getElementById('selectStatus');
const selectCliente = document.getElementById('selectCliente');
const selectOrdem = document.getElementById("selectOrdem");
const tablePedidos = document.getElementById("tablePedidos");
const tbodyPedidos = document.getElementById("tbodyPedidos");

function preencherTabela(dados) {
    let txt = ``;

    $.each(dados, function () {
        let cliente = this.cliente.tipo ===1
            ? this.cliente.pessoaFisica.nome
            : this.cliente.pessoaJuridica.nomeFantasia;

        txt +=
            `<tr>
                <td class="hidden">${this.id}</td>
                <td>${this.descricao}</td>
                <td>${cliente}</td>
                <td>${this.motorista.pessoa.nome}</td>
                <td>${FormatarData(this.data)}</td>
                <td>${this.destino.nome}/${this.destino.estado.sigla}</td>
                <td>${this.autor.funcionario.pessoa.nome}</td>
                <td>${this.formaPagamentoFrete.descricao}</td>
                <td>${this.status.status.descricao}</td>
                <td>${formatarValor(this.valor)}</td>
            </tr>`;
    });
    $(tbodyPedidos).html(txt);
}

async function obter(ordem = '1') {
    let res = await postJSON(
        '/representacoes/pedido/frete/obter.php',
        { ordem: ordem }
    );

    if (res.status) {
        preencherTabela(res.response);
    } else {
        mostraDialogo(
            res.error.message,
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

    let status = get('/representacoes/pedido/frete/obter-status.php');
    if (status !== null && status.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < status.length; i++) {
            options +=
                `<option value="${status[i].id}">${status[i].descricao}</option>`;
        }

        selectStatus.innerHTML = options;
    }

    let clientes = get('/representacoes/relatorio/pedido/frete/obter-clientes.php');
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

async function obterPorFiltroPeriodoStatusCliente(filtro, inicio, fim, status, cliente, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/frete/obter-por-filtro-periodo-status-cliente.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim: fim,
                status: status,
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

async function obterPorFiltroPeriodoStatus(filtro, inicio, fim, status, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/frete/obter-por-filtro-periodo-status.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim: fim,
                status: status,
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

async function obterPorFiltroPeriodoCliente(filtro, inicio, fim, cliente, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/frete/obter-por-filtro-periodo-cliente.php',
            {
                filtro: filtro,
                inicio: inicio,
                fim: fim,
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
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/frete/obter-por-filtro-periodo.php',
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

async function obterPorPeriodoStatusCliente(inicio, fim, status, cliente, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/frete/obter-por-periodo-status-cliente.php',
            {
                inicio: inicio,
                fim: fim,
                status: status,
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

async function obterPorPeriodoStatus(filtro, inicio, fim, status, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/frete/obter-por-filtro-periodo-status.php',
            {
                inicio: inicio,
                fim: fim,
                status: status,
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

async function obterPorPeriodoCliente(inicio, fim, cliente, ordem) {
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/frete/obter-por-periodo-cliente.php',
            {
                inicio: inicio,
                fim: fim,
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
    let dataInicio = new Date(inicio + ' 05:00:59');
    let dataFim = new Date(fim + ' 05:00:59');

    if (dataInicio > dataFim) {
        mostraDialogo(
            'a data de início deve ser menor ou igual a data final.',
            'danger',
            3000
        );
    } else {
        let res = await postJSON(
            '/representacoes/relatorio/pedido/frete/obter-por-periodo.php',
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

async function obterPorFiltroStatusCliente(filtro, status, cliente, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/frete/obter-por-filtro-status-cliente.php',
        {
            filtro: filtro,
            status: status,
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

async function obterPorFiltroStatus(filtro, status, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/frete/obter-por-filtro-status.php',
        {
            filtro: filtro,
            status: status,
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

async function obterPorFiltroCliente(filtro, cliente, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/frete/obter-por-filtro-cliente.php',
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
        '/representacoes/relatorio/pedido/frete/obter-por-filtro.php',
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

async function obterPorStatusCliente(status, cliente, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/frete/obter-por-status-cliente.php',
        {
            status: status,
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

async function obterPorStatus(status, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/frete/obter-por-status.php',
        {
            status: status,
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

async function obterPorCliente(cliente, ordem) {
    let res = await postJSON(
        '/representacoes/relatorio/pedido/frete/obter-por-cliente.php',
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
    let status = Number.parseInt(selectStatus.value);
    let cliente = Number.parseInt(selectCliente.value);
    let ordem = selectOrdem.value;

    if (filtro === '' && inicio === '' && fim === '' && status === 0 && cliente === 0) {
        await obter(ordem);
    } else {
        if (filtro !== '' && inicio !== '' && fim !== '' && status > 0 && cliente > 0) {
            await obterPorFiltroPeriodoStatusCliente(filtro, inicio, fim, status, cliente, ordem);
        } else {
            if (filtro !== '' && inicio !== '' && fim !== '' && status > 0 && cliente === 0) {
                await obterPorFiltroPeriodoStatus(filtro, inicio, fim, status, ordem);
            } else {
                if (filtro !== '' && inicio !== '' && fim !== '' && status === 0 && cliente > 0) {
                    await obterPorFiltroPeriodoCliente(filtro, inicio, fim, cliente,ordem);
                } else {
                    if (filtro !== '' && inicio !== '' && fim !== '' && status === 0 && cliente === 0) {
                        await obterPorFiltroPeriodo(filtro, inicio, fim, ordem);
                    } else {
                        if (filtro !== '' && inicio === '' && fim === '' && status > 0 && cliente > 0) {
                            await obterPorFiltroStatusCliente(filtro, status, cliente, ordem);
                        } else {
                            if (filtro !== '' && inicio === '' && fim === '' && status > 0 && cliente === 0) {
                                await obterPorFiltroStatus(filtro, status, ordem);
                            } else {
                                if (filtro !== '' && inicio === '' && fim === '' && status === 0 && cliente > 0) {
                                    await obterPorFiltroCliente(filtro, cliente, ordem);
                                } else {
                                    if (filtro !== '' && inicio === '' && fim === '' && status === 0 && cliente === 0) {
                                        await obterPorFiltro(filtro, ordem);
                                    } else {
                                        if (filtro === '' && inicio !== '' && fim !== '' && status > 0 && cliente > 0) {
                                            await obterPorPeriodoStatusCliente(inicio, fim, status, cliente, ordem);
                                        } else {
                                            if (filtro === '' && inicio !== '' && fim !== '' && status > 0 && cliente === 0) {
                                                await obterPorPeriodoStatus(inicio, fim, status, ordem);
                                            } else {
                                                if (filtro === '' && inicio !== '' && fim !== '' && status === 0 && cliente > 0) {
                                                    await obterPorPeriodoCliente(inicio, fim, cliente, ordem);
                                                } else {
                                                    if (filtro === '' && inicio !== '' && fim !== '' && status === 0 && cliente === 0) {
                                                        await obterPorPeriodo(inicio, fim, ordem);
                                                    } else {
                                                        if (filtro === '' && inicio === '' && fim === '' && status > 0 && cliente > 0) {
                                                            await obterPorStatusCliente(status, cliente, ordem);
                                                        } else {
                                                            if (filtro === '' && inicio === '' && fim === '' && status > 0 && cliente === 0) {
                                                                await obterPorStatus(status, ordem);
                                                            } else {
                                                                if (filtro === '' && inicio === '' && fim === '' && status === 0 && cliente > 0) {
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
    let status = Number.parseInt(selectStatus.value);
    let cliente = Number.parseInt(selectCliente.value);

    const guia = window.open(`/representacoes/relatorio/pedido/frete/emitir.php?filtro=${filtro}&inicio=${inicio}&fim=${fim}&status=${status}&cliente=${cliente}&ordem=1`, '_blank');
    guia.focus();
}