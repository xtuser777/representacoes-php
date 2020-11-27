const txFiltro = document.getElementById("txFiltro");
const txDataInicio = document.getElementById("txDataInicio");
const txDataFim = document.getElementById("txDataFim");
const slOrdenar = document.getElementById("slOrdenar");
const selectComissao = document.getElementById("selectComissao");
const selectRepresentacao = document.getElementById("selectRepresentacao");
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
                <td>'+ formatarValor(this.valor) +'</td>\
                <td>' + FormatarData(this.vencimento) + '</td>\
                <td>'+ formatarValor(this.valorRecebido) +'</td>\
                <td>' + ( (this.dataRecebimento === "") ? "" : FormatarData(this.dataRecebimento) ) + '</td>\
                <td>'+ sit +'</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="DETALHES" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="top" title="ESTORNAR" href="javascript:estornar(' + this.id + ')"></a></td>\
            </tr>';
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

function obter(ordem = "1") {
    $.ajax({
        type: "POST",
        url: "/representacoes/controlar/contas/receber/obter.php",
        data: {
            ordem: ordem
        },
        async: false,
        success:
            function (response) {
                preencherTabela(response);
            },
        error:
            function (xhr, status, thrown) {
                console.error(thrown);
                mostraDialogo(
                    "Ocorreu um erro ao tentar obter as contas. Consulte o console para mais detalhes.",
                    "danger",
                    3000
                );
            }
    });
}

$(document).ready(function (event) {
    let formas = get("/representacoes/controlar/contas/receber/obter-formas.php");
    if (formas === null || formas.length === 0) {
        alert("Não existem formas de pagamento para contas a pagar cadastradas.");
        location.href = "../../../gerenciar/formapagamento/";
    }

    let representacoes = get("/representacoes/controlar/contas/receber/obter-representacoes.php");
    if (representacoes !== null && representacoes.length > 0) {
        for (let i = 0; i < representacoes.length; i++) {
            let option = document.createElement("option");
            option.text = representacoes[i].pessoa.nomeFantasia;
            option.value = representacoes[i].id;

            selectRepresentacao.appendChild(option);
        }
    }

    selecionarComissao();

    obter();
});

async function filtrar() {
    let filtro = txFiltro.value;
    let dataInicio = txDataInicio.value;
    let dataFim = txDataFim.value;
    let ordem = slOrdenar.value;
    let comissao = Number.parseInt(selectComissao.value);
    let representacao = Number.parseInt(selectRepresentacao.value);
    let situacao = Number.parseInt(slSituacao.value);

    let data1 = new Date(dataInicio);
    let data2 = new Date(dataFim);

    if (filtro === "" && dataInicio === "" && dataFim === "" && comissao === 0 && situacao === 0) {
        obter(ordem);
    } else {
        if (filtro !== "" && dataInicio !== "" && dataFim !== "" && comissao > 0 && situacao > 0) {

            if (data1 > data2) {
                mostraDialogo(
                    "A Data de Início deve ser menor que a data Fim do filtro.",
                    "warning",
                    3000
                );
            } else {
                if (representacao > 0) {
                    let params = new FormData();
                    params.append('filtro', filtro);
                    params.append('dataInicio', dataInicio);
                    params.append('dataFim', dataFim);
                    params.append('comissao', comissao.toString());
                    params.append('representacao', representacao.toString());
                    params.append('situacao', situacao.toString());
                    params.append('ordem', ordem);

                    let res = await post(
                        '/representacoes/controlar/contas/receber/obter-por-filtro-periodo-comissao-representacao-situacao.php',
                        params
                    );

                    if (res.status) {
                        preencherTabela(res.response);
                    } else {
                        mostraDialogo(
                            res.error.message+"<br />" +
                            "Status: "+res.error.code+" "+res.error.status,
                            "danger",
                            3000
                        );
                    }
                } else {
                    let params = new FormData();
                    params.append('filtro', filtro);
                    params.append('dataInicio', dataInicio);
                    params.append('dataFim', dataFim);
                    params.append('comissao', comissao.toString());
                    params.append('situacao', situacao.toString());
                    params.append('ordem', ordem);

                    let res = await post(
                        '/representacoes/controlar/contas/receber/obter-por-filtro-periodo-comissao-situacao.php',
                        params
                    );

                    if (res.status) {
                        preencherTabela(res.response);
                    } else {
                        mostraDialogo(
                            res.error.message+"<br />" +
                            "Status: "+res.error.code+" "+res.error.status,
                            "danger",
                            3000
                        )
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
                    if (representacao > 0) {
                        let params = new FormData();
                        params.append('filtro', filtro);
                        params.append('dataInicio', dataInicio);
                        params.append('dataFim', dataFim);
                        params.append('comissao', comissao.toString());
                        params.append('representacao', representacao.toString());
                        params.append('ordem', ordem);

                        let res = await post(
                            '/representacoes/controlar/contas/receber/obter-por-filtro-periodo-comissao-representacao.php',
                            params
                        );

                        if (res.status) {
                            preencherTabela(res.response);
                        } else {
                            mostraDialogo(
                                res.error.message + "<br />" +
                                "Status: " + res.error.code + " " + res.error.status,
                                "danger",
                                3000
                            );
                        }
                    } else {
                        let params = new FormData();
                        params.append('filtro', filtro);
                        params.append('dataInicio', dataInicio);
                        params.append('dataFim', dataFim);
                        params.append('comissao', comissao.toString());
                        params.append('ordem', ordem);

                        let res = await post(
                            '/representacoes/controlar/contas/receber/obter-por-filtro-periodo-comissao.php',
                            params
                        );

                        if (res.status) {
                            preencherTabela(res.response);
                        } else {
                            mostraDialogo(
                                res.error.message + "<br />" +
                                "Status: " + res.error.code + " " + res.error.status,
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
                        if (representacao > 0) {
                            let params = new FormData();
                            params.append('dataInicio', dataInicio);
                            params.append('dataFim', dataFim);
                            params.append('comissao', comissao);
                            params.append('representacao', representacao.toString());
                            params.append('situacao', situacao.toString());
                            params.append('ordem', ordem);

                            let res = await post(
                                '/representacoes/controlar/contas/receber/obter-por-periodo-comissao-representacao-situacao.php',
                                params
                            );

                            if (res.status) {
                                preencherTabela(res.response);
                            } else {
                                mostraDialogo(
                                    res.error.message+"<br />" +
                                    "Status: "+res.error.code+" "+res.error.status,
                                    "danger",
                                    3000
                                );
                            }
                        } else {
                            let params = new FormData();
                            params.append('dataInicio', dataInicio);
                            params.append('dataFim', dataFim);
                            params.append('comissao', comissao.toString());
                            params.append('situacao', situacao.toString());
                            params.append('ordem', ordem);

                            let res = await post(
                                '/representacoes/controlar/contas/receber/obter-por-periodo-comissao-situacao.php',
                                params
                            );

                            if (res.status) {
                                preencherTabela(res.response);
                            } else {
                                mostraDialogo(
                                    res.error.message+"<br />" +
                                    "Status: "+res.error.code+" "+res.error.status,
                                    "danger",
                                    3000
                                );
                            }
                        }
                    }

                } else {
                    if (filtro !== "" && dataInicio === "" && dataFim === "" && comissao > 0 && situacao > 0) {
                        if (representacao > 0) {
                            let form = new FormData();
                            form.append('filtro', filtro);
                            form.append('comissao', comissao.toString());
                            form.append('representacao', representacao.toString());
                            form.append('situacao', situacao.toString());
                            form.append('ordem', ordem);

                            let res = await post(
                                '/representacoes/controlar/contas/receber/obter-por-filtro-comissao-representacao-situacao.php',
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
                            form.append('comissao', comissao.toString());
                            form.append('situacao', situacao.toString());
                            form.append('ordem', ordem);

                            let res = await post(
                                '/representacoes/controlar/contas/receber/obter-por-filtro-comissao-situacao.php',
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
                        if (filtro !== "" && dataInicio === "" && dataFim === "" && comissao > 0 && situacao === 0) {
                            if (representacao > 0) {
                                let params = new FormData();
                                params.append('filtro', filtro);
                                params.append('comissao', comissao.toString());
                                params.append('representacao', representacao.toString());
                                params.append('ordem', ordem);

                                let res = await post(
                                    '/representacoes/controlar/contas/receber/obter-por-filtro-comissao-representacao.php',
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
                                    '/representacoes/controlar/contas/receber/obter-por-filtro-comissao.php',
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
                                if (representacao > 0) {
                                    let params = new FormData();
                                    params.append('comissao', comissao.toString());
                                    params.append('representacao', representacao.toString());
                                    params.append('situacao', situacao.toString());
                                    params.append('ordem', ordem);

                                    let res = await post(
                                        '/representacoes/controlar/contas/receber/obter-por-comissao-representacao-situacao.php',
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
                                        '/representacoes/controlar/contas/receber/obter-por-comissao-situacao.php',
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
                                        if (representacao > 0) {
                                            let params = new FormData();
                                            params.append('dataInicio', dataInicio);
                                            params.append('dataFim', dataFim);
                                            params.append('comissao', comissao.toString());
                                            params.append('representacao', representacao.toString());
                                            params.append('ordem', ordem);

                                            let res = await post(
                                                '/representacoes/controlar/contas/receber/obter-por-periodo-comissao-representacao.php',
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
                                            params.append('dataInicio', dataInicio);
                                            params.append('dataFim', dataFim);
                                            params.append('comissao', comissao.toString());
                                            params.append('ordem', ordem);

                                            let res = await post(
                                                '/representacoes/controlar/contas/receber/obter-por-periodo-comissao.php',
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
                                            let params = new FormData();
                                            params.append('filtro', filtro);
                                            params.append('dataInicio', dataInicio);
                                            params.append('dataFim', dataFim);
                                            params.append('situacao', situacao.toString());
                                            params.append('ordem', ordem);

                                            let res = await post(
                                                '/representacoes/controlar/contas/receber/obter-por-filtro-periodo-situacao.php',
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
                                        if (filtro !== "" && dataInicio !== "" && dataFim !== "" && comissao === 0 && situacao === 0) {

                                            if (data1 > data2) {
                                                mostraDialogo(
                                                    "A Data de Início deve ser menor que a data Fim do filtro.",
                                                    "warning",
                                                    3000
                                                );
                                            } else {
                                                let params = new FormData();
                                                params.append('filtro', filtro);
                                                params.append('dataInicio', dataInicio);
                                                params.append('dataFim', dataFim);
                                                params.append('ordem', ordem);

                                                let res = await post(
                                                    '/representacoes/controlar/contas/receber/obter-por-filtro-periodo.php',
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
                                            if (filtro === "" && dataInicio !== "" && dataFim !== "" && comissao === 0 && situacao > 0) {

                                                if (data1 > data2) {
                                                    mostraDialogo(
                                                        "A Data de Início deve ser menor que a data Fim do filtro.",
                                                        "warning",
                                                        3000
                                                    );
                                                } else {
                                                    let params = new FormData();
                                                    params.append('dataInicio', dataInicio);
                                                    params.append('dataFim', dataFim);
                                                    params.append('comissao', comissao.toString());
                                                    params.append('situacao', situacao.toString());
                                                    params.append('ordem', ordem);

                                                    let res = await post(
                                                        '/representacoes/controlar/contas/receber/obter-por-periodo-comissao-situacao.php',
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
                                                if (filtro !== "" && dataInicio === "" && dataFim === "" && comissao === 0 && situacao > 0) {
                                                    let params = new FormData();
                                                    params.append('filtro', filtro);
                                                    params.append('situacao', situacao.toString());
                                                    params.append('ordem', ordem);

                                                    let res = await post(
                                                        '/representacoes/controlar/contas/receber/obter-por-filtro-situacao.php',
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
                                                            '/representacoes/controlar/contas/receber/obter-por-filtro.php',
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
                                                                '/representacoes/controlar/contas/receber/obter-por-situacao.php',
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
                                                                        let params = new FormData();
                                                                        params.append('dataInicio', dataInicio);
                                                                        params.append('dataFim', dataFim);
                                                                        params.append('ordem', ordem);

                                                                        let res = await post(
                                                                            '/representacoes/controlar/contas/receber/obter-por-periodo.php',
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
                                                                if (filtro === "" && dataInicio === "" && dataFim === "" && comissao > 0 && situacao === 0) {
                                                                    if (representacao > 0) {
                                                                        let params = new FormData();
                                                                        params.append('comissao', comissao.toString());
                                                                        params.append('representacao', representacao.toString());
                                                                        params.append('ordem', ordem);

                                                                        let res = await post(
                                                                            '/representacoes/controlar/contas/receber/obter-por-comissao-representacao.php',
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
                                                                            '/representacoes/controlar/contas/receber/obter-por-comissao.php',
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

function alterar(id) {
    let request = new XMLHttpRequest();
    request.open("POST", "/representacoes/controlar/contas/receber/enviar.php", false);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(encodeURI('id='+id));

    if (request.DONE === 4 && request.status === 200) {
        let res = JSON.parse(request.responseText);
        if (res !== null && res.length === 0) {
            window.location.href = "../../../controlar/contas/receber/detalhes";
        } else {
            mostraDialogo(
                res,
                "danger",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Erro na requisição da URL /representacoes/controlar/contas/receber/enviar.php. <br />" +
            "Status: "+request.status+" "+request.statusText,
            "danger",
            3000
        );
    }
}

function estornar(id) {
    bootbox.confirm({
        message: "Confirma o estorno deste recebimento?",
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
        callback: function (result) {
            if (result) {
                let request = new XMLHttpRequest();
                request.open("POST", "/representacoes/controlar/contas/receber/estornar.php", false);
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                request.send(encodeURI('id='+id));

                if (request.DONE === 4 && request.status === 200) {
                    let res = JSON.parse(request.responseText);
                    if (res !== null && res.length === 0) {
                        mostraDialogo(
                            "<strong>Conta a receber estornada com sucesso.</strong>" +
                            "<br /> O valor pago anteriormente foi estornado da parcela selecionada.",
                            "success",
                            3000
                        );
                        obter();
                    } else {
                        mostraDialogo(
                            res,
                            "danger",
                            3000
                        );
                    }
                } else {
                    mostraDialogo(
                        "Erro na requisição da URL.<br />" +
                        "Status: "+request.status+" "+request.statusText,
                        "danger",
                        3000
                    );
                }
            }
        }
    });
}