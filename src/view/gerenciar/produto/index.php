<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Gerenciar Produtos</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Produtos</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="filtro">Filtro:</label>
                <input type="text" id="filtro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição..." />
            </div>

            <div class="col-sm-4">
                <label for="representacao">Representação:</label>
                <select id="representacao" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrarProdutos();">FILTRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend">Produtos Cadastrados</div>

    <div class="fieldset-card-container">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-10">
                <label for="cbord">Ordenar por:</label>
                <select name="cbord" id="cbord" class="form-control input-sm" onchange="ordenarProdutos();">
                    <option value="1">REGISTRO (CRESCENTE)</option>
                    <option value="2">REGISTRO (DECRESCENTE)</option>
                    <option value="3">DESCRIÇÃO (CRESCENTE)</option>
                    <option value="4">DESCRIÇÃO (DECRESCENTE)</option>
                    <option value="5">MEDIDA (CRESCENTE)</option>
                    <option value="6">MEDIDA (DECRESCENTE)</option>
                    <option value="7">PREÇO (CRESCENTE)</option>
                    <option value="8">PREÇO (DECRESCENTE)</option>
                    <option value="9">REPRESENTAÇÃO (CRESCENTE)</option>
                    <option value="10">REPRESENTAÇÃO (DECRESCENTE)</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="novo">&nbsp;</label>
                <a role="button" id="novo" class="btn btn-success btn-sm" style="width: 100%;" href="/representacoes/gerenciar/produto/novo">NOVO</a>
            </div>
        </div>

        <table id="table_produtos" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th style="width: 30%;">DESCRIÇÃO</th>
                <th style="width: 16%;">MEDIDA</th>
                <th style="width: 10%;">PREÇO</th>
                <th style="width: 20%;">REPRESENTAÇÂO</th>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 2%;">&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbody_produtos">
            </tbody>
        </table>
    </div>
</div>