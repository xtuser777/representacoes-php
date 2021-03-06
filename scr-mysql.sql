CREATE TABLE estado
(
    est_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    est_nome VARCHAR(50) NOT NULL,
    est_sigla VARCHAR(2) NOT NULL
);

CREATE TABLE cidade
(
    cid_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cid_nome VARCHAR(50) NOT NULL,
    est_id INTEGER NOT NULL,
    FOREIGN KEY (est_id) REFERENCES estado(est_id)
);

CREATE TABLE endereco
(
    end_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    end_rua VARCHAR(70) NOT NULL,
    end_numero VARCHAR(10) NOT NULL,
    end_bairro VARCHAR(50) NOT NULL,
    end_complemento VARCHAR(40),
    end_cep VARCHAR(10) NOT NULL,
    cid_id INTEGER NOT NULL,
    FOREIGN KEY (cid_id) REFERENCES cidade(cid_id)
);

CREATE TABLE contato
(
    ctt_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ctt_telefone VARCHAR(14) NOT NULL,
    ctt_celular VARCHAR(16) NOT NULL,
    ctt_email VARCHAR(70) NOT NULL,
    end_id INTEGER NOT NULL,
    FOREIGN KEY (end_id) REFERENCES endereco(end_id)
);

CREATE TABLE pessoa_fisica
(
    pf_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    pf_nome VARCHAR(70) NOT NULL,
    pf_rg VARCHAR(30) NOT NULL,
    pf_cpf VARCHAR(16) NOT NULL,
    pf_nascimento DATE NOT NULL,
    ctt_id INTEGER NOT NULL,
    FOREIGN KEY (ctt_id) REFERENCES contato(ctt_id)
);

CREATE TABLE pessoa_juridica
(
    pj_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    pj_razao_social VARCHAR(90) NOT NULL,
    pj_nome_fantasia VARCHAR(70) NOT NULL,
    pj_cnpj VARCHAR(18) NOT NULL,
    ctt_id INTEGER NOT NULL,
    FOREIGN KEY (ctt_id) REFERENCES contato(ctt_id)
);

CREATE TABLE funcionario
(
    fun_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fun_tipo INTEGER NOT NULL,
    fun_admissao DATE NOT NULL,
    fun_demissao DATE,
    pf_id INTEGER NOT NULL,
    FOREIGN KEY (pf_id) REFERENCES pessoa_fisica(pf_id)
);

CREATE TABLE nivel
(
    niv_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    niv_descricao VARCHAR(30) NOT NULL
);

CREATE TABLE usuario
(
    usu_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    usu_login VARCHAR(15) NOT NULL,
    usu_senha VARCHAR(15) NOT NULL,
    usu_ativo BOOL NOT NULL,
    fun_id INTEGER NOT NULL,
    niv_id INTEGER NOT NULL,
    FOREIGN KEY (fun_id) REFERENCES funcionario(fun_id),
    FOREIGN KEY (niv_id) REFERENCES nivel(niv_id)
);

CREATE TABLE parametrizacao
(
    par_id INTEGER NOT NULL PRIMARY KEY,
    par_logotipo VARCHAR(255),
    pj_id INTEGER NOT NULL,
    FOREIGN KEY (pj_id) REFERENCES pessoa_juridica(pj_id)
);

CREATE TABLE cliente
(
    cli_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cli_cadastro DATE NOT NULL,
    cli_tipo INTEGER NOT NULL
);

CREATE TABLE cliente_pessoa_fisica
(
    cli_id INTEGER NOT NULL,
    pf_id INTEGER NOT NULL,
    PRIMARY KEY (cli_id, pf_id),
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id),
    FOREIGN KEY (pf_id) REFERENCES pessoa_fisica(pf_id)
);

CREATE TABLE cliente_pessoa_juridica
(
    cli_id INTEGER NOT NULL,
    pj_id INTEGER NOT NULL,
    PRIMARY KEY (cli_id, pj_id),
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id),
    FOREIGN KEY (pj_id) REFERENCES pessoa_juridica(pj_id)
);

CREATE TABLE representacao
(
    rep_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    rep_cadastro DATE NOT NULL,
    rep_unidade VARCHAR(60) NOT NULL,
    pj_id INTEGER NOT NULL,
    FOREIGN KEY (pj_id) REFERENCES pessoa_juridica(pj_id)
);

CREATE TABLE tipo_caminhao
(
    tip_cam_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tip_cam_descricao VARCHAR(70) NOT NULL,
    tip_cam_eixos INTEGER NOT NULL,
    tip_cam_capacidade DECIMAL(10,2) NOT NULL
);

CREATE TABLE produto
(
    pro_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    pro_descricao VARCHAR(70) NOT NULL,
    pro_medida VARCHAR(40) NOT NULL,
    pro_peso DECIMAL(10,1) NOT NULL,
    pro_preco DECIMAL(10,2) NOT NULL,
    pro_preco_out DECIMAL(10,2),
    rep_id INTEGER NOT NULL,
    FOREIGN KEY (rep_id) REFERENCES representacao(rep_id)
);

CREATE TABLE produto_tipo_caminhao
(
    pro_id INTEGER NOT NULL,
    tip_cam_id INTEGER NOT NULL,
    PRIMARY KEY (pro_id, tip_cam_id),
    FOREIGN KEY (pro_id) REFERENCES produto(pro_id),
    FOREIGN KEY (tip_cam_id) REFERENCES tipo_caminhao(tip_cam_id)
);

CREATE TABLE categoria_conta_pagar
(
    cat_con_pag_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cat_con_pag_descricao VARCHAR(255) NOT NULL
);

CREATE TABLE forma_pagamento
(
    for_pag_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    for_pag_descricao VARCHAR(60) NOT NULL,
    for_pag_vinculo INTEGER NOT NULL,
    for_pag_prazo INTEGER NOT NULL
);

CREATE TABLE dados_bancarios
(
    dad_ban_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    dad_ban_banco VARCHAR(5) NOT NULL,
    dad_ban_agencia VARCHAR(6) NOT NULL,
    dad_ban_conta VARCHAR(12) NOT NULL,
    dad_ban_tipo INTEGER NOT NULL
);

CREATE TABLE motorista
(
    mot_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    mot_cadastro DATE NOT NULL,
    mot_cnh VARCHAR(255) NOT NULL,
    pf_id INTEGER NOT NULL,
    dad_ban_id INTEGER NOT NULL,
    FOREIGN KEY (pf_id) REFERENCES pessoa_fisica(pf_id),
    FOREIGN KEY (dad_ban_id) REFERENCES dados_bancarios(dad_ban_id)
);

CREATE TABLE orcamento_venda
(
    orc_ven_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    orc_ven_descricao VARCHAR(100) NOT NULL,
    orc_ven_data DATE NOT NULL,
    orc_ven_nome_cliente VARCHAR(70) NOT NULL,
    orc_ven_documento_cliente VARCHAR(18) NOT NULL,
    orc_ven_telefone_cliente VARCHAR(14) NOT NULL,
    orc_ven_celular_cliente VARCHAR(16) NOT NULL,
    orc_ven_email_cliente VARCHAR(70) NOT NULL,
    orc_ven_peso DECIMAL(10,2) NOT NULL,
    orc_ven_valor DECIMAL(10,2) NOT NULL,
    orc_ven_validade DATE NOT NULL,
    fun_id INTEGER,
    cid_id INTEGER NOT NULL,
    usu_id INTEGER NOT NULL,
    cli_id INTEGER,
    FOREIGN KEY (fun_id) REFERENCES funcionario(fun_id),
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id),
    FOREIGN KEY (usu_id) REFERENCES usuario(usu_id),
    FOREIGN KEY (cid_id) REFERENCES cidade(cid_id)
);

CREATE TABLE orcamento_frete
(
    orc_fre_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    orc_fre_descricao VARCHAR(100) NOT NULL,
    orc_fre_data DATE NOT NULL,
    orc_fre_distancia INTEGER NOT NULL,
    orc_fre_peso DECIMAL(10,2) NOT NULL,
    orc_fre_valor DECIMAL(10,2) NOT NULL,
    orc_fre_entrega DATE NOT NULL,
    orc_fre_validade DATE NOT NULL,
    orc_ven_id INTEGER,
    rep_id INTEGER,
    cli_id INTEGER NOT NULL,
    tip_cam_id INTEGER NOT NULL,
    cid_id INTEGER NOT NULL,
    usu_id INTEGER NOT NULL,
    FOREIGN KEY (orc_ven_id) REFERENCES orcamento_venda(orc_ven_id),
    FOREIGN KEY (rep_id) REFERENCES representacao(rep_id),
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id),
    FOREIGN KEY (tip_cam_id) REFERENCES tipo_caminhao(tip_cam_id),
    FOREIGN KEY (cid_id) REFERENCES cidade(cid_id),
    FOREIGN KEY (usu_id) REFERENCES usuario(usu_id)
);

CREATE TABLE orcamento_venda_produto
(
    orc_ven_id INTEGER NOT NULL,
    pro_id INTEGER NOT NULL,
    orc_ven_pro_quantidade INTEGER NOT NULL,
    orc_ven_pro_valor DECIMAL(10,2) NOT NULL,
    orc_ven_pro_peso DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (orc_ven_id, pro_id),
    FOREIGN KEY (orc_ven_id) REFERENCES orcamento_venda(orc_ven_id),
    FOREIGN KEY (pro_id) REFERENCES produto(pro_id)
);

CREATE TABLE orcamento_frete_produto
(
    orc_fre_id INTEGER NOT NULL,
    pro_id INTEGER NOT NULL,
    orc_fre_pro_quantidade INTEGER NOT NULL,
    orc_fre_pro_peso DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orc_fre_id) REFERENCES orcamento_frete(orc_fre_id),
    FOREIGN KEY (pro_id) REFERENCES produto(pro_id),
    PRIMARY KEY (orc_fre_id, pro_id)
);

CREATE TABLE proprietario
(
    prp_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    prp_cadastro DATE NOT NULL,
    prp_tipo INTEGER NOT NULL,
    mot_id INTEGER NOT NULL
);

CREATE TABLE proprietario_pessoa_fisica
(
    prp_id INTEGER NOT NULL,
    pf_id INTEGER NOT NULL,
    PRIMARY KEY (prp_id, pf_id),
    FOREIGN KEY (prp_id) REFERENCES proprietario(prp_id),
    FOREIGN KEY (pf_id) REFERENCES pessoa_fisica(pf_id)
);

CREATE TABLE proprietario_pessoa_juridica
(
    prp_id INTEGER NOT NULL,
    pj_id INTEGER NOT NULL,
    PRIMARY KEY (prp_id, pj_id),
    FOREIGN KEY (prp_id) REFERENCES proprietario(prp_id),
    FOREIGN KEY (pj_id) REFERENCES pessoa_juridica(pj_id)
);

CREATE TABLE caminhao
(
    cam_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cam_placa VARCHAR(10) NOT NULL,
    cam_marca VARCHAR(30) NOT NULL,
    cam_modelo VARCHAR(50) NOT NULL,
    cam_cor VARCHAR(30) NOT NULL,
    cam_ano_fabricacao VARCHAR(4) NOT NULL,
    cam_ano_modelo VARCHAR(4) NOT NULL,
    tip_cam_id INTEGER NOT NULL,
    prp_id INTEGER NOT NULL,
    FOREIGN KEY (tip_cam_id) REFERENCES tipo_caminhao(tip_cam_id),
    FOREIGN KEY (prp_id) REFERENCES proprietario(prp_id)
);

CREATE TABLE pedido_venda
(
    ped_ven_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ped_ven_data DATE NOT NULL,
    ped_ven_descricao VARCHAR(150) NOT NULL,
    ped_ven_peso DECIMAL(10,2) NOT NULL,
    ped_ven_valor DECIMAL(10,2) NOT NULL,
    fun_id INTEGER,
    cid_id INTEGER NOT NULL,
    orc_ven_id INTEGER,
    cli_id INTEGER NOT NULL,
    for_pag_id INTEGER NOT NULL,
    usu_id INTEGER NOT NULL,
    FOREIGN KEY (fun_id) REFERENCES funcionario(fun_id),
    FOREIGN KEY (cid_id) REFERENCES cidade(cid_id),
    FOREIGN KEY (orc_ven_id) REFERENCES orcamento_venda(orc_ven_id),
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id),
    FOREIGN KEY (for_pag_id) REFERENCES forma_pagamento(for_pag_id),
    FOREIGN KEY (usu_id) REFERENCES usuario(usu_id)
);

CREATE TABLE pedido_venda_produto
(
    ped_ven_id INTEGER NOT NULL,
    pro_id INTEGER NOT NULL,
    ped_ven_pro_quantidade INTEGER NOT NULL,
    ped_ven_pro_valor DECIMAL(10,2) NOT NULL,
    ped_ven_pro_peso DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (ped_ven_id) REFERENCES pedido_venda(ped_ven_id),
    FOREIGN KEY (pro_id) REFERENCES produto(pro_id),
    PRIMARY KEY (ped_ven_id, pro_id)
);

CREATE TABLE pedido_frete
(
    ped_fre_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ped_fre_data DATE NOT NULL,
    ped_fre_descricao VARCHAR(150) NOT NULL,
    ped_fre_distancia INTEGER NOT NULL,
    ped_fre_peso DECIMAL(10,2) NOT NULL,
    ped_fre_valor DECIMAL(10,2) NOT NULL,
    ped_fre_valor_motorista DECIMAL(10,2) NOT NULL,
    ped_fre_entrada_motorista DECIMAL(10,2) NOT NULL,
    ped_fre_entrega DATE NOT NULL,
    orc_fre_id INTEGER,
    ped_ven_id INTEGER,
    rep_id INTEGER,
    cli_id INTEGER NOT NULL,
    cid_id INTEGER NOT NULL,
    tip_cam_id INTEGER NOT NULL,
    cam_id INTEGER NOT NULL,
    prp_id INTEGER NOT NULL,
    mot_id INTEGER NOT NULL,
    for_pag_fre INTEGER NOT NULL,
    for_pag_mot INTEGER,
    usu_id INTEGER NOT NULL,
    FOREIGN KEY (orc_fre_id) REFERENCES orcamento_frete(orc_fre_id),
    FOREIGN KEY (ped_ven_id) REFERENCES pedido_venda(ped_ven_id),
    FOREIGN KEY (rep_id) REFERENCES representacao(rep_id),
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id),
    FOREIGN KEY (cid_id) REFERENCES cidade(cid_id),
    FOREIGN KEY (tip_cam_id) REFERENCES tipo_caminhao(tip_cam_id),
    FOREIGN KEY (cam_id) REFERENCES caminhao(cam_id),
    FOREIGN KEY (prp_id) REFERENCES proprietario(prp_id),
    FOREIGN KEY (mot_id) REFERENCES motorista(mot_id),
    FOREIGN KEY (for_pag_fre) REFERENCES forma_pagamento(for_pag_id),
    FOREIGN KEY (for_pag_mot) REFERENCES forma_pagamento(for_pag_id),
    FOREIGN KEY (usu_id) REFERENCES usuario(usu_id)
);

CREATE TABLE pedido_frete_produto (
    ped_fre_id INTEGER NOT NULL,
    pro_id INTEGER NOT NULL,
    ped_fre_pro_quantidade INTEGER NOT NULL,
    ped_fre_pro_peso INTEGER NOT NULL,
    PRIMARY KEY (ped_fre_id, pro_id),
    FOREIGN KEY (ped_fre_id) REFERENCES pedido_frete(ped_fre_id),
    FOREIGN KEY (pro_id) REFERENCES produto(pro_id)
);

CREATE TABLE conta_pagar
(
    con_pag_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    con_pag_conta INTEGER NOT NULL,
    con_pag_data DATE NOT NULL,
    con_pag_tipo INTEGER NOT NULL,
    con_pag_descricao VARCHAR(150) NOT NULL,
    con_pag_empresa VARCHAR(50) NOT NULL,
    con_pag_parcela INTEGER NOT NULL,
    con_pag_valor DECIMAL(10,2) NOT NULL,
    con_pag_situacao INTEGER NOT NULL,
    con_pag_comissao BOOLEAN DEFAULT FALSE NOT NULL,
    con_pag_vencimento DATE NOT NULL,
    con_pag_data_pagamento DATE,
    con_pag_valor_pago DECIMAL(10,2),
    con_pag_pendencia INTEGER,
    for_pag_id INTEGER,
    mot_id INTEGER,
    fun_id INTEGER,
    cat_con_pag_id INTEGER NOT NULL,
    ped_fre_id INTEGER,
    ped_ven_id INTEGER,
    usu_id INTEGER NOT NULL,
    FOREIGN KEY (con_pag_pendencia) REFERENCES conta_pagar(con_pag_id),
    FOREIGN KEY (for_pag_id) REFERENCES forma_pagamento(for_pag_id),
    FOREIGN KEY (mot_id) REFERENCES motorista(mot_id),
    FOREIGN KEY (fun_id) REFERENCES funcionario(fun_id),
    FOREIGN KEY (cat_con_pag_id) REFERENCES categoria_conta_pagar(cat_con_pag_id),
    FOREIGN KEY (ped_fre_id) REFERENCES pedido_frete(ped_fre_id),
    FOREIGN KEY (ped_ven_id) REFERENCES pedido_venda(ped_ven_id),
    FOREIGN KEY (usu_id) REFERENCES usuario(usu_id)
);

CREATE TABLE conta_receber (
    con_rec_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    con_rec_data DATE NOT NULL,
    con_rec_conta INTEGER NOT NULL,
    con_rec_descricao VARCHAR(255) NOT NULL,
    con_rec_pagador VARCHAR(255) NOT NULL,
    con_rec_valor DECIMAL(10,2) NOT NULL,
    con_rec_situacao INTEGER NOT NULL,
    con_rec_comissao BOOLEAN DEFAULT FALSE NOT NULL,
    con_rec_vencimento DATE NOT NULL,
    con_rec_valor_recebido DECIMAL(10,2),
    con_rec_data_recebimento DATE,
    con_rec_pendencia INTEGER,
    for_pag_id INTEGER,
    rep_id INTEGER,
    ped_ven_id INTEGER,
    ped_fre_id INTEGER,
    usu_id INTEGER NOT NULL,
    FOREIGN KEY (con_rec_pendencia) REFERENCES conta_receber(con_rec_id),
    FOREIGN KEY (for_pag_id) REFERENCES forma_pagamento(for_pag_id),
    FOREIGN KEY (rep_id) REFERENCES representacao(rep_id),
    FOREIGN KEY (ped_ven_id) REFERENCES pedido_venda(ped_ven_id),
    FOREIGN KEY (ped_fre_id) REFERENCES pedido_frete(ped_fre_id),
    FOREIGN KEY (usu_id) REFERENCES usuario(usu_id)
);

CREATE TABLE evento (
    evt_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    evt_descricao VARCHAR(255) NOT NULL,
    evt_data DATE NOT NULL,
    evt_hora TIME NOT NULL,
    ped_ven_id INTEGER,
    ped_fre_id INTEGER,
    usu_id INTEGER NOT NULL,
    FOREIGN KEY (usu_id) REFERENCES usuario(usu_id)
);

CREATE TABLE status (
    sts_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sts_descricao VARCHAR(255) NOT NULL
);

CREATE TABLE pedido_frete_status (
    ped_fre_id INTEGER NOT NULL,
    sts_id INTEGER NOT NULL,
    ped_fre_sts_data DATE NOT NULL,
    ped_fre_sts_hora TIME NOT NULL,
    ped_fre_sts_observacoes VARCHAR(255),
    ped_fre_sts_atual BOOLEAN NOT NULL,
    usu_id INTEGER NOT NULL,
    PRIMARY KEY (ped_fre_id, sts_id),
    FOREIGN KEY (ped_fre_id) REFERENCES pedido_frete(ped_fre_id),
    FOREIGN KEY (sts_id) REFERENCES status(sts_id),
    FOREIGN KEY (usu_id) REFERENCES usuario(usu_id)
);

CREATE TABLE etapa_carregamento (
    eta_car_id INTEGER NOT NULL AUTO_INCREMENT,
    ped_fre_id INTEGER NOT NULL,
    eta_car_ordem INTEGER NOT NULL,
    eta_car_status INTEGER NOT NULL,
    eta_car_carga DECIMAL(10,2) NOT NULL,
    rep_id INTEGER NOT NULL,
    PRIMARY KEY (eta_car_id, ped_fre_id),
    FOREIGN KEY (rep_id) REFERENCES representacao(rep_id),
    FOREIGN KEY (ped_fre_id) REFERENCES pedido_frete(ped_fre_id)
);