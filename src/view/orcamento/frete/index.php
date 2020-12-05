<?php if (!isset($_COOKIE['USER_ID'])) header('Location: /representacoes/login'); ?>

<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4>
            <b>SCR - Controlar Orçamentos de Frete</b>
        </h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem de Orçamentos</div>

    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-8">
                <label for="filtro">Filtro:</label>
                <input type="text" id="filtro" class="form-control input-sm" style="width: 100%;" placeholder="Filtrar por descrição..." />
            </div>

            <div class="col-sm-2">
                <label for="filtro_data">Filtro Data:</label>
                <input type="date" id="filtro_data" class="form-control input-sm" style="width: 100%;" />
            </div>

            <div class="col-sm-2">
                <label for="filtrar">&nbsp;</label>
                <button id="filtrar" class="btn btn-primary btn-sm" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card" style="margin-bottom: 40px;">
    <div class="fieldset-card-legend" style="width: 200px;">Orçamentos Abertos</div>

    <div class="fieldset-card-container">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-10">
                <label for="cbord">Ordenar por:</label>
                <select id="cbord" class="form-control input-sm" onchange="ordenar();">
                    <option value="1">REGISTRO (CRESCENTE)</option>
                    <option value="2">REGISTRO (DECRESCENTE)</option>
                    <option value="3">DESCRIÇÂO (CRESCENTE)</option>
                    <option value="4">DESCRIÇÂO (DECRESCENTE)</option>
                    <option value="5">CLIENTE (CRESCENTE)</option>
                    <option value="6">CLIENTE (DECRESCENTE)</option>
                    <option value="7">DATA (CRESCENTE)</option>
                    <option value="8">DATA (DECRESCENTE)</option>
                    <option value="9">ATOR (CRESCENTE)</option>
                    <option value="10">ATOR (DECRESCENTE)</option>
                    <option value="11">VALOR (CRESCENTE)</option>
                    <option value="12">VALOR (DECRESCENTE)</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="novo">&nbsp;</label>
                <a role="button" id="novo" class="btn btn-success btn-sm" style="width: 100%;" href="/representacoes/orcamento/frete/novo">NOVO</a>
            </div>
        </div>

        <table id="table_orcamentos" class="table table-responsive" style="width: 100%;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th>DESCRIÇÃO</th>
                <th>CLIENTE</th>
                <th>DATA</th>
                <th>AUTOR</th>
                <th>VALOR (R$)</th>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 2%;">&nbsp;</th>
            </tr>
            </thead>

            <tbody id="tbody_orcamentos">
            </tbody>
        </table>
    </div>
</div>