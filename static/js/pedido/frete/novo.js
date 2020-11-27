const selectOrcFrete = document.getElementById("selectOrcamentoFrete");
const selectPedVenda = document.getElementById('selectPedidoVenda');
const selectRepresentacao = document.getElementById("selectRepresentacao");
const textDesc = document.getElementById("textDescricao");
const selectCidade = document.getElementById("selectCidadeDestino");
const selectEstado = document.getElementById("selectEstadoDestino");
const tbodyItens = document.getElementById("tbodyItens");
const tbodyEtapas = document.getElementById("tbodyEtapas");
const selectTipoCam = document.getElementById("selectTipoCaminhao");
const selectProprietario = document.getElementById('selectProprietario');
const selectCaminhao = document.getElementById('selectCaminhao');
const textDistancia = document.getElementById("textDistancia");
const textValorMotorista = document.getElementById('textValorMotorista');
const textValorAdiantamento = document.getElementById('textValorAdiantamento');
const selectFormaAdiantamento = document.getElementById('selectFormaAdiantamento');
const textPesoItens = document.getElementById("textPesoProdutos");
const textValorFrete = document.getElementById("textValorFrete");
const selectForma = document.getElementById('selectForma');
const dateEntrega = document.getElementById("dateEntrega");

let tipos = [];

let piso = 0.0;

let itens = [];

let etapas = [];

let erroDesc = true;
let erroEstado = true;
let erroCidade = true;
let erroTipo = true;
let erroProprietario = true;
let erroCaminhao = true;
let erroDistancia = true;
let erroValorMotorista = true;
let erroFormaAdiantamento = true;
let erroValor = true;
let erroFormaPagamento = true;
let erroEntrega = true;

function textDescBlur() {
    let desc = textDesc.value.toString();
    if (desc.trim().length === 0) {
        erroDesc = true;
        $("#msdesc").html("<span class='label label-danger'>A Descrição do pedido precisa ser preenchida.</span>");
    } else {
        erroDesc = false;
        $("#msdesc").html("");
    }
}

function limparSelectTipo() {
    for (let i = selectTipoCam.childElementCount - 1; i > 0; i--) {
        selectTipoCam.children.item(i).remove();
    }
}

function adicionarEtapa(item) {
    let i;
    let achou = false;

    for (i = 0; i < etapas.length; i++) {
        if (etapas[i].representacao.id === item.produto.representacao.id) {
            achou = true;
            etapas[i].carga = truncate(etapas[i].carga + (item.produto.peso * item.quantidade));
        }
    }

    if (i === etapas.length && !achou) {
        etapas.push({
            ordem: (etapas.length + 1),
            representacao: {
                id: item.produto.representacao.id,
                nomeFantasia: item.produto.representacao.nomeFantasia,
                unidade: item.produto.representacao.unidade
            },
            carga: (item.produto.peso * item.quantidade),
            status: 1
        });
    }

    preencheTabelaEtapas(etapas);
}

function deletarEtapa(item) {
    let achou = false;

    for (let i = 0; i < etapas.length && !achou; i++) {
        if (etapas[i].representacao.id === item.produto.representacao.id && etapas[i].carga > (item.produto.peso * item.quantidade)) {
            achou = true;
            etapas[i].carga = truncate(etapas[i].carga - (item.produto.peso * item.quantidade));
        } else {
            if (etapas[i].representacao.id === item.produto.representacao.id) {
                achou = true;
                let tmp = [];

                for (let x = 0; x < etapas.length; x++) {
                    if (etapas[x].representacao.id !== item.produto.representacao.id) {
                        tmp.push(etapas[x]);
                    }
                }

                etapas = tmp;
            }
        }
    }

    preencheTabelaEtapas(etapas);
}

async function selectOrcFreteChange() {
    let orc = Number.parseInt(selectOrcFrete.value);
    if (orc > 0) {
        let orcamento = get('/representacoes/pedido/frete/novo/obter-orcamento.php?id='+orc);
        selectPedVenda.value = 0;
        selectPedVenda.disabled = true;
        selectRepresentacao.value = 0;
        selectRepresentacao.disabled = true;
        itens = [];
        etapas = [];
        tipos = [];
        limparSelectTipo();
        textPesoItens.value = '0,0';
        piso = 0.0;
        textValorFrete.value = formatarValor(piso);

        textDesc.value = orcamento.descricao;

        let params = new FormData();
        params.append('orc', orc.toString());

        let res = await post(
            '/representacoes/pedido/frete/novo/item/obter-por-orcamento.php',
            params
        );

        if (res.status) {
            let peso = 0.0;
            for (let i = 0; i < res.response.length; i++) {
                peso += res.response[i].peso;

                let params1 = new FormData();
                params1.append('item', res.response[i].produto.id);

                let res1 = await post(
                    '/representacoes/pedido/frete/novo/item/obter-tipos-por-item.php',
                    params1
                );

                if (res1.status) {
                    if (tipos.length === 0) {
                        tipos = res1.response;
                        for (let i = 0; i < res1.response.length; i++) {
                            let option = document.createElement("option");
                            option.value = res1.response[i].id;
                            option.text = res1.response[i].descricao;
                            selectTipoCam.appendChild(option);
                        }
                    } else {
                        let tmp = [];
                        limparSelectTipo();

                        for (let i = 0; i < res1.response.length; i++) {
                            if (tipos.findIndex((element) => { return (element.id === res1.response[i].id); }) !== -1) {
                                tmp.push(res1.response[i]);
                                let option = document.createElement("option");
                                option.value = res1.response[i].id;
                                option.text = res1.response[i].descricao;
                                selectTipoCam.appendChild(option);
                            }
                        }
                        tipos = tmp;
                    }
                } else {
                    mostraDialogo(
                        res.error.message + "<br />" +
                        "Status: "+res.error.code+" "+res.error.status,
                        "danger",
                        3000
                    );
                }

                let item = {
                    produto: {
                        id: res.response[i].produto.id,
                        descricao: res.response[i].produto.descricao,
                        peso: res.response[i].produto.peso,
                        estado: res.response[i].produto.representacao.pessoa.contato.endereco.cidade.estado.id,
                        representacao: {
                            id: res.response[i].produto.representacao.id,
                            nomeFantasia: res.response[i].produto.representacao.pessoa.nomeFantasia,
                            unidade: res.response[i].produto.representacao.unidade
                        }
                    },
                    quantidade: Number(res.response[i].quantidade),
                    peso: res.response[i].peso
                };

                adicionarEtapa(item);

                itens.push(item);
            }
            preencheTabelaItens(itens);

            textPesoItens.value = formatarPeso(peso);
        } else {
            mostraDialogo(
                res.error.message + "<br />" +
                "Status: "+res.error.code+" "+res.error.status,
                "danger",
                3000
            );
        }

        if (orcamento.representacao) {
            selectRepresentacao.value = orcamento.representacao.id;
        } else {
            $("#button_clr_itens").prop("disabled", true);
            $("#button_add_item").prop("disabled", true);
        }
        selectTipoCam.value = orcamento.tipoCaminhao.id;
        selectTipoCaminhaoChange();
        textDistancia.value = orcamento.distancia;
        selectEstado.value = orcamento.destino.estado.id;
        selectEstadoChange();
        selectCidade.value = orcamento.destino.id;
        textValorFrete.value = orcamento.valor;
        dateEntrega.value = orcamento.entrega;
    } else {
        selectPedVenda.disabled = false;
        selectRepresentacao.disabled = false;
        itens = [];
        etapas = [];
        preencheTabelaItens(itens);
        preencheTabelaEtapas(etapas);
        $("#button_clr_itens").prop("disabled", false);
        $("#button_add_item").prop("disabled", false);
        tipos = [];
        limparSelectTipo();
        selectTipoCam.value = 0;
        selectTipoCaminhaoChange();
        textDistancia.value = 0;
        selectEstado.value = 0;
        selectEstadoChange();
        textPesoItens.value = '0,0';
        piso = 0.0;
        textValorFrete.value = formatarValor(piso);
    }
}

async function selectPedVendaChange() {
    let ven = Number.parseInt(selectPedVenda.value);
    if (ven > 0) {
        let venda = get('/representacoes/pedido/frete/novo/obter-venda.php?id='+ven);
        selectOrcFrete.value = 0;
        selectOrcFrete.disabled = true;
        selectRepresentacao.value = 0;
        selectRepresentacao.disabled = true;
        itens = [];
        etapas = [];
        tipos = [];
        $("#button_clr_itens").prop("disabled", true);
        $("#button_add_item").prop("disabled", true);
        limparSelectTipo();
        textPesoItens.value = '0,0';
        piso = 0.0;
        textValorFrete.value = formatarValor(piso);

        textDesc.value = venda.descricao;

        let params = new FormData();
        params.append('venda', ven.toString());

        let res = await post(
            '/representacoes/pedido/frete/novo/item/obter-por-venda.php',
            params
        );

        if (res.status) {
            let peso = 0.0;
            for (let i = 0; i < res.response.length; i++) {
                peso += res.response[i].peso;

                let params1 = new FormData();
                params1.append('item', res.response[i].produto.id);

                let res1 = await post(
                    '/representacoes/pedido/frete/novo/item/obter-tipos-por-item.php',
                    params1
                );

                if (res1.status) {
                    if (tipos.length === 0) {
                        tipos = res1.response;
                        for (let i = 0; i < res1.response.length; i++) {
                            let option = document.createElement("option");
                            option.value = res1.response[i].id;
                            option.text = res1.response[i].descricao;
                            selectTipoCam.appendChild(option);
                        }
                    } else {
                        let tmp = [];
                        limparSelectTipo();

                        for (let i = 0; i < res1.response.length; i++) {
                            if (tipos.findIndex((element) => { return (element.id === res1.response[i].id); }) !== -1) {
                                tmp.push(res1.response[i]);
                                let option = document.createElement("option");
                                option.value = res1.response[i].id;
                                option.text = res1.response[i].descricao;
                                selectTipoCam.appendChild(option);
                            }
                        }
                        tipos = tmp;
                    }
                } else {
                    mostraDialogo(
                        res.error.message + "<br />" +
                        "Status: "+res.error.code+" "+res.error.status,
                        "danger",
                        3000
                    );
                }

                let item = {
                    produto: {
                        id: res.response[i].produto.id,
                        descricao: res.response[i].produto.descricao,
                        peso: res.response[i].produto.peso,
                        estado: res.response[i].produto.representacao.pessoa.contato.endereco.cidade.estado.id,
                        representacao: {
                            id: res.response[i].produto.representacao.id,
                            nomeFantasia: res.response[i].produto.representacao.pessoa.nomeFantasia,
                            unidade: res.response[i].produto.representacao.unidade
                        }
                    },
                    quantidade: Number(res.response[i].quantidade),
                    peso: res.response[i].peso
                };

                adicionarEtapa(item);

                itens.push(item);
            }
            preencheTabelaItens(itens);

            textPesoItens.value = formatarPeso(peso);
        } else {
            mostraDialogo(
                res.error.message + "<br />" +
                "Status: "+res.error.code+" "+res.error.status,
                "danger",
                3000
            );
        }

        selectEstado.value = venda.destino.estado.id;
        selectEstadoChange();
        selectCidade.value = venda.destino.id;
    } else {
        selectOrcFrete.disabled = false;
        selectRepresentacao.disabled = false;
        itens = [];
        etapas = [];
        preencheTabelaItens(itens);
        preencheTabelaEtapas(etapas);
        $("#button_clr_itens").prop("disabled", false);
        $("#button_add_item").prop("disabled", false);
        tipos = [];
        limparSelectTipo();
        selectTipoCam.value = 0;
        selectTipoCaminhaoChange();
        textDistancia.value = 0;
        selectEstado.value = 0;
        selectEstadoChange();
        textPesoItens.value = '0,0';
        piso = 0.0;
        textValorFrete.value = formatarValor(piso);
    }
}

function selectRepresentacaoChange() {
    let rep = Number.parseInt($(selectRepresentacao).val());
    selectPedVenda.disabled = selectOrcFrete.disabled = rep !== 0;

    let nome = selectRepresentacao[rep].innerText.trim();

    let i = 0;
    while (i < itens.length && nome.startsWith(itens[i].produto.representacao.nomeFantasia))
        i++;

    if (i < itens.length) {
        bootbox.confirm({
            message: "Confirma a seleção da representação? <br/>Ao confirmar esta ação, os itens e seus valores atrelados serão zerados",
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
                    itens = [];
                    etapas = [];
                    preencheTabelaItens(itens);
                    preencheTabelaEtapas(etapas);
                    tipos = [];
                    limparSelectTipo();
                    textPesoItens.value = "0,0";
                    piso = 0.0;
                    textValorFrete.value = formatarValor(piso);
                } else {
                    selectRepresentacao.value = itens[i].produto.representacao.id;
                    selectRepresentacaoChange();
                }
            }
        });
    }
}

function selectEstadoBlur() {
    let estado = selectEstado.value.toString();
    if (estado === "0") {
        erroEstado = true;
        $("#msest").html("<span class='label label-danger'>O Estado de destino precisa ser preenchido.</span>");
    } else {
        erroEstado = false;
        $("#msest").html("");
    }
}

function selectCidadeBlur() {
    let cidade = selectCidade.value.toString();
    if (cidade === "0") {
        erroCidade = true;
        $("#mscid").html("<span class='label label-danger'>A Cidade de destino precida ser preenchida.</span>");
    } else {
        erroCidade = false;
        $("#mscid").html("");
    }
}

function limparEstados() {
    for (let i = selectEstado.childElementCount - 1; i > 0; i--) {
        selectEstado.children.item(i).remove();
    }
}

function limparCidades() {
    for (let i = selectCidade.childElementCount - 1; i > 0; i--) {
        selectCidade.children.item(i).remove();
    }
}

function carregarCidades() {
    let cidades = [];

    $.ajax({
        type: 'POST',
        url: '/representacoes/cidade/obter-por-estado.php',
        data: {
            estado: selectEstado.value
        },
        async: false,
        success: function (response) {
            cidades = response;
        },
        error: function (err) {
            mostraDialogo(
                "<strong>Ocorreu um problema ao se comunicar com o servidor...</strong>" +
                "<br/>Um problema no servidor impediu sua comunicação...",
                "danger",
                2000
            );
        }
    });

    limparCidades();
    if (cidades !== "") {
        for (let i = 0; i < cidades.length; i++) {
            let option = document.createElement("option");
            option.value = cidades[i].id;
            option.text = cidades[i].nome;
            selectCidade.appendChild(option);
        }
    }
}

function selectEstadoChange() {
    if (selectEstado.value === "0") {
        limparCidades();
        selectCidade.disabled = true;
    } else {
        carregarCidades();
        selectCidade.disabled = false;
    }
}

function selectTipoCaminhaoBlur() {
    let tipo = Number.parseInt(selectTipoCam.value);
    if (tipo === 0) {
        erroTipo = true;
        $("#mstipo").html('<span class="label label-danger">O tipo de caminhão deve ser selecionado.</span>');
    } else {
        erroTipo = false;
        $("#mstipo").html('');
    }
}

async function selectTipoCaminhaoChange() {
    let tipo = Number.parseInt(selectTipoCam.value);

    if (tipo === null || isNaN(tipo) || tipo === 0) {
        selectProprietario.value = 0;
        await selectProprietarioChange();
        selectProprietario.innerHTML = '<option value="0">SELECIONE</option>';
        selectProprietario.disabled = true;
    } else {
        let i = tipos.findIndex((element) => { return (element.id === tipo); })
        if (tipos[i].capacidade < Number.parseFloat(textPesoItens.value.replace(',', '.'))) {
            mostraDialogo(
                'A capacidade deste tipo de caminhão é inferior ao peso total dos itens.',
                'warning',
                3000
            );
            selectTipoCam.value = 0;
            await selectTipoCaminhaoChange();
        } else {
            selectProprietario.disabled = false;
            selectProprietario.value = 0;

            let res = await postJSON(
                '/representacoes/pedido/frete/novo/obter-proprietarios-por-tipo.php',
                { tipo: tipo }
            );

            if (res.status) {
                let props = res.response;

                let options = `<option value="0">SELECIONE</option>`;
                for (let i = 0; i < props.length; i++) {
                    options +=
                        `<option value="${props[i].id}">
                        ${props[i].tipo === 1 ? props[i].pessoaFisica.nome : props[i].pessoaJuridica.nomeFantasia}
                    </option>`;
                }

                selectProprietario.innerHTML = options;
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
}

function selectProprietarioBlur() {
    let prop = Number.parseInt(selectProprietario.value);

    if (prop === null || isNaN(prop) || prop === 0) {
        erroProprietario = true;
        $('#msprop').html('<span class="label label-danger">O proprietário do caminhão deve ser selecionado.</span>');
    } else {
        erroProprietario = false;
        $('#msprop').html('');
    }
}

async function selectProprietarioChange() {
    let prop = Number.parseInt(selectProprietario.value);

    if (prop === null || isNaN(prop) || prop === 0) {
        selectCaminhao.value = 0;
        selectCaminhao.innerHTML = '<option value="0">SELECIONE</option>';
        selectCaminhao.disabled = true;
    } else {
        selectCaminhao.disabled = false;

        let res = await postJSON(
            '/representacoes/pedido/frete/novo/obter-caminhoes-por-prop.php',
            { prop: prop }
        );

        if (res.status) {
            let caminhoes = res.response;

            let options = `<option value="0">SELECIONE</option>`;
            for (let i = 0; i < caminhoes.length; i++) {
                options +=
                    `<option value="${caminhoes[i].id}">
                        ${caminhoes[i].marca} / ${caminhoes[i].modelo}
                    </option>`;
            }

            selectCaminhao.innerHTML = options;
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

function selectCaminhaoBlur() {
    let cam = Number.parseInt(selectCaminhao.value);

    if (cam === null || isNaN(cam) || cam === 0) {
        erroCaminhao = true;
        $('#mscaminhao').html('<span class="label label-danger">O caminhão deve ser selecionado.</span>');
    } else {
        erroCaminhao = false;
        $('#mscaminhao').html('');
    }
}

async function textDistanciaBlur() {
    let dist = Number.parseFloat(textDistancia.value);
    if (dist <= 0 || isNaN(dist)) {
        erroDistancia = true;
        $("#msdist").html('<span class="label label-danger">A distância a percorrer deve ser preenchida.</span>');

        piso = 0.0;
        textValorFrete.value = formatarValor(piso);
    } else {
    	if (Number.parseInt(selectTipoCam.value) !== 0) { 
			erroDistancia = false;
			$("#msdist").html('');

			if (tipos[tipos.findIndex((element) => { return (element.id === Number.parseInt(selectTipoCam.value)); })].eixos > 3) {
				await $.ajax({
				type: "POST",
				url: "/representacoes/pedido/frete/novo/calcular-piso-minimo.php",
				data: {
					distancia: dist, eixos: tipos[tipos.findIndex((element) => {
						return (element.id === Number.parseInt(selectTipoCam.value));
					})].eixos
				},
				success: function (response) {
					if (response <= 0) {
						mostraDialogo(
						    "Erro ao processar a requisição",
						    "danger",
						    3000
						);
					} else {
						let tmp = response.toString();
						tmp = tmp.replace('.', '#');
						tmp = tmp.substring(0, tmp.indexOf('#') + 3);
						tmp = tmp.replace('#', '.');
						piso = Number.parseFloat(tmp);
					}
				},
				error: function (xhr, status, thrown) {
					console.error(thrown);
					mostraDialogo(
						"Erro ao processar a requisição",
						"danger",
						3000
					);
				}
				});

				textValorFrete.value = formatarValor(truncate(piso));
			} else {
				piso = 1.0;
			}
        }
    }
}

async function textDistanciaValid() {
    let dist = Number.parseFloat(textDistancia.value);
    if (dist <= 0 || isNaN(dist)) {
        erroDistancia = true;
        $("#msdist").html('<span class="label label-danger">A distância a percorrer deve ser preenchida.</span>');

        piso = 0.0;
        textValorFrete.value = formatarValor(piso);
    } else {
	    if (Number.parseInt(selectTipoCam.value) !== 0) { 
		    erroDistancia = false;
		    $("#msdist").html('');

		    if (tipos[tipos.findIndex((element) => { return (element.id === Number.parseInt(selectTipoCam.value)); })].eixos > 3) {
		        await $.ajax({
		            type: "POST",
		            url: "/representacoes/pedido/frete/novo/calcular-piso-minimo.php",
		            data: {
		                distancia: dist, eixos: tipos[tipos.findIndex((element) => {
		                    return (element.id === Number.parseInt(selectTipoCam.value));
		                })].eixos
		            },
		            success: function (response) {
		                if (response <= 0) {
		                    mostraDialogo(
		                        "Erro ao processar a requisição",
		                        "danger",
		                        3000
		                    );
		                } else {
		                    let tmp = response.toString();
		                    tmp = tmp.replace('.', '#');
		                    tmp = tmp.substring(0, tmp.indexOf('#') + 3);
		                    tmp = tmp.replace('#', '.');
		                    piso = Number.parseFloat(tmp);
		                }
		            },
		            error: function (xhr, status, thrown) {
		                console.error(thrown);
		                mostraDialogo(
		                    "Erro ao processar a requisição",
		                    "danger",
		                    3000
		                );
		            }
		        });
		    } else {
		    	piso = 1.0;
		    }
        }
    }
}

function textValorMotoristaBlur() {
    let vm = Number.parseFloat(textValorMotorista.value.toString().replace(",", "."));

    if (vm === null || isNaN(vm) || vm <= 0) {
        erroValorMotorista = true;
        $('#msvalormotorista').html('<span class="label label-danger">O valor pago ao motorista precisa ser preenchido.</span>');
    } else {
        erroValorMotorista = false;
        $('#msvalormotorista').html('');
    }
}

function formaAdiantamentoEstado() {
    let va = Number.parseFloat(textValorAdiantamento.value.toString().replace(",", "."));

    if (va === null || isNaN(va) || va <= 0) {
        selectFormaAdiantamento.value = 0;
        selectFormaAdiantamento.disabled = true;
        erroFormaAdiantamento = false;
        $('#msformaadiantamento').html('');
    } else {
        selectFormaAdiantamento.disabled = false;
        selectFormaAdiantamento.value = 0;
    }
}

function selectFormaAdiantamentoBlur() {
    let forma = Number.parseInt(selectFormaAdiantamento.value);
    let va = Number.parseFloat(textValorAdiantamento.value.toString().replace(",", "."));

    if (va !== null && !isNaN(va) && va > 0) {
        if (forma === null || isNaN(forma) || forma === 0) {
            erroFormaAdiantamento = true;
            $('#msformaadiantamento').html('<span class="label label-danger">A forma de adiantamento precisa ser selecionada.</span>');
        } else {
            erroFormaAdiantamento = false;
            $('#msformaadiantamento').html('');
        }
    }
}

function textValorFreteBlur() {
    let valor = $(textValorFrete).val();
    if (valor.length === 0 || valor === "0,00") {
        erroValor = true;
        $("#msvalor").html('<span class="label label-danger">O valor do frete deve ser preenchido.</span>');
    } else {
        if (piso === 0.0) {
            erroValor = true;
            $("#msvalor").html('<span class="label label-danger">Selecione o tipo de caminhão e a distância para calcular o piso.</span>');
        } else {
            if (Number.parseFloat(valor.replace(',', '.')) < piso) {
                erroValor = true;
                $("#msvalor").html('<span class="label label-danger">O valor do frete menor que o piso ('+piso+').</span>');
            } else {
                erroValor = false;
                $("#msvalor").html('');
            }
        }
    }
}

function selectFormaBlur() {
    let forma = Number.parseInt(selectForma.value);

    if (forma === null || isNaN(forma) || forma === 0) {
        erroFormaPagamento = true;
        $('#msforma').html('<span class="label label-danger">A forma de pagamento precisa ser selecionada.</span>');
    } else {
        erroFormaPagamento = false;
        $('#msforma').html('');
    }
}

function dateEntregaBlur() {
    let entrega = dateEntrega.value.toString();
    if (entrega.length === 0) {
        erroEntrega = true;
        $("#msentrega").html('<span class="label label-danger">A data aproximada de entrega precisa ser preenchida.</span>');
    } else {
        let dateEntrega = new Date(entrega + ' 12:00:00');
        if (dateEntrega < Date.now()) {
            erroEntrega = true;
            $("#msentrega").html('<span class="label label-danger">A data é inválida (menor que a atual).</span>');
        } else {
            erroEntrega = false;
            $("#msentrega").html('');
        }
    }
}

function buttonClrItensClick() {
    itens = [];
    $(tbodyItens).html('');
    etapas = [];
    $(tbodyEtapas).html('');
    textPesoItens.value = 0.0;
    textValorItens.value = 0.0;
    tipos = [];
    limparSelectTipo();
}

async function validar() {
    textDescBlur();
    selectEstadoBlur();
    selectCidadeBlur();
    selectTipoCaminhaoBlur();
    selectProprietarioBlur();
    selectCaminhaoBlur();
    await textDistanciaValid();
    textValorMotoristaBlur();
    selectFormaAdiantamentoBlur();
    textValorFreteBlur();
    selectFormaBlur();
    dateEntregaBlur();

    return (
        !erroDesc &&
        !erroEstado &&
        !erroCidade &&
        !erroTipo &&
        !erroProprietario &&
        !erroCaminhao &&
        !erroDistancia &&
        !erroValorMotorista &&
        !erroFormaAdiantamento &&
        !erroValor &&
        !erroFormaPagamento &&
        !erroEntrega
    );
}

function buttonCancelarClick() {
    buttonLimparClick();
    location.href = '../../frete';
}

function buttonLimparClick() {
    textDesc.value = "";
    selectOrcFrete.disabled = false;
    selectOrcFrete.value = 0;
    selectPedVenda.disabled = false;
    selectPedVenda.value = 0;
    selectRepresentacao.disabled = false;
    selectRepresentacao.value = 0;
    selectEstado.value = 0;
    selectEstadoChange();
    selectCidade.value = 0;
    itens = [];
    $(tbodyItens).html("");
    etapas = [];
    $(tbodyEtapas).html('');
    textPesoItens.value = "0,0";
    piso = 0.0;
    textValorFrete.value = "0,00";
    tipos = [];
    limparSelectTipo();
    selectTipoCam.value = 0;
    selectTipoCaminhaoChange()
    textDistancia.value = "";
    textValorMotorista.value = "0,00";
    textValorAdiantamento.value = "";
    formaAdiantamentoEstado();
    selectForma.value = 0;
    dateEntrega.value = "";

    erroDesc = true;
    erroEstado = true;
    erroCidade = true;
    erroTipo = true;
    erroProprietario = true;
    erroCaminhao = true;
    erroDistancia = true;
    erroValorMotorista = true;
    erroValor = true;
    erroFormaPagamento = true;
    erroEntrega = true;
}

async function buttonSalvarClick() {
    let desc = "";
    let orc = 0;
    let ven = 0;
    let rep = 0;
    let est = 0;
    let cid = 0;
    let tip = 0;
    let prop = 0;
    let cam = 0;
    let dist = 0;
    let vm = 0.0;
    let vam = 0.0;
    let fa = 0;
    let peso = 0.0;
    let valor = 0.0;
    let fr = 0;
    let entrega = "";

    if (await validar()) {
        if (itens.length > 0) {
            desc = textDesc.value;
            orc = selectOrcFrete.value;
            ven = selectPedVenda.value;
            rep = selectRepresentacao.value;
            cid = selectCidade.value;
            tip = selectTipoCam.value;
            prop = selectProprietario.value;
            cam = selectCaminhao.value;
            dist = textDistancia.value;
            vm = textValorMotorista.value.toString().replace(',', '.');
            vam = textValorAdiantamento.value.toString().replace(',', '.');
            fa = selectFormaAdiantamento.value;
            peso = textPesoItens.value.toString().replace(',', '.');
            valor = textValorFrete.value.toString().replace(',', '.');
            fr = selectForma.value;
            entrega = dateEntrega.value;

            let frm = new FormData();
            frm.append("desc", desc);
            frm.append('orc', orc.toString());
            frm.append("ven", ven.toString());
            frm.append("rep", rep.toString());
            frm.append("cid", cid.toString());
            frm.append("tip", tip.toString());
            frm.append('prop', prop.toString());
            frm.append('cam', cam.toString());
            frm.append("dist", dist);
            frm.append('vm', vm);
            frm.append('vam', vam);
            frm.append('fa', fa.toString());
            frm.append("peso", peso);
            frm.append("valor", valor);
            frm.append('fr', fr);
            frm.append("entrega", entrega);
            frm.append("itens", JSON.stringify(itens));
            frm.append('etapas', JSON.stringify(etapas));

            let res = await post(
                '/representacoes/pedido/frete/novo/gravar.php',
                frm
            );

            if (res.status) {
                mostraDialogo(
                    "<strong>Pedido de frete gravado com sucesso!</strong>" +
                    "<br />Os dados do novo pedido de frete foram salvos com sucesso no banco de dados.",
                    "success",
                    2000
                );
                buttonLimparClick();
            } else {
                mostraDialogo(
                    res.error.message + "<br />" +
                    "Status: "+res.error.code+" "+res.error.status,
                    "danger",
                    3000
                );
            }
        } else {
            mostraDialogo(
                "Adicione produtos ao orçamento.",
                "warning",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Preencha os campos obrigatórios.",
            "warning",
            3000
        );
    }
}

$(document).ready((event) => {
    let prods = get("/representacoes/gerenciar/produto/obter.php");
    if (prods === null || prods.length === 0) {
        alert("Não existem produtos cadastrados!");
        location.href = "../../inicio";
    }

    let orcamentos = get('/representacoes/pedido/frete/novo/obter-orcamentos.php');
    if (orcamentos !== null && orcamentos.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < orcamentos.length; i++) {
            options +=
                `<option value="${orcamentos[i].id}">
                    ${orcamentos[i].descricao}
                </option>`;
        }

        selectOrcFrete.innerHTML = options;
    }

    let vendas = get("/representacoes/pedido/frete/novo/obter-vendas.php");
    if (vendas !== null && vendas.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < vendas.length; i++) {
            options +=
                `<option value="${vendas[i].id}">
                    ${vendas[i].descricao}
                </option>`;
        }

        selectPedVenda.innerHTML = options;
    }

    let representacoes = get('/representacoes/pedido/frete/novo/obter-representacoes.php');
    if (representacoes !== null && representacoes.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < representacoes.length; i++) {
            options +=
                `<option value="${representacoes[i].id}">
                    ${representacoes[i].pessoa.nomeFantasia} (${representacoes[i].unidade})
                </option>`;
        }

        selectRepresentacao.innerHTML = options;
    }

    let estados = get('/representacoes/estado/obter.php');
    limparEstados();
    if (estados !== null && estados !== []) {
        for (let i = 0; i < estados.length; i++) {
            let option = document.createElement("option");
            option.value = estados[i].id;
            option.text = estados[i].nome;
            selectEstado.appendChild(option);
        }
    }

    let formasPagamento = get('/representacoes/pedido/frete/novo/obter-formas-pagamento.php');
    if (formasPagamento !== null && formasPagamento.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < formasPagamento.length; i++) {
            options +=
                `<option value="${formasPagamento[i].id}">
                    ${formasPagamento[i].descricao}
                </option>`;
        }

        selectFormaAdiantamento.innerHTML = options;
    }

    let formasRecebimento = get('/representacoes/pedido/frete/novo/obter-formas-recebimento.php');
    if (formasRecebimento !== null && formasRecebimento.length > 0) {
        let options = `<option value="0">SELECIONE</option>`;

        for (let i = 0; i < formasRecebimento.length; i++) {
            options +=
                `<option value="${formasRecebimento[i].id}">
                    ${formasRecebimento[i].descricao}
                </option>`;
        }

        selectForma.innerHTML = options;
    }

    buttonLimparClick();

    $(textValorMotorista).mask('0000000000,00', { reverse: true });
    $(textValorAdiantamento).mask('0000000000,00', { reverse: true });
    $(textValorFrete).mask('0000000000,00', { reverse: true });
});
