<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Cadastrar Novo Caminhão</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Caminhão</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-2">
                <label for="placa">Placa <span style="color: red;">*</span>:</label>
                <input type="text" id="placa" class="form-control input-sm" style="width: 100%;" value="" maxlength="8"/>
                <div id="msplaca"></div>
            </div>

            <div class="col-sm-3">
                <label for="marca">Marca <span style="color: red;">*</span>:</label>
                <input type="text" id="marca" class="form-control input-sm" style="width: 100%;" value="" maxlength="30"/>
                <div id="msmarca"></div>
            </div>

            <div class="col-sm-4">
                <label for="modelo">Modelo <span style="color: red;">*</span>:</label>
                <input type="text" id="modelo" class="form-control input-sm" style="width: 100%;" value="" maxlength="50"/>
                <div id="msmodelo"></div>
            </div>

            <div class="col-sm-3">
                <label for="cor">Cor <span style="color: red;">*</span>:</label>
                <input type="text" id="cor" class="form-control input-sm" style="width: 100%;" value="" maxlength="30"/>
                <div id="mscor"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <label for="anofab">Ano Fabricação <span style="color: red;">*</span>:</label>
                <input type="text" id="anofab" class="form-control input-sm" style="width: 100%;" value="" />
                <div id="msanofab"></div>
            </div>

            <div class="col-sm-2">
                <label for="anomod">Ano Modelo <span style="color: red;">*</span>:</label>
                <input type="text" id="anomod" class="form-control input-sm" style="width: 100%;" value="" />
                <div id="msanomod"></div>
            </div>

            <div class="col-sm-3">
                <label for="tipo">Tipo <span style="color: red;">*</span>:</label>
                <select id="tipo" class="form-control input-sm" style="width: 100%;">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="mstipo"></div>
            </div>

            <div class="col-sm-5">
                <label for="proprietario">Proprietário <span style="color: red;">*</span>:</label>
                <select id="proprietario" class="form-control input-sm" style="width: 100%;">
                    <option value="0">SELECIONE</option>
                </select>
                <div id="msprop"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <a role="button" class="btn btn-default" style="width: 100%;" href="/representacoes/gerenciar/caminhao">VOLTAR</a>
    </div>

    <div class="col-sm-6"></div>

    <div class="col-sm-2">
        <button id="limpar" class="btn btn-primary" style="width: 100%;" onclick="limpar();">LIMPAR</button>
    </div>

    <div class="col-sm-2">
        <button id="salvar" class="btn btn-success" style="width: 100%;" onclick="gravar();">SALVAR</button>
    </div>
</div>