insert into endereco(end_rua,end_numero,end_bairro,end_complemento,end_cep,cid_id)
values('Clarinho','165','Martins','','19.600-000',5181);

insert into contato(ctt_telefone,ctt_celular,ctt_email,end_id)
values('(11) 2222-3333','(11) 23333-4444','lucas@mail.com',1);

insert into pessoa_fisica(pf_nome,pf_rg,pf_cpf,pf_nascimento,ctt_id)
values('Lucas','11.222.333-4','430.987.568-88','1994-05-06',1);

insert into funcionario(fun_tipo,fun_admissao,fun_demissao,pf_id)
values(1,now(),null,1);

insert into nivel(niv_descricao)
values('Administrador'),('Gerente'),('Operacional');

insert into usuario(usu_login,usu_senha,usu_ativo,fun_id,niv_id)
values('lucas','121314',true,1,1);