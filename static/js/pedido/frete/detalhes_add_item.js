function preencheTabelaItens(dados) {
    let txt = "";

    $.each(dados, function () {
        txt +=
            '<tr>\
                <td>' + this.produto.descricao + '</td>\
                <td>' + this.produto.representacao.nomeFantasia + '</td>\
                <td>' + formatarPeso(this.produto.peso) + '</td>\
                <td>' + this.quantidade + '</td>\
                <td>' + this.peso + '</td>\
            </tr>';
    });

    $(tbodyItens).html(txt);
}

function preencheTabelaEtapas(dados) {
    let txt = ``;

    for (let i = 0; i < dados.length; i++) {
        let status = '';

        switch (dados[i].status) {
            case 1: status = 'PENDENTE'; break;
            case 2: status = 'AUTORIZADO'; break;
            case 3: status = 'CARREGADO'; break;
        }

        txt +=
            `<tr>
                <td>${dados[i].ordem}</td>
                <td>${dados[i].representacao.nomeFantasia}</td>
                <td>${dados[i].representacao.unidade}</td>
                <td>${formatarPeso(dados[i].carga)}</td>
                <td>${status}</td>
            </tr>`;
    }

    tbodyEtapas.innerHTML = txt;
}

function adicionarEtapa(etapa) {

    etapas.push({
        ordem: etapa.ordem,
        representacao: {
            id: etapa.representacao.id,
            nomeFantasia: etapa.representacao.pessoa.nomeFantasia,
            unidade: etapa.representacao.unidade
        },
        carga: etapa.carga,
        status: etapa.status
    });

    preencheTabelaEtapas(etapas);
}

async function adicionarItem(item) {
    let peso = 0.0;

    let res = await postJSON(
        '/representacoes/pedido/frete/detalhes/item/obter-tipos-por-item.php',
        { item: item.produto.id }
    );

    if (res.status) {
        let tmp = [];

        if (tipos.length === 0) {
            for (let i = 0; i < res.response.length; i++) {
                let option = document.createElement("option");
                option.value = res.response[i].id;
                option.text = res.response[i].descricao;
                selectTipoCam.appendChild(option);
            }
        } else {
            for (let i = 0; i < res.response.length; i++) {
                if (tipos.findIndex((element) => { return (element.id === res.response[i].id); }) !== -1) {
                    tmp.push(res.response[i]);
                }
            }

            tipos = tmp;
            limparSelectTipo();
            for (let i = 0; i < tmp.length; i++) {
                let option = document.createElement("option");
                option.value = tmp[i].id;
                option.text = tmp[i].descricao;
                selectTipoCam.appendChild(option);
            }
        }

        let dados = {
            produto: {
                id: item.produto.id,
                descricao: item.produto.descricao,
                peso: item.produto.peso,
                estado: item.produto.representacao.pessoa.contato.endereco.cidade.estado.id,
                representacao: {
                    id: item.produto.representacao.id,
                    nomeFantasia: item.produto.representacao.pessoa.nomeFantasia,
                    unidade: item.produto.representacao.unidade
                }
            },
            quantidade: item.quantidade,
            peso: item.peso
        };

        itens.push(dados);

        preencheTabelaItens(itens);
    } else {
        mostraDialogo(
            res.error.message + "<br />" +
            "Status: "+res.error.code+" "+res.error.status,
            "danger",
            3000
        );
    }
}

function truncate(valor) {
    let numbers = valor.toString();
    numbers = numbers.replace('.', '#');
    if (numbers.search('#') === -1 || numbers.substring(numbers.search('#'), numbers.length).length <= 2) return valor;
    let numbersTruncated = numbers.substring(0, numbers.search('#')+3);
    numbersTruncated = numbersTruncated.replace('#', '.');

    return Number.parseFloat(numbersTruncated);
}