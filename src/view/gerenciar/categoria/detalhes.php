<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Detalhes da Categoria</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados da Categoria</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-12">
                <label for="desc">Descrição <span style="color: red;">*</span>:</label>
                <input type="text" id="desc" class="form-control input-sm" style="width: 100%;" value="" />
                <div id="msdesc"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <a role="button" class="btn btn-default" style="width: 100%;" href="/representacoes/gerenciar/categoria">VOLTAR</a>
    </div>

    <div class="col-sm-8"></div>

    <div class="col-sm-2">
        <button id="salvar" class="btn btn-success" style="width: 100%;" onclick="gravar();">SALVAR</button>
    </div>
</div>