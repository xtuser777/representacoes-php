<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Detalhes da Forma de Pagamento</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados da Forma de Pagamento</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-9">
                <label for="desc">Descrição <span style="color: red;">*</span>:</label>
                <input type="text" id="desc" class="form-control input-sm" style="width: 100%;" value="" />
                <div id="msdesc"></div>
            </div>

            <div class="col-sm-3">
                <label for="prazo">Prazo (dias) <span style="color: red;">*</span>:</label>
                <input type="number" id="prazo" class="form-control input-sm" style="width: 100%;" value="0" />
                <div id="msprazo"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <a role="button" class="btn btn-default" style="width: 100%;" href="/representacoes/gerenciar/formapagamento">VOLTAR</a>
    </div>

    <div class="col-sm-8"></div>

    <div class="col-sm-2">
        <button id="salvar" class="btn btn-success" style="width: 100%;" onclick="gravar();">SALVAR</button>
    </div>
</div>