<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Detalhes do Produto</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Produto</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-7">
                <label for="desc">Descrição <span style="color: red;">*</span>:</label>
                <input type="text" id="desc" class="form-control input-sm" style="width: 100%;" value="" />
                <div id="msdesc"></div>
            </div>

            <div class="col-sm-5">
                <label for="representacao">Representação <span style="color: red;">*</span>:</label>
                <select id="representacao" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="msrep"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="medida">Medida <span style="color: red;">*</span>:</label>
                <input type="text" id="medida" class="form-control input-sm" style="width: 100%;" placeholder="Exemplo: Kg, Sacos de X Kg..." value="" />
                <div id="msmedida"></div>
            </div>

            <div class="col-sm-4">
                <label for="preco">Preço <span style="color: red;">*</span>:</label>
                <div class="input-group">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="preco" class="form-control input-sm" style="width: 100%;" value=""/>
                </div>
                <div id="mspreco"></div>
            </div>

            <div class="col-sm-4">
                <label for="preco_out">Preço fora do estado:</label>
                <div class="input-group">
                    <div class="input-group-addon">R$</div>
                    <input type="text" id="preco_out" class="form-control input-sm" style="width: 100%;" value=""/>
                </div>
                <div id="mspo"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <a role="button" class="btn btn-default" style="width: 100%;" href="/gerenciar/produto">VOLTAR</a>
    </div>

    <div class="col-sm-6"></div>

    <div class="col-sm-2">
        <button id="limpar" class="btn btn-primary" style="width: 100%;" onclick="limpar();">LIMPAR</button>
    </div>

    <div class="col-sm-2">
        <button id="salvar" class="btn btn-success" style="width: 100%;" onclick="gravar();">SALVAR</button>
    </div>
</div>