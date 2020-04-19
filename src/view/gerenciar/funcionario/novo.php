<!-- Card titulo pagina -->
<div class="card-title">
    <div class="card-title-container" style="text-align: center;">
        <h4><b>SCR - Cadastrar Novo Funcionário</b></h4>
    </div>
</div>
<!-- Fim card titulo pagina -->

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados do Funcionário</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-9">
                <label for="txNome">Nome <span style="color: red;">*</span>:</label>
                <input type="text" id="txNome" class="form-control input-sm" style="width: 100%;" />
                <span id="msNome" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-3">
                <label for="dtNasc">Nascimento <span style="color: red;">*</span>:</label>
                <input type="date" id="dtNasc" class="form-control input-sm" style="width: 100%;" />
                <span id="msNasc" class="label label-danger hidden"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="txRg">RG <span style="color: red;">*</span>:</label>
                <input type="text" id="txRg" class="form-control input-sm" style="width: 100%;" maxlength="30" />
                <span id="msRg" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-3">
                <label for="txCpf">CPF <span style="color: red;">*</span>:</label>
                <input type="text" id="txCpf" class="form-control input-sm" style="width: 100%;" />
                <span id="msCpf" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-3">
                <label for="dtAdm">Admissão <span style="color: red;">*</span>:</label>
                <input type="date" id="dtAdm" class="form-control input-sm" style="width: 100%;" />
                <span id="msAdm" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-3">
                <label for="cbTipo">Tipo <span style="color: red;">*</span>:</label>
                <select id="cbTipo" class="form-control input-sm" style="width: 100%;">
                    <option value="0">SELECIONE</option>
                    <option value="1">INTERNO</option>
                    <option value="2">VENDEDOR</option>
                </select>
                <span id="msTipo" class="label label-danger hidden"></span>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="fieldset-card">
    <div class="fieldset-card-legend">Dados de contato do funcionario</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-9">
                <label for="txRua">Rua <span style="color: red;">*</span>:</label>
                <input type="text" name="txRua" id="txRua" class="form-control input-sm" style="width: 100%;" />
                <span id="msRua" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-3">
                <label for="txNumero">Número <span style="color: red;">*</span>:</label>
                <input type="text" name="txNumero" id="txNumero" class="form-control input-sm" style="width: 100%;" />
                <span id="msNumero" class="label label-danger hidden"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="txBairro">Bairro <span style="color: red;">*</span>:</label>
                <input type="text" name="txBairro" id="txBairro" class="form-control input-sm" style="width: 100%;" />
                <span id="msBairro" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-6">
                <label for="txComplemento">Complemento:</label>
                <input type="text" name="txComplemento" id="txComplemento" class="form-control input-sm" style="width: 100%;" />
                <span id="msComplemento" class="label label-danger hidden"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label for="cbestado">Estado <span style="color: red;">*</span>:</label>
                <select id="cbestado" class="form-control input-sm" style="width: 100%;">
                    <option value="0">SELECIONAR</option>
                </select>
                <span id="msEstado" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-5">
                <label for="cbcidade">Cidade <span style="color: red;">*</span>:</label>
                <select id="cbcidade" class="form-control input-sm" style="width: 100%;">
                    <option value="0">SELECIONAR</option>
                </select>
                <span id="msCidade" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-3">
                <label for="txCep">CEP <span style="color: red;">*</span>:</label>
                <input type="text" name="txCep" id="txCep" class="form-control input-sm" style="width: 100%;" />
                <span id="msCep" class="label label-danger hidden"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label for="txTel">Telefone <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span></div>
                    <input type="text" id="txTel" class="form-control input-sm" />
                </div>
                <span id="msTelefone" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-3">
                <label for="txCel">Celular <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span></div>
                    <input type="text" id="txCel" class="form-control input-sm" />
                </div>
                <span id="msCelular" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-6">
                <label for="txEmail">E-Mail <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon">@@</div>
                    <input type="text" id="txEmail" class="form-control input-sm" />
                </div>
                <span id="msEmail" class="label label-danger hidden"></span>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div id="auth" class="fieldset-card">
    <div class="fieldset-card-legend">Dados de autenticação</div>
    <div class="fieldset-card-container">
        <div class="row">
            <div class="col-sm-6">
                <label for="cbNivel">Nível <span style="color: red;">*</span>:</label>
                <select name="cbNivel" id="cbNivel" class="form-control input-sm" style="width: 100%;">
                    <option value="0">SELECIONAR</option>
                </select>
                <span id="msNivel" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-6">
                <label for="txLogin">Login <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                    <input type="text" name="txLogin" id="txLogin" class="form-control input-sm" value="" />
                </div>
                <span id="msLogin" class="label label-danger hidden"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="txSenha">Senha <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
                    <input type="password" name="txSenha" id="txSenha" class="form-control input-sm" value="" />
                </div>
                <span id="msSenha" class="label label-danger hidden"></span>
            </div>

            <div class="col-sm-6">
                <label for="txConfSenha">Confirmar Senha <span style="color: red;">*</span>:</label>
                <div class="input-group" style="width: 100%;">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
                    <input type="password" name="txConfSenha" id="txConfSenha" class="form-control input-sm" value="" />
                </div>
                <span id="msConfSenha" class="label label-danger hidden"></span>
            </div>
        </div>

        <div class="fieldset-card-legend-obg">* Campos de preenchimento obrigatório.</div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">
        <button id="btVoltar" class="btn btn-default" style="width: 100%;">VOLTAR</button>
    </div>

    <div class="col-sm-6"></div>

    <div class="col-sm-2">
        <button id="btLimpar" class="btn btn-primary" style="width: 100%;">LIMPAR</button>
    </div>

    <div class="col-sm-2">
        <button id="btSalvar" class="btn btn-success" style="width: 100%;">SALVAR</button>
    </div>
</div>