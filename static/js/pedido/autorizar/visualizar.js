const textDescricao = document.getElementById('textDescricao');
const textDestino = document.getElementById('textDestino');
const textDistancia = document.getElementById('textDistancia');
const textProprietario = document.getElementById('textProprietario');
const textCaminhao = document.getElementById('textCaminhao');
const textTipoCaminhao = document.getElementById('textTipoCaminhao');
const dateEntrega = document.getElementById('dateEntrega');
const tableEtapas = document.getElementById('tableEtapas');
const tbodyEtapas = document.getElementById('tbodyEtapas');
const textRepresentacao = document.getElementById('textRepresentacao');
const textCidade = document.getElementById('textCidade');
const textCarga = document.getElementById('textCarga');

let pedido = {};
let etapas = [];
let etapa = {};

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
                <td>${dados[i].representacao.pessoa.nomeFantasia}</td>
                <td>${dados[i].representacao.unidade}</td>
                <td>${formatarPeso(dados[i].carga)}</td>
                <td>${status}</td>
            </tr>`;
    }

    tbodyEtapas.innerHTML = txt;
}

function limpar() {
    textDescricao.value = '';
    textDestino.value = '';
    textDistancia.value = '';
    textProprietario.value = '';
    textCaminhao.value = '';
    textTipoCaminhao.value = '';
    dateEntrega.value = '';

    pedido = {};
    etapas = [];

    tbodyEtapas.innerHTML = '';

    textRepresentacao.value = '';
    textCidade.value = '';
    textCarga.value = '';
}

function voltar() {
    limpar();
    location.href = '../../autorizar';
}

function selecionarEtapa() {
    etapa = etapas[0];

    textRepresentacao.value = etapa.representacao.pessoa.nomeFantasia;
    textCidade.value = etapa.representacao.unidade;
    textCarga.value = formatarPeso(etapa.carga);
}

async function autorizar() {
    if (etapa !== null && etapa !== {}) {
        let res = await postJSON(
            '/representacoes/pedido/autorizar/visualizar/autorizar.php',
            { etapa: etapa.id, pedido: pedido.id }
        );

        if (res.status) {
            mostraDialogo(
                'Etapa de carregamento autorizada com sucesso.',
                'success',
                3000
            );

            etapas.shift();

            const guia = window.open(`/representacoes/pedido/autorizar/visualizar/emitir.php?pedido=${pedido.id}&etapa=${etapa.id}`, '_blank');
            guia.focus();

            if (etapas.length === 0) {
                alert('Todas as etapas deste pedido foram autorizadas. Voltando controle de autorizações.');
                voltar();
            } else {
                selecionarEtapa();
            }
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

$(document).ready(function (event) {
    pedido = get('/representacoes/pedido/autorizar/visualizar/obter.php');
    if (!!pedido && typeof pedido !== "string") {
        for (let i = 0; i < pedido.etapas.length; i++) {
            if (pedido.etapas[i].status === 1)
                etapas.push(pedido.etapas[i]);
        }

        if (etapas.length === 0) {
            alert('Todas as etapas deste pedido foram autorizadas. Voltando controle de autorizações.');
            voltar();
        } else {
            textDescricao.value = pedido.descricao;
            textDestino.value = `${pedido.destino.nome} / ${pedido.destino.estado.nome}`;
            textDistancia.value = pedido.distancia;
            textProprietario.value = pedido.proprietario.tipo === 1 ? pedido.proprietario.pessoaFisica.nome : pedido.proprietario.pessoaJuridica.nomeFantasia;
            textCaminhao.value = `${pedido.caminhao.marca} / ${pedido.caminhao.modelo}`;
            textTipoCaminhao.value = pedido.tipoCaminhao.descricao;
            dateEntrega.value = pedido.entrega;
            preencheTabelaEtapas(etapas);

            selecionarEtapa();
        }
    }
});