<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login/index.php');
}
elseif (strcmp($_SESSION['USER_LEVEL'], '1') !== 0)
{
    header('Location: /login/denied.php');
}
else
{
    $page_title = 'Gerenciar Funcionários';
    $section_container = '/src/view/gerenciar/funcionario/index.php';
    $section_scripts = '/src/view/gerenciar/funcionario/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}