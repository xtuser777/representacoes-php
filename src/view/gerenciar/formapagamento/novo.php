<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Cadastrar Nova Forma de Pagamento</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Forma de Pagamento</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="desc">Descrição <span style="color: red;">*</span>:</label>
                <input type="text" id="desc" class="form-control input-sm" style="width: 100%;" value="" />
                <div id="msdesc"></div>
            </div>

            <div class="col-sm-3">
                <label for="vinculo">Vínculo <span style="color: red;">*</span>:</label>
                <select id="vinculo" class="form-control input-sm">
                    <option value="0">SELECIONE</option>
                    <option value="1">CONTA A PAGAR</option>
                    <option value="2">CONTA A RECEBER</option>
                </select>
                <div id="msvinculo"></div>
            </div>

            <div class="col-sm-3">
                <label for="prazo">Prazo (dias) <span style="color: red;">*</span>:</label>
                <input type="number" id="prazo" class="form-control input-sm" style="width: 100%;" value="0" />
                <div id="msprazo"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <a role="button" class="btn btn-default" style="width: 100%;" href="/representacoes/gerenciar/formapagamento">VOLTAR</a>
    </div>

    <div class="col-sm-6"></div>

    <div class="col-sm-2">
        <button id="limpar" class="btn btn-primary" style="width: 100%;" onclick="limpar();">LIMPAR</button>
    </div>

    <div class="col-sm-2">
        <button id="salvar" class="btn btn-success" style="width: 100%;" onclick="gravar();">SALVAR</button>
    </div>
</div>