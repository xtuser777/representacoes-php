const txFiltro = document.getElementById("txFiltro");
const txDataInicio = document.getElementById("txDataInicio");
const txDataFim = document.getElementById("txDataFim");
const slOrdenar = document.getElementById("slOrdenar");
const selectComissao = document.getElementById("selectComissao");
const selectVendedor = document.getElementById("selectVendedor");
const slSituacao = document.getElementById("slSituacao");
const tbContas = document.getElementById("tbContas");
const tbodyContas = document.getElementById("tbodyContas");

function preencherTabela(dados) {
    let txt = "";
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
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.conta + '</td>\
                <td>' + this.descricao + '</td>\
                <td>' + this.parcela + '</td>\
                <td>'+ formatarValor(this.valor) +'</td>\
                <td>' + FormatarData(this.vencimento) + '</td>\
                <td>'+ formatarValor(this.valorPago) +'</td>\
                <td>' + ( (this.dataPagamento === "") ? "" : FormatarData(this.dataPagamento) ) + '</td>\
                <td>'+ sit +'</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="DETALHES" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="top" title="ESTORNAR" href="javascript:estornar(' + this.id + ')"></a></td>\
            </tr>';
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
        '/representacoes/controlar/contas/pagar/obter.php',
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
    let formas = get("/representacoes/controlar/contas/pagar/obter-formas.php");
    if (formas === null || formas.length === 0) {
        alert("Não existem formas de pagamento para contas a pagar cadastradas.");
        location.href = "../../../gerenciar/formapagamento/";
    }

    let vendedores = get('/representacoes/controlar/contas/pagar/obter-vendedores.php');
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

async function filtrar() {
    let filtro = txFiltro.value;
    let dataInicio = txDataInicio.value;
    let dataFim = txDataFim.value;
    let ordem = slOrdenar.value;
    let comissao = Number.parseInt(selectComissao.value);
    let vendedor = Number.parseInt(selectVendedor.value);
    let situacao = Number.parseInt(slSituacao.value);

    let data1 = new Date(dataInicio);
    let data2 = new Date(dataFim);

    if (filtro === "" && dataInicio === "" && dataFim === "" && comissao === 0 && situacao === 0) {
        await obter(ordem);
    } else {
        if (filtro !== "" && dataInicio !== "" && dataFim !== "" && comissao > 0 && situacao > 0) {

            if (data1 > data2) {
                mostraDialogo(
                    "A Data de Início deve ser menor que a data Fim do filtro.",
                    "warning",
                    3000
                );
            } else {
                if (vendedor > 0) {
                    let form = new FormData();
                    form.append("filtro", filtro);
                    form.append("dataInicio", dataInicio);
                    form.append("dataFim", dataFim);
                    form.append("comissao", comissao.toString());
                    form.append("vendedor", vendedor.toString());
                    form.append("situacao", situacao.toString());
                    form.append("ordem", ordem);

                    let res = await post(
                        '/representacoes/controlar/contas/pagar/obter-por-filtro-periodo-comissao-vendedor-situacao.php',
                        form
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
                } else {
                    let form = new FormData();
                    form.append("filtro", filtro);
                    form.append("dataInicio", dataInicio);
                    form.append("dataFim", dataFim);
                    form.append("comissao", comissao.toString());
                    form.append("situacao", situacao.toString());
                    form.append("ordem", ordem);

                    let res = await post(
                        '/representacoes/controlar/contas/pagar/obter-por-filtro-periodo-comissao-situacao.php',
                        form
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

        } else {
            if (filtro !== "" && dataInicio !== "" && dataFim !== "" && comissao > 0 && situacao === 0) {

                if (data1 > data2) {
                    mostraDialogo(
                        "A Data de Início deve ser menor que a data Fim do filtro.",
                        "warning",
                        3000
                    );
                } else {
                    if (vendedor > 0) {
                        let form = new FormData();
                        form.append('filtro', filtro);
                        form.append('dataInicio', dataInicio);
                        form.append('dataFim', dataFim);
                        form.append('comissao', comissao.toString());
                        form.append('vendedor', vendedor.toString());
                        form.append('ordem', ordem);

                        let res = await post(
                            '/representacoes/controlar/contas/pagar/obter-por-filtro-periodo-comissao-vendedor.php',
                            form
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
                    } else {
                        let form = new FormData();
                        form.append('filtro', filtro);
                        form.append('dataInicio', dataInicio);
                        form.append('dataFim', dataFim);
                        form.append('comissao', comissao.toString());
                        form.append('ordem', ordem);

                        let res = await post(
                            '/representacoes/controlar/contas/pagar/obter-por-filtro-periodo-comissao.php',
                            form
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

            } else {
                if (filtro === "" && dataInicio !== "" && dataFim !== "" && comissao > 0 && situacao > 0) {

                    if (data1 > data2) {
                        mostraDialogo(
                            "A Data de Início deve ser menor que a data Fim do filtro.",
                            "warning",
                            3000
                        );
                    } else {
                        if (vendedor > 0) {
                            let form = new FormData();
                            form.append('dataInicio', dataInicio);
                            form.append('dataFim', dataFim);
                            form.append('comissao', comissao.toString());
                            form.append('vendedor', vendedor.toString());
                            form.append('situacao', situacao.toString());
                            form.append('ordem', ordem);

                            let res = await post(
                                '/representacoes/controlar/contas/pagar/obter-por-periodo-comissao-vendedor-situacao.php',
                                form
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
                        } else {
                            let form = new FormData();
                            form.append('dataInicio', dataInicio);
                            form.append('dataFim', dataFim);
                            form.append('comissao', comissao.toString());
                            form.append('situacao', situacao.toString());
                            form.append('ordem', ordem);

                            let res = await post(
                                '/representacoes/controlar/contas/pagar/obter-por-periodo-comissao-situacao.php',
                                form
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

                } else {
                    if (filtro !== "" && dataInicio === "" && dataFim === "" && comissao > 0 && situacao > 0) {
                        if (vendedor > 0) {
                            let params = new FormData();
                            params.append('filtro', filtro);
                            params.append('comissao', comissao.toString());
                            params.append('vendedor', vendedor.toString());
                            params.append('situacao', situacao.toString());
                            params.append('ordem', ordem);

                            let res = await post(
                                '/representacoes/controlar/contas/pagar/obter-por-filtro-comissao-vendedor-situacao.php',
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
                        } else {
                            let params = new FormData();
                            params.append('filtro', filtro);
                            params.append('comissao', comissao.toString());
                            params.append('situacao', situacao.toString());
                            params.append('ordem', ordem);

                            let res = await post(
                                '/representacoes/controlar/contas/pagar/obter-por-filtro-comissao-situacao.php',
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
                    } else {
                        if (filtro !== "" && dataInicio === "" && dataFim === "" && comissao > 0 && situacao === 0) {
                            if (vendedor > 0) {
                                let params = new FormData();
                                params.append('filtro', filtro);
                                params.append('comissao', comissao.toString());
                                params.append('vendedor', vendedor.toString());
                                params.append('ordem', ordem);

                                let res = await post(
                                    '/representacoes/controlar/contas/pagar/obter-por-filtro-comissao-vendedor.php',
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
                            } else {
                                let params = new FormData();
                                params.append('filtro', filtro);
                                params.append('comissao', comissao.toString());
                                params.append('ordem', ordem);

                                let res = await post(
                                    '/representacoes/controlar/contas/pagar/obter-por-filtro-comissao.php',
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
                        } else {
                            if (filtro === "" && dataInicio === "" && dataFim === "" && comissao > 0 && situacao > 0) {
                                if (vendedor > 0) {
                                    let params = new FormData();
                                    params.append('comissao', comissao.toString());
                                    params.append('vendedor', vendedor.toString());
                                    params.append('situacao', situacao.toString());
                                    params.append('ordem', ordem);

                                    let res = await post(
                                        '/representacoes/controlar/contas/pagar/obter-por-comissao-vendedor-situacao.php',
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
                                } else {
                                    let params = new FormData();
                                    params.append('comissao', comissao.toString());
                                    params.append('situacao', situacao.toString());
                                    params.append('ordem', ordem);

                                    let res = await post(
                                        '/representacoes/controlar/contas/pagar/obter-por-comissao-situacao.php',
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
                            } else {
                                if (filtro === "" && dataInicio !== "" && dataFim !== "" && comissao > 0 && situacao === 0) {

                                    if (data1 > data2) {
                                        mostraDialogo(
                                            "A Data de Início deve ser menor que a data Fim do filtro.",
                                            "warning",
                                            3000
                                        );
                                    } else {
                                        if (vendedor > 0) {
                                            let form = new FormData();
                                            form.append('dataInicio', dataInicio);
                                            form.append('dataFim', dataFim);
                                            form.append('comissao', comissao.toString());
                                            form.append('vendedor', vendedor.toString());
                                            form.append('ordem', ordem);

                                            let res = await post(
                                                '/representacoes/controlar/contas/pagar/obter-por-periodo-comissao-vendedor.php',
                                                form
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
                                        } else {
                                            let form = new FormData();
                                            form.append('dataInicio', dataInicio);
                                            form.append('dataFim', dataFim);
                                            form.append('comissao', comissao.toString());
                                            form.append('ordem', ordem);

                                            let res = await post(
                                                '/representacoes/controlar/contas/pagar/obter-por-periodo-comissao.php',
                                                form
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

                                } else {
                                    if (filtro !== "" && dataInicio !== "" && dataFim !== "" && comissao === 0 && situacao > 0) {

                                        if (data1 > data2) {
                                            mostraDialogo(
                                                "A Data de Início deve ser menor que a data Fim do filtro.",
                                                "warning",
                                                3000
                                            );
                                        } else {
                                            let form = new FormData();
                                            form.append('filtro', filtro);
                                            form.append('dataInicio', dataInicio);
                                            form.append('dataFim', dataFim);
                                            form.append('situacao', situacao.toString());
                                            form.append('ordem', ordem);

                                            let res = await post(
                                                '/representacoes/controlar/contas/pagar/obter-por-filtro-periodo-situacao.php',
                                                form
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

                                    } else {
                                        if (filtro !== "" && dataInicio !== "" && dataFim !== "" && comissao === 0 && situacao === 0) {

                                            if (data1 > data2) {
                                                mostraDialogo(
                                                    "A Data de Início deve ser menor que a data Fim do filtro.",
                                                    "warning",
                                                    3000
                                                );
                                            } else {
                                                let form = new FormData();
                                                form.append('filtro', filtro);
                                                form.append('dataInicio', dataInicio);
                                                form.append('dataFim', dataFim);
                                                form.append('ordem', ordem);

                                                let res = await post(
                                                    '/representacoes/controlar/contas/pagar/obter-por-filtro-periodo.php',
                                                    form
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

                                        } else {
                                            if (filtro === "" && dataInicio !== "" && dataFim !== "" && comissao === 0 && situacao > 0) {

                                                if (data1 > data2) {
                                                    mostraDialogo(
                                                        "A Data de Início deve ser menor que a data Fim do filtro.",
                                                        "warning",
                                                        3000
                                                    );
                                                } else {
                                                    let form = new FormData();
                                                    form.append('dataInicio', dataInicio);
                                                    form.append('dataFim', dataFim);
                                                    form.append('situacao', situacao.toString());
                                                    form.append('ordem', ordem);

                                                    let res = await post(
                                                        '/representacoes/controlar/contas/pagar/obter-por-periodo-situacao.php',
                                                        form
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

                                            } else {
                                                if (filtro !== "" && dataInicio === "" && dataFim === "" && comissao === 0 && situacao > 0) {
                                                    let params = new FormData();
                                                    params.append('filtro', filtro);
                                                    params.append('situacao', situacao.toString());
                                                    params.append('ordem', ordem);

                                                    let res = await post(
                                                        '/representacoes/controlar/contas/pagar/obter-por-filtro-situacao.php',
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
                                                } else {
                                                    if (filtro !== "" && dataInicio === "" && dataFim === "" && comissao === 0 && situacao === 0) {
                                                        let params = new FormData();
                                                        params.append('filtro', filtro);
                                                        params.append('ordem', ordem);

                                                        let res = await post(
                                                            '/representacoes/controlar/contas/pagar/obter-por-filtro.php',
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
                                                    } else {
                                                        if (filtro === "" && dataInicio === "" && dataFim === "" && comissao === 0 && situacao > 0) {
                                                            let params = new FormData();
                                                            params.append('situacao', situacao.toString());
                                                            params.append('ordem', ordem);

                                                            let res = await post(
                                                                '/representacoes/controlar/contas/pagar/obter-por-situacao.php',
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
                                                        } else {
                                                            if (filtro === "" && dataInicio !== "" && dataFim !== "" && comissao === 0 && situacao === 0) {

                                                                if (data1 > data2) {
                                                                    mostraDialogo(
                                                                        "A Data de Início deve ser menor que a data Fim do filtro.",
                                                                        "warning",
                                                                        3000
                                                                    );
                                                                } else {
                                                                    let form = new FormData();
                                                                    form.append('dataInicio', dataInicio);
                                                                    form.append('dataFim', dataFim);
                                                                    form.append('ordem', ordem);

                                                                    let res = await post(
                                                                        '/representacoes/controlar/contas/pagar/obter-por-periodo.php',
                                                                        form
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

                                                            } else {
                                                                if (filtro === "" && dataInicio === "" && dataFim === "" && comissao > 0 && situacao === 0) {
                                                                    if (vendedor > 0) {
                                                                        let params = new FormData();
                                                                        params.append('comissao', comissao.toString());
                                                                        params.append('vendedor', vendedor.toString());
                                                                        params.append('ordem', ordem);

                                                                        let res = await post(
                                                                            '/representacoes/controlar/contas/pagar/obter-por-comissao-vendedor.php',
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
                                                                    } else {
                                                                        let params = new FormData();
                                                                        params.append('comissao', comissao.toString());
                                                                        params.append('ordem', ordem);

                                                                        let res = await post(
                                                                            '/representacoes/controlar/contas/pagar/obter-por-comissao.php',
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
                                                                } else {
                                                                    if (dataInicio === "") {
                                                                        mostraDialogo(
                                                                            "A Data de Início do filtro período deve ser preenchida.",
                                                                            "warning",
                                                                            3000
                                                                        );
                                                                    } else {
                                                                        if (dataFim === "") {
                                                                            mostraDialogo(
                                                                                "A Data Fim do filtro período deve ser preenchida.",
                                                                                "warning",
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
}

async function alterar(id) {
    let params = new FormData();
    params.append('id', id.toString());

    let res = await post(
        '/representacoes/controlar/contas/pagar/enviar.php',
        params
    );

    if (res.status) {
        window.location.href = '../../../controlar/contas/pagar/detalhes';
    } else {
        mostraDialogo(
            res.error.message + "<br />" +
            "Status: "+res.error.code+" "+res.error.status,
            "danger",
            3000
        );
    }
}

async function estornar(id) {
    bootbox.confirm({
        message: 'Confirma o estorno deste pagamento?',
        buttons: {
            confirm: {
                label: 'Sim',
                className: 'btn-success'
            },
            cancel: {
                label: 'Não',
                className: 'btn-danger'
            }
        },
        callback: async function (result) {
            if (result) {
                let params = new FormData();
                params.append('id', id.toString());

                let res = await post(
                    '/representacoes/controlar/contas/pagar/estornar.php',
                    params
                );

                if (res.status) {
                    mostraDialogo(
                        "<strong>Conta a pagar estornada com sucesso.</strong>" +
                        "<br /> O valor pago anteriormente foi estornado da parcela selecionada.",
                        "success",
                        3000
                    );
                    await obter();
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
    });
}