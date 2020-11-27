<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Eventos do Sistema</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<!-- Conteudo da pagina -->
<div class="fieldset-card">
    <div class="fieldset-card-legend">Filtragem dos Eventos</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-5">
                <label for="textFiltro">Filtro:</label>
                <input type="text" name="textFiltro" id="textFiltro" class="form-control" style="width: 100%;" value="" />
            </div>

            <div class="col-sm-2">
                <label for="dateEvento">Data dos Eventos:</label>
                <input type="date" name="dateEvento" id="dateEvento" class="form-control" style="width: 100%;" value="" />
            </div>

            <div class="col-sm-3">
                <label for="selectTipoPedido">Tipo do Pedido:</label>
                <select name="selectTipoPedido" id="selectTipoPedido" class="form-control" style="width: 100%;">
                    <option value="0">SELECIONE</option>
                    <option value="1">VENDA</option>
                    <option value="2">FRETE</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label for="btFiltrar">&nbsp;</label>
                <button name="btFiltrar" id="btFiltrar" class="btn btn-primary" style="width: 100%;" onclick="filtrar();">FILTRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend" style="width: 140px;">Eventos do Sistema</div>
    <div class="fieldset-card-container">
        <table id="tableEventos" class="table table-striped table-hover">
            <thead>
            <tr>
                <th>DESCRIÇÃO</th>
                <th>DATA</th>
                <th>HORA</th>
                <th>PEDIDO</th>
                <th>ATOR</th>
            </tr>
            </thead>

            <tbody id="tbodyEventos">
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <button id="btGerarPdf" class="btn btn-primary" style="width: 100%;">Gerar PDF</button>
    </div>
    <div class="col-sm-4"></div>
</div>
<!-- Fim conteudo da pagina -->