<?php if (!isset($_COOKIE['USER_ID']))  header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Alterar Status do Pedido</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Status Atual do Pedido</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="textPedido">Pedido:</label>
                <input id="textPedido" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-3">
                <label for="textStatusAtual">Status:</label>
                <input id="textStatusAtual" class="form-control input-sm" style="width: 100%;" value="" readonly />
            </div>

            <div class="col-sm-3">
                <label for="dateStatusAtual">Data:</label>
                <input type="date" id="dateStatusAtual" class="form-control input-sm" style="width: 100%;" readonly />
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Novo Status do Pedido</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="selectStatus">Status <span style="color: red;">*</span>:</label>
                        <select id="selectStatus" class="form-control input-sm" onblur="selectStatusBlur();">
                            <option value="0">SELECIONE</option>
                        </select>
                        <div id="msstatus"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <label for="dateStatus">Data <span style="color: red;">*</span>:</label>
                        <input type="date" id="dateStatus" class="form-control input-sm" style="width: 100%;" onblur="dateStatusBlur();" />
                        <div id="msdata"></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="col-sm-12">
                    <label for="textObservacoes">Observações:</label>
                    <textarea id="textObservacoes" class="form-control input-sm" rows="4">
                    </textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <button id="button_cancelar" class="btn btn-danger" style="width: 100%;" onclick="cancelar();">CANCELAR</button>
    </div>

    <div class="col-sm-8"></div>

    <div class="col-sm-2">
        <button id="button_excluir" class="btn btn-primary" style="width: 100%;" onclick="alterar();">ALTERAR</button>
    </div>
</div>