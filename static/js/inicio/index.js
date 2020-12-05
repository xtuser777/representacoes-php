const textFiltro = document.getElementById('textFiltro');
const dateEvento = document.getElementById('dateEvento');
const selectTipoPedido = document.getElementById('selectTipoPedido');
const tableEventos = document.getElementById('tableEventos');
const tbodyEventos = document.getElementById('tbodyEventos');

function preencheTabelaEventos() {
    let linhas = ``;

    for (let i = 0; i < eventos.length; i++) {
        let pedido = '';
        if (eventos[i].pedidoVenda) {
            pedido = eventos[i].pedidoVenda.descricao;
        } else {
            if (eventos[i].pedidoFrete) {
                pedido = eventos[i].pedidoFrete.descricao;
            }
        }

        linhas +=
            `<tr>
                <td>${eventos[i].descricao}</td>
                <td>${FormatarData(eventos[i].data)}</td>
                <td>${eventos[i].hora}</td>
                <td>${pedido}</td>
                <td>${eventos[i].autor.funcionario.pessoa.nome}</td>
            </tr>`;
    }

    tbodyEventos.innerHTML = linhas;
}

function obter() {
    eventos = get('/representacoes/inicio/obter.php');

    preencheTabelaEventos(eventos);
}

async function filtrar() {
    let filtro = textFiltro.value;
    let data = dateEvento.value;
    let tipo = Number.parseInt(selectTipoPedido.value);

    let date = new Date(data + ' 05:00:00');

    if (filtro === '' && data === '' && tipo === 0) {
        obter();
    } else {
        if (filtro !== '' && data !== '' && tipo > 0) {
            let res = await postJSON(
                '/representacoes/inicio/obter-por-filtro-data-tipo.php',
                {
                    filtro: filtro.trim(),
                    data: data,
                    tipo: tipo
                }
            );

            if (res.status) {
                preencheTabelaEventos(res.response);
            } else {
                mostraDialogo(
                    `Código: ${res.error.code}. <br />
                    Erro: ${res.error.message}`,
                    'danger',
                    3000
                );
            }
        } else {
            if (filtro !== '' && data === '' && tipo > 0) {
                let res = await postJSON(
                    '/representacoes/inicio/obter-por-filtro-tipo.php',
                    {
                        filtro: filtro.trim(),
                        tipo: tipo
                    }
                );

                if (res.status) {
                    preencheTabelaEventos(res.response);
                } else {
                    mostraDialogo(
                        `Código: ${res.error.code}. <br />
                        Erro: ${res.error.message}`,
                        'danger',
                        3000
                    );
                }
            } else {
                if (filtro === '' && data !== '' && tipo > 0) {
                    let res = await postJSON(
                        '/representacoes/inicio/obter-por-data-tipo.php',
                        {
                            data: data,
                            tipo: tipo
                        }
                    );

                    if (res.status) {
                        preencheTabelaEventos(res.response);
                    } else {
                        mostraDialogo(
                            `Código: ${res.error.code}. <br />
                            Erro: ${res.error.message}`,
                            'danger',
                            3000
                        );
                    }
                } else {
                    if (filtro === '' && data === '' && tipo > 0) {
                        let res = await postJSON(
                            '/representacoes/inicio/obter-por-tipo.php',
                            {
                                tipo: tipo
                            }
                        );

                        if (res.status) {
                            preencheTabelaEventos(res.response);
                        } else {
                            mostraDialogo(
                                `Código: ${res.error.code}. <br />
                                Erro: ${res.error.message}`,
                                'danger',
                                3000
                            );
                        }
                    } else {
                        if (filtro !== '' && data !== '' && tipo === 0) {
                            let res = await postJSON(
                                '/representacoes/inicio/obter-por-filtro-data.php',
                                {
                                    filtro: filtro.trim(),
                                    data: data
                                }
                            );

                            if (res.status) {
                                preencheTabelaEventos(res.response);
                            } else {
                                mostraDialogo(
                                    `Código: ${res.error.code}. <br />
                                    Erro: ${res.error.message}`,
                                    'danger',
                                    3000
                                );
                            }
                        } else {
                            if (filtro !== '' && data === '' && tipo === 0) {
                                let res = await postJSON(
                                    '/representacoes/inicio/obter-por-filtro.php',
                                    {
                                        filtro: filtro.trim()
                                    }
                                );

                                if (res.status) {
                                    preencheTabelaEventos(res.response);
                                } else {
                                    mostraDialogo(
                                        `Código: ${res.error.code}. <br />
                                        Erro: ${res.error.message}`,
                                        'danger',
                                        3000
                                    );
                                }
                            } else {
                                if (filtro === '' && data !== '' && tipo === 0) {
                                    let res = await postJSON(
                                        '/representacoes/inicio/obter-por-data.php',
                                        {
                                            data: data
                                        }
                                    );

                                    if (res.status) {
                                        preencheTabelaEventos(res.response);
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
                        }
                    }
                }
            }
        }
    }
}

function emitirPdf() {
    let filtro = textFiltro.value;
    let data = dateEvento.value;
    let tipo = Number.parseInt(selectTipoPedido.value);

    const guia = window.open(`/representacoes/inicio/emitir.php?filtro=${filtro}&data=${data}&tipo=${tipo}`, '_blank');
    guia.focus();
}

function carregarPagina() {
    obter();
}

$(document).ready(carregarPagina());