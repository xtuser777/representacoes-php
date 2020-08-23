const txFiltro = document.getElementById("txFiltro");
const txFiltroData = document.getElementById("txFiltroData");
const selectOrd = document.getElementById("cbord");
const tabDespesas = document.getElementById("tabDespesas");
const tbodyDespesas = document.getElementById("tbodyDespesas");

function preencherTabela(dados) {
    let txt = "";
    $.each(dados, function () {
        let valorFormat = this.valor.toString();
        valorFormat = valorFormat.replace('.', '#');
        if (valorFormat.search('#') === -1) valorFormat += ',00';
        else valorFormat = valorFormat.replace('#', ',');
        txt +=
            '<tr>\
                <td class="hidden">' + this.id + '</td>\
                <td>' + this.descricao + '</td>\
                <td>' + this.categoria.descricao + '</td>\
                <td>' + FormatarData(this.data) + '</td>\
                <td>' + FormatarData(this.vencimento) + '</td>\
                <td>' + this.empresa + '</td>\
                <td>'+ this.autor.funcionario.pessoa.nome +'</td>\
                <td>'+ valorFormat +'</td>\
                <td><a role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="ALTERAR" href="javascript:alterar(' + this.id + ')"></a></td>\
                <td><a role="button" class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="EXCLUIR" href="javascript:excluir(' + this.id + ')"></a></td>\
            </tr>';
    });
    $(tbodyOrcamentos).html(txt);
}

function get(url_i) {
    let res = {};
    let request = new XMLHttpRequest();
    request.open("GET", url_i, false);
    request.send();

    if (request.DONE === 4 && request.status === 200) {
        res = JSON.parse(request.responseText);
    } else {
        mostraDialogo(
            "Erro na requisição da URL " + url_i + ". <br />" +
            "Status: "+request.status+" "+request.statusText,
            "danger",
            3000
        );
    }

    return res;
}

function obter() {
    let data = get("/representacoes/controlar/lancar/despesas/obter.php");
    preencherTabela(data);
}

$(document).ready(function (event) {
    let cats = get("/representacoes/controlar/lancar/despesas/obter-categorias.php");
    if (cats === null || cats.length === 0) {
        alert("Não existem categorias de contas cadastradas!");
        location.href = "../../../inicio";
    }
    obter();
});

function ordenar() {
    let ord = selectOrd.value;

    let request = new XMLHttpRequest();
    request.open("POST", "/representacoes/controlar/lancar/despesas/ordenar.php", false);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(encodeURI('col='+ord));

    if (request.DONE === 4 && request.status === 200) {
        let res = JSON.parse(request.responseText);
        if (res !== null && typeof res !== "string" && res.length !== 0) {
            preencherTabela(res);
        } else {
            mostraDialogo(
                res,
                "danger",
                3000
            );
        }
    } else {
        mostraDialogo(
            "Erro na requisição da URL /representacoes/controlar/lancar/despesas/ordenar.php. <br />" +
            "Status: "+request.status+" "+request.statusText,
            "danger",
            3000
        );
    }
}

function filtrar() {
    let filtro = txFiltro.value;
    let data = txFiltroData.value;

    if (filtro === "" && data === "") {
        obter();
    } else {
        if (filtro !== "" && data !== "") {
            let request = new XMLHttpRequest();
            request.open("POST", "/representacoes/controlar/lancar/despesas/obter-por-filtro-data.php", false);
            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            request.send(encodeURI("filtro="+filtro+"&data="+data));

            if (request.DONE === 4 && request.status === 200) {
                let res = JSON.parse(request.responseText);
                if (res !== null && typeof res !== "string" && res.length !== 0) {
                    preencherTabela(res);
                } else {
                    mostraDialogo(
                        res,
                        "danger",
                        3000
                    );
                }
            } else {
                mostraDialogo(
                    "Erro na requisição da URL /representacoes/controlar/lancar/despesas/obter-por-filtro-data.php. <br />" +
                    "Status: "+request.status+" "+request.statusText,
                    "danger",
                    3000
                );
            }
        } else {
            if (filtro !== "") {
                $.ajax({
                    type: 'POST',
                    url: '/representacoes/controlar/lancar/despesas/obter-por-filtro.php',
                    data: { filtro: filtro },
                    success: function (response) {
                        if (response != null && response !== ""){
                            preencherTabela(response);
                        }
                    },
                    error: function () {
                        alert("Ocorreu um erro ao comunicar-se com o servidor...");
                    }
                });
            } else {
                if (data !== ""){
                    $.ajax({
                        type: 'POST',
                        url: '/representacoes/controlar/lancar/despesas/obter-por-data.php',
                        data: { data: data },
                        success: function (response) {
                            if (response != null && response !== ""){
                                preencherTabela(response);
                            }
                        },
                        error: function () {
                            alert("Ocorreu um erro ao comunicar-se com o servidor...");
                        }
                    });
                }
            }
        }
    }
}

function excluir(id) {
    bootbox.confirm({
        message: "Confirma a exclusão desta despesa?",
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
                $.ajax({
                    type: 'POST',
                    url: '/representacoes/controlar/lancar/despesas/excluir.php',
                    data: {
                        id: id
                    },
                    success: function (result) {
                        if (result === "") {
                            obter();
                        } else {
                            alert("Ocorreu um problema ao excluir esta despesa.");
                        }
                    },
                    error: function (XMLHttpRequest, txtStatus, errorThrown) {
                        alert("Status: " + txtStatus);
                        alert("Error: " + errorThrown);
                    }
                });
            }
        }
    });
}

function alterar(id) {
    $.ajax({
        type: 'POST',
        url: '/representacoes/controlar/lancar/despesas/enviar.php',
        data: {
            id: id
        },
        success: function (result) {
            if (result.length > 0) alert(result);
            else {
                window.location.href = "../../../controlar/lancar/despesas/detalhes";
            }
        },
        error: function (XMLHttpRequest, txtStatus, errorThrown) {
            mostraDialogo(
                "<strong>Ocorreu um erro ao se comunicar com o servidor...</strong>" +
                "<br/>"+errorThrown,
                "danger",
                2000
            );
        }
    });
}