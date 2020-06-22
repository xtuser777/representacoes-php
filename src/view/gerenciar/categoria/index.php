<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Gerenciar Categorias de Contas</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Categorias de Contas</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-10">
                <label for="filtro">Filtro:</label>
                <input type="text" id="filtro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição..." />
            </div>

            <div class="col-sm-2">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend">Categorias Cadastradas</div>

    <div class="fieldset-card-container">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-10">
                <label for="cbord">Ordenar por:</label>
                <select name="cbord" id="cbord" class="form-control input-sm" onchange="ordenar();">
                    <option value="1">REGISTRO (CRESCENTE)</option>
                    <option value="2">REGISTRO (DECRESCENTE)</option>
                    <option value="3">DESCRIÇÃO (CRESCENTE)</option>
                    <option value="4">DESCRIÇÃO (DECRESCENTE)</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="novo">&nbsp;</label>
                <a role="button" id="novo" class="btn btn-success btn-sm" style="width: 100%;" href="/representacoes/gerenciar/categoria/novo">NOVO</a>
            </div>
        </div>

        <table id="table_categorias" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="">DESCRIÇÃO</th>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 2%;">&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbody_categorias">
            </tbody>
        </table>
    </div>
</div>