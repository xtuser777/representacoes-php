<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Gerenciar Vínculos com Tipos de Caminhão';
    $section_container = '/src/view/gerenciar/produto/tipocaminhao.php';
    $section_scripts = '/src/view/gerenciar/produto/tipocaminhao_scripts.php';

    require ROOT . '/src/view/layout.php';
}