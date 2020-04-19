<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="/static/images/logo.png">
    <title>Autenticação do Usuário - Sistema de Controle de Representações</title>
    <link rel="stylesheet" type="text/css" href="/static/lib/bootstrap/dist/css/bootstrap.css">
    <style type="text/css">
        .container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            background: rgb(0,120,212);
        }
        .box {
            border-radius: 10px;
            width: 300px;
            padding: 50px 10px 20px 10px;
            background: white;
        }
        body {
            margin: 0px;
            padding: 0px;
        }
        .btn-login {
            width: 100%;
        }
        .logo {
            background-image: url("../static/images/logo-personalizado.png");
            background-size: cover;
            background-position: center;
            height: 150px;
            width: 150px;
            margin: auto;
            padding: 0px;
            border-radius: 0px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="box">
        <div class="logo "></div>
        <br />
        <br />
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" name="login" id="login" class="form-control" />
            <span id="msgLogin" class="label label-danger hidden"></span>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control" />
            <span id="msgSenha" class="label label-danger hidden"></span>
        </div>
        <input type="button" id="btnEntrar" class="btn btn-login btn-primary" value="ENTRAR" />
        <span id="msgAutenticacao" class="label label-danger hidden"></span>
    </div>
</div>
<script type="text/javascript" src="/static/lib/jquery/dist/jquery.js"></script>
<script type="text/javascript" src="/static/lib/bootstrap/dist/js/bootstrap.js"></script>
<script type="text/javascript" src="/static/js/login/index.js"></script>
</body>
</html>