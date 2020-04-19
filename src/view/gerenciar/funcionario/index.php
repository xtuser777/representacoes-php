<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Gerenciar Funcionários</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Funcionários</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-8">
                <label for="filtro">Filtro:</label>
                <input type="text" id="filtro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por nome, login e email..." />
            </div>

            <div class="col-sm-2">
                <label for="filtro_adm">Filtro Admissão:</label>
                <input type="date" id="filtro_adm" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrarFuncionarios();">FILTRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend">Funcionários Cadastrados</div>

    <div class="fieldset-card-container">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-10">
                <label for="cbord">Ordenar por:</label>
                <select id="cbord" class="form-control input-sm" onchange="ordenarLista();">
                    <option value="1">REGISTRO (CRESCENTE)</option>
                    <option value="2">REGISTRO (DECRESCENTE)</option>
                    <option value="3">NOME (CRESCENTE)</option>
                    <option value="4">NOME (DECRESCENTE)</option>
                    <option value="5">USUÁRIO (CRESCENTE)</option>
                    <option value="6">USUÁRIO (DECRESCENTE)</option>
                    <option value="7">NÍVEL (CRESCENTE)</option>
                    <option value="8">NÍVEL (DECRESCENTE)</option>
                    <option value="9">CPF (CRESCENTE)</option>
                    <option value="10">CPF (DECRESCENTE)</option>
                    <option value="11">ADMISSÃO (CRESCENTE)</option>
                    <option value="12">ADMISSÃO (DECRESCENTE)</option>
                    <option value="13">TIPO (CRESCENTE)</option>
                    <option value="14">TIPO (DECRESCENTE)</option>
                    <option value="15">ATIVO (CRESCENTE)</option>
                    <option value="16">ATIVO (DECRESCENTE)</option>
                    <option value="17">EMAIL (CRESCENTE)</option>
                    <option value="18">EMAIL (DECRESCENTE)</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="novo">&nbsp;</label>
                <a role="button" id="novo" class="btn btn-success btn-sm" style="width: 100%;" href="/gerenciar/funcionario/novo">NOVO</a>
            </div>
        </div>

        <table id="table_funcionarios" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 20%;">NOME</th>
                <th style="width: 10%;">USUÁRIO</th>
                <th style="width: 12%;">NÍVEL</th>
                <th style="width: 12%;">CPF</th>
                <th style="width: 8%;">ADMISSÃO</th>
                <th style="width: 10%;">TIPO</th>
                <th style="width: 8%;">ATIVO</th>
                <th>EMAIL</th>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 2%;">&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbody_funcionarios">
            </tbody>
        </table>
    </div>
</div>