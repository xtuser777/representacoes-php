const textPedido = document.getElementById('textPedido');
const textStatusAtual = document.getElementById('textStatusAtual');
const dateStatusAtual = document.getElementById('dateStatusAtual');
const selectStatus = document.getElementById('selectStatus');
const dateStatus = document.getElementById('dateStatus');
const textObservacoes = document.getElementById('textObservacoes');

let erroStatus = true;
let erroData = true;

let pedido = {};

function selectStatusBlur() {
    let status = Number.parseInt(selectStatus.value);

    if (status === null || isNaN(status) || status === 0) {
        erroStatus = true;
        $('#msstatus').html('<span class="label label-danger">O novo status precisa ser selecionado.</span>');
    } else {
        erroStatus = false;
        $('#msstatus').html('');
    }
}

function dateStatusBlur() {
    let data = dateStatus.value;

    if (data === null || data.length === 0) {
        erroData = true;
        $('#msdata').html('<span class="label label-danger">A data do novo status precisa ser preenchida.</span>');
    } else {
        erroData = false;
        $('#msdata').html('');
    }
}

function limpar() {
    textPedido.value = '';
    textStatusAtual.value = '';
    dateStatusAtual.value = '';

    selectStatus.value = 0;
    dateStatus.value = '';
    textObservacoes.value = '';
}

function cancelar() {
    limpar();
    location.href = '../../status';
}

function validar() {
    selectStatusBlur();
    dateStatusBlur();

    return (
        !erroStatus && !erroData
    );
}

async function alterar() {
    let status = 0;
    let data = '';
    let obs = '';

    if (validar()) {
        status = Number.parseInt(selectStatus.value);
        data = dateStatus.value;
        obs = textObservacoes.value;

        let res = await postJSON(
            '/representacoes/pedido/status/alterar/gravar.php',
            {
                pedido: pedido.id,
                status: status,
                data: data,
                obs: obs
            }
        );

        if (res.status) {
            mostraDialogo(
                "<strong>Status do Pedido de frete alterado com sucesso!</strong>" +
                "<br />Os dados do novo status do pedido de frete foram salvos com sucesso no banco de dados.",
                "success",
                2000
            );

            carregarPagina();

            selectStatus.value = 0;
            dateStatus.value = '';
            textObservacoes.value = '';
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
            "Preencha os campos obrigatórios.",
            "warning",
            3000
        );
    }
}

function carregarPagina() {
    pedido = get('/representacoes/pedido/status/alterar/obter.php');
    if (!!pedido) {
        if (pedido.status.status.id === 1) {
            alert(
                'Este pedido ainda não possui etapas de carregamento com autorização de carregamento. \n\
                 Autorize o caregamento das etapas primeiro.'
            );
            location.href = '../../status';
        } else {
            let esc = 0;
            for (let i = 0; i < pedido.etapas.length; i++) {
                if (pedido.etapas[i].status === 1)
                    esc++;
            }

            if (pedido.status.status.id === 2 && esc > 0) {
                alert(
                    `Este pedido ainda possui ${esc} etapas de carregamento sem autorização de carregamento. \n
                     Autorize o caregamento destas etapas primeiro.`
                );
                location.href = '../../status';
            } else {
                if (pedido.status.status.id === 6) {
                    alert(
                        'Este pedido foi cancelado e não pode ser mais alterado.'
                    );
                    location.href = '../../status';
                } else {
                    textPedido.value = pedido.descricao;
                    textStatusAtual.value = pedido.status.status.descricao;
                    dateStatusAtual.value = pedido.status.data;

                    let status = get('/representacoes/pedido/status/alterar/obter-status.php');
                    if (!!status && status.length > 0) {
                        if (pedido.status.status.id !== 5) {
                            let i = 0;
                            while (i < status.length && status[i].id !== pedido.status.status.id)
                                i++;

                            i++;

                            let options = `<option value="0">SELECIONE</option>`;
                            for (let j = i; j < status.length; j++) {
                                options +=
                                    `<option value="${status[j].id}">${status[j].descricao}</option>`;
                            }

                            selectStatus.innerHTML = options;
                        } else {
                            let last = get('/representacoes/pedido/status/alterar/obter-status-anterior.php');

                            if (!!last) {
                                let options = `<option value="0">SELECIONE</option>`;
                                for (let j = last.status.id; j < status.length; j++) {
                                    options +=
                                        `<option value="${status[j].id}">${status[j].descricao}</option>`;
                                }

                                selectStatus.innerHTML = options;
                            }
                        }
                    }
                }
            }
        }
    }
}

$(document).ready(carregarPagina());