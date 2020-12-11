const textFiltro = document.getElementById("textFiltro");
const dateFiltroDataInicio = document.getElementById("dateFiltroDataInicio");
const dateFiltroDataFim = document.getElementById("dateFiltroDataFim");
const selectStatus = document.getElementById('selectStatus');
const selectOrdem = document.getElementById("selectOrdem");
const tablePedidos = document.getElementById("tablePedidos");
const tbodyPedidos = document.getElementById("tbodyPedidos");

function preencherTabela(dados) {
    let txt = "";

    $.each(dados, function () {
        let cliente = this.cliente.tipo ===1
            ? this.cliente.pessoaFisica.nome
            : this.cliente.pessoaJuridica.nomeFantasia;

        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.descricao + '</td>\
                <td>' + cliente + '</td>\
                <td>' + FormatarData(this.data) + '</td>\
                <td>'+ this.autor.funcionario.pessoa.nome +'</td>\
                <td>'+ this.formaPagamentoFrete.descricao +'</td>\
                <td>'+ this.status.status.descricao +'</td>\
                <td>'+ formatarValor(this.valor) +'</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR STATUS" href="javascript:alterar(' + this.id + ')"></a></td>\
            </tr>';
    });
    $(tbodyPedidos).html(txt);
}

async function obter(ordem = '1') {
    let res = await postJSON(
        '/representacoes/pedido/status/obter.php',
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
    let pedidos = get("/representacoes/pedido/frete/obter.php");
    if (pedidos === null || pedidos.length === 0) {
        alert("Não existem pedidos de frete cadastrados!");
        location.href = "../../inicio";
    }

    let status = get('/representacoes/pedido/status/obter-status.php');
    if (status !== null && status.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < status.length; i++) {
            options +=
                `<option value="${status[i].id}">${status[i].descricao}</option>`;
        }

        selectStatus.innerHTML = options;
    }

    await obter();
});

async function filtrar() {
    let filtro = textFiltro.value;
    let dataInicio = dateFiltroDataInicio.value;
    let dataFim = dateFiltroDataFim.value;
    let status = Number.parseInt(selectStatus.value);
    let ordem = selectOrdem.value;

    let inicio = new Date(dataInicio + " 12:00:01");
    let fim = new Date(dataFim + " 12:00:01");

    if (filtro === "" && dataInicio === "" && dataFim === "" && status === 0) {
        await obter(ordem);
    } else {
        if (filtro !== "" && dataInicio !== "" && dataFim !== "" && status > 0) {
            if (inicio > fim) {
                mostraDialogo(
                    "A data de início deve ser igual ou menor que a data final.",
                    "danger",
                    3000
                );
            } else {
                let res = await postJSON(
                    '/representacoes/pedido/status/obter-por-filtro-periodo-status.php',
                    {
                        filtro: filtro,
                        inicio: dataInicio,
                        fim: dataFim,
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
        } else {
            if (filtro !== "" && dataInicio === "" && dataFim === "" && status > 0) {
                let res = await postJSON(
                    '/representacoes/pedido/status/obter-por-filtro-status.php',
                    {
                        filtro: filtro,
                        status: status,
                        ordem: ordem
                    }
                )

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
            } else {
                if (filtro === "" && dataInicio !== "" && dataFim !== "" && status > 0) {
                    if (inicio > fim) {
                        mostraDialogo(
                            "A data de início deve ser igual ou menor que a data final.",
                            "danger",
                            3000
                        );
                    } else {
                        let res = await postJSON(
                            '/representacoes/pedido/status/obter-por-periodo-status.php',
                            {
                                inicio: dataInicio,
                                fim: dataFim,
                                status: status,
                                ordem: ordem
                            }
                        )

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
                } else {
                    if (filtro === "" && dataInicio === "" && dataFim === "" && status > 0) {
                        let res = await postJSON(
                            '/representacoes/pedido/status/obter-por-status.php',
                            {
                                status: status,
                                ordem: ordem
                            }
                        )

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
                    } else {
                        if (filtro !== "" && dataInicio !== "" && dataFim !== "" && status === 0) {
                            if (inicio > fim) {
                                mostraDialogo(
                                    "A data de início deve ser igual ou menor que a data final.",
                                    "danger",
                                    3000
                                );
                            } else {
                                let res = await postJSON(
                                    '/representacoes/pedido/status/obter-por-filtro-periodo.php',
                                    {
                                        filtro: filtro,
                                        inicio: dataInicio,
                                        fim: dataFim,
                                        ordem: ordem
                                    }
                                )

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
                        } else {
                            if (filtro !== "" && dataInicio === "" && dataFim === "" && status === 0) {
                                let res = await postJSON(
                                    '/representacoes/pedido/status/obter-por-filtro.php',
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
                            } else {
                                if (filtro === "" && dataInicio !== "" && dataFim !== "" && status === 0) {
                                    if (inicio > fim) {
                                        mostraDialogo(
                                            "A data de início deve ser igual ou menor que a data final.",
                                            "danger",
                                            3000
                                        );
                                    } else {
                                        let res = await postJSON(
                                            '/representacoes/pedido/status/obter-por-periodo.php',
                                            {
                                                inicio: dataInicio,
                                                fim: dataFim,
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
                                } else {
                                    if (dataInicio === "" || dataFim === "") {
                                        mostraDialogo(
                                            "A data de início e a data final devem estar preenchidas.",
                                            "danger",
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

async function alterar(id) {
    let res = await postJSON(
        '/representacoes/pedido/status/enviar.php',
        { id: id}
    );

    if (res.status) {
        window.location.href = "../../pedido/status/alterar";
    } else {
        mostraDialogo(
            `Código: ${res.error.code}. <br />
            Erro: ${res.error.message}`,
            'danger',
            3000
        );
    }
}