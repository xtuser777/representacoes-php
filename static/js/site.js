// Please see documentation at https://docs.microsoft.com/aspnet/core/client-side/bundling-and-minification
// for details on configuring this project to bundle and minify static web assets.

// Write your JavaScript code.
/**
 * @return {string}
 */
function FormatarData(data) {
    let d = data.split("-");
    return d[2] + "/" + d[1] + "/" + d[0];
}

/**
 * @return {string}
 */
function FormatarDataIso(data) {
    data = FormatarData(data);
    let d = data.split("/");
    return d[2] + "-" + d[1] + "-" + d[0];
}

function formatarPeso(peso) {
    let pesoFormat = peso.toString();
    pesoFormat = pesoFormat.replace(".", "#");
    if (pesoFormat.search("#") < 0) {
        pesoFormat = pesoFormat + ",0";
    } else {
        pesoFormat = pesoFormat.replace("#", ",");
    }

    return pesoFormat;
}

function formatarValor(valor) {
    let valorFormat = valor.toString();
    valorFormat = valorFormat.replace(".", "#");
    if (valorFormat.search("#") < 0) {
        valorFormat = valorFormat + ",00";
    } else {
        if (valorFormat.split("#")[1].length === 1) {
            valorFormat = valorFormat + "0";
        }

        valorFormat = valorFormat.replace("#", ",");
    }

    return valorFormat;
}

function mostraDialogo(mensagem, tipo, tempo){

    // se houver outro alert desse sendo exibido, cancela essa requisição
    if($("#message").is(":visible")){
        return false;
    }

    // se não setar o tempo, o padrão é 3 segundos
    if(!tempo){
        let tempo = 3000;
    }

    // se não setar o tipo, o padrão é alert-info
    if(!tipo){
        let tipo = "info";
    }

    // monta o css da mensagem para que fique flutuando na frente de todos elementos da página
    let cssMessage = "display: block; position: fixed; top: 60px; left: 20%; right: 20%; width: 60%; padding-top: 10px; z-index: 9999";
    let cssInner = "margin: 0 auto; box-shadow: 1px 1px 5px black;";

    // monta o html da mensagem com Bootstrap
    let dialogo = "";
    dialogo += '<div id="message" style="'+cssMessage+'">';
    dialogo += '    <div class="alert alert-'+tipo+' alert-dismissable" style="'+cssInner+'">';
    dialogo += '    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>';
    dialogo +=          mensagem;
    dialogo += '    </div>';
    dialogo += '</div>';

    // adiciona ao body a mensagem com o efeito de fade
    $("body").append(dialogo);
    $("#message").hide();
    $("#message").fadeIn(200);

    // contador de tempo para a mensagem sumir
    setTimeout(function() {
        $('#message').fadeOut(300, function(){
            $(this).remove();
        });
    }, tempo); // milliseconds
}