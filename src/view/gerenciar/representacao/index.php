<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Gerenciar Representações</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Representações</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-8">
                <label for="filtro">Filtro:</label>
                <input type="text" id="filtro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por nome e email..." />
            </div>

            <div class="col-sm-2">
                <label for="filtro_cad">Filtro Cadastro:</label>
                <input type="date" id="filtro_cad" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrarRepresentacoes();">FILTRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend">Representações Cadastradas</div>

    <div class="fieldset-card-container">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-10">
                <label for="cbord">Ordenar por:</label>
                <select id="cbord" class="form-control input-sm" onchange="ordenarRepresentacoes();">
                    <option value="1">REGISTRO (CRESCENTE)</option>
                    <option value="2">REGISTRO (DECRESCENTE)</option>
                    <option value="3">NOME FANTASIA (CRESCENTE)</option>
                    <option value="4">NOME FANTASIA (DECRESCENTE)</option>
                    <option value="5">CNPJ (CRESCENTE)</option>
                    <option value="6">CNPJ (DECRESCENTE)</option>
                    <option value="7">CADASTRO (CRESCENTE)</option>
                    <option value="8">CADASTRO (DECRESCENTE)</option>
                    <option value="9">UNIDADE (CRESCENTE)</option>
                    <option value="10">UNIDADE (DECRESCENTE)</option>
                    <option value="11">EMAIL (CRESCENTE)</option>
                    <option value="12">EMAIL (DECRESCENTE)</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="novo">&nbsp;</label>
                <a role="button" id="novo" class="btn btn-success" style="width: 100%;" href="/gerenciar/representacao/novo">NOVO</a>
            </div>
        </div>

        <table id="table_representacoes" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 30%;">NOME FANTASIA</th>
                <th style="width: 16%;">CNPJ</th>
                <th style="width: 10%;">CADASTRO</th>
                <th style="width: 20%;">UNIDADE</th>
                <th>EMAIL</th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
                <th style="width: 2%;"></th>
            </tr>
            </thead>

            <tbody id="tbody_representacoes">
            </tbody>
        </table>
    </div>
</div>