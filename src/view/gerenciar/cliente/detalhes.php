<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Detalhes do Cliente</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Tipo Cliente</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-12">
                <label for="tipo">Tipo do Cliente: </label>
                <input type="text" id="tipo" class="form-control input-sm" style="width: 100%;" disabled />
            </div>
        </div>
    </div>
</div>

<div id="fisica" class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Cliente</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-12">
                <label for="nome">Nome <span style="color: red;">*</span>:</label>
                <input type="text" id="nome" class="form-control input-sm" style="width: 100%;" />
                <div id="msnome"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="rg">RG <span style="color: red;">*</span>:</label>
                <input type="text" id="rg" class="form-control input-sm" style="width: 100%;" maxlength="30" />
                <div id="msrg"></div>
            </div>

            <div class="col-sm-4">
                <label for="cpf">CPF <span style="color: red;">*</span>:</label>
                <input type="text" id="cpf" class="form-control input-sm" style="width: 100%;" />
                <div id="mscpf"></div>
            </div>

            <div class="col-sm-4">
                <label for="nasc">Nascimento <span style="color: red;">*</span>:</label>
                <input type="date" id="nasc" class="form-control input-sm" style="width: 100%;" />
                <div id="msnasc"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div id="juridica" class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Cliente</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-12">
                <label for="razao_social">Razão Social <span style="color: red;">*</span>:</label>
                <input type="text" id="razao_social" class="form-control input-sm" style="width: 100%;" />
                <div id="msrs"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-9">
                <label for="nome_fantasia">Nome Fantasia <span style="color: red;">*</span>:</label>
                <input type="text" id="nome_fantasia" class="form-control input-sm" style="width: 100%;" />
                <div id="msnf"></div>
            </div>

            <div class="col-sm-3">
                <label for="cnpj">CNPJ <span style="color: red;">*</span>:</label>
                <input type="text" id="cnpj" class="form-control input-sm" style="width: 100%;" />
                <div id="mscnpj"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados de contato do cliente</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-9">
                <label for="rua">Rua <span style="color: red;">*</span>:</label>
                <input type="text" id="rua" class="form-control input-sm" style="width: 100%;" />
                <div id="msrua"></div>
            </div>

            <div class="col-sm-3">
                <label for="numero">Número <span style="color: red;">*</span>:</label>
                <input type="text" id="numero" class="form-control input-sm" style="width: 100%;" />
                <div id="msnumero"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="bairro">Bairro <span style="color: red;">*</span>:</label>
                <input type="text" id="bairro" class="form-control input-sm" style="width: 100%;" />
                <div id="msbairro"></div>
            </div>

            <div class="col-sm-6">
                <label for="complemento">Complemento:</label>
                <input type="text" name="complemento" id="complemento" class="form-control input-sm" style="width: 100%;" />
                <div id="mscomplemento"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="cbestado">Estado <span style="color: red;">*</span>:</label>
                <select id="cbestado" class="form-control input-sm" style="width: 100%;" onchange="onCbEstadoChange();">
                    <option value="0">SELECIONAR</option>
                </select>
                <div id="msestado"></div>
            </div>

            <div class="col-sm-5">
                <label for="cbcidade">Cidade <span style="color: red;">*</span>:</label>
                <select id="cbcidade" class="form-control input-sm" style="width: 100%;">
                    <option value="0">SELECIONAR</option>
                </select>
                <span id="mscidade" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-3">
                <label for="cep">CEP <span style="color: red;">*</span>:</label>
                <input type="text" id="cep" class="form-control input-sm" style="width: 100%;" />
                <div id="mscep"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="tel">Telefone <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span></div>
                    <input type="text" id="tel" class="form-control input-sm" />
                </div>
                <div id="mstel"></div>
            </div>

            <div class="col-sm-3">
                <label for="cel">Celular <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span></div>
                    <input type="text" id="cel" class="form-control input-sm" />
                </div>
                <div id="mscel"></div>
            </div>

            <div class="col-sm-6">
                <label for="email">E-Mail <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon">@</div>
                    <input type="text" id="email" class="form-control input-sm" />
                </div>
                <div id="msemail"></div>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <a role="button" id="voltar" class="btn btn-default" style="width: 100%;" href="/gerenciar/cliente">VOLTAR</a>
    </div>

    <div class="col-sm-8"></div>

    <div class="col-sm-2">
        <button id="btSalvar" class="btn btn-success" style="width: 100%;" onclick="gravar();">SALVAR</button>
    </div>
</div>