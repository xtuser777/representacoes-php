<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Relatório de Clientes</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Clientes</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-3">
                <label for="textFiltro">Filtro:</label>
                <input type="text" id="textFiltro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por nome e email..." />
            </div>

            <div class="col-sm-2">
                <label for="dateCadastroInicio">Filtro Cadastro Início:</label>
                <input type="date" id="dateCadastroInicio" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="dateCadastroFim">Filtro Cadastro Fim:</label>
                <input type="date" id="dateCadastroFim" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="selectTipo">Tipo Cliente:</label>
                <select id="selectTipo" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                    <option value="1">FÍSICA</option>
                    <option value="2">JURÍDICA</option>
                </select>
            </div>

            <div class="col-sm-3">
                <label for="selectOrdem">Ordenar por:</label>
                <select id="selectOrdem" class="form-control input-sm">
                    <option value="1">NOME (CRESCENTE)</option>
                    <option value="2">NOME (DECRESCENTE)</option>
                    <option value="3">CPF/CNPJ (CRESCENTE)</option>
                    <option value="4">CPF/CNPJ (DECRESCENTE)</option>
                    <option value="5">CADASTRO (CRESCENTE)</option>
                    <option value="6">CADASTRO (DECRESCENTE)</option>
                    <option value="7">TELEFONE (CRESCENTE)</option>
                    <option value="8">TELEFONE (DECRESCENTE)</option>
                    <option value="9">CELULAR (CRESCENTE)</option>
                    <option value="10">CELULAR (DECRESCENTE)</option>
                    <option value="11">TIPO (CRESCENTE)</option>
                    <option value="12">TIPO (DECRESCENTE)</option>
                    <option value="13">EMAIL (CRESCENTE)</option>
                    <option value="14">EMAIL (DECRESCENTE)</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3"></div>

            <div class="col-sm-3">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
            </div>

            <div class="col-sm-3">
                <label for="emitir">&nbsp;</label>
                <button id="emitir" class="btn btn-info btn-sm" style="width: 100%;" onclick="emitir();">EMITIR PDF</button>
            </div>

            <div class="col-sm-3"></div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend" style="width: 200px;">Clientes listados</div>

    <div class="fieldset-card-container">
        <table id="tableClientes" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 30%;">NOME/NOME FANTASIA</th>
                <th style="width: 13%;">CPF/CNPJ</th>
                <th style="width: 8%;">CADASTRO</th>
                <th style="width: 10%;">TELEFONE</th>
                <th style="width: 11%;">CELULAR</th>
                <th style="width: 6%;">TIPO</th>
                <th>EMAIL</th>
            </tr>
            </thead>

            <tbody id="tbodyClientes">
            </tbody>
        </table>
    </div>
</div>