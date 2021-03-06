<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Gerenciar Clientes</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Clientes</div>

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
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend" style="width: 200px;">Clientes Cadastrados</div>

    <div class="fieldset-card-container">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-10">
                <label for="cbord">Ordenar por:</label>
                <select id="cbord" class="form-control input-sm" onchange="ordenar();">
                    <option value="1">REGISTRO (CRESCENTE)</option>
                    <option value="2">REGISTRO (DECRESCENTE)</option>
                    <option value="3">NOME (CRESCENTE)</option>
                    <option value="4">NOME (DECRESCENTE)</option>
                    <option value="5">CPF/CNPJ (CRESCENTE)</option>
                    <option value="6">CPF/CNPJ (DECRESCENTE)</option>
                    <option value="7">CADASTRO (CRESCENTE)</option>
                    <option value="8">CADASTRO (DECRESCENTE)</option>
                    <option value="9">TIPO (CRESCENTE)</option>
                    <option value="10">TIPO (DECRESCENTE)</option>
                    <option value="11">EMAIL (CRESCENTE)</option>
                    <option value="12">EMAIL (DECRESCENTE)</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="novo">&nbsp;</label>
                <a role="button" id="novo" class="btn btn-success btn-sm" style="width: 100%;" href="/representacoes/gerenciar/cliente/novo">NOVO</a>
            </div>
        </div>

        <table id="table_clientes" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 40%;">NOME/NOME FANTASIA</th>
                <th style="width: 16%;">CPF/CNPJ</th>
                <th style="width: 10%;">CADASTRO</th>
                <th style="width: 10%;">TIPO</th>
                <th>EMAIL</th>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 2%;">&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbody_clientes">
            </tbody>
        </table>
    </div>
</div>