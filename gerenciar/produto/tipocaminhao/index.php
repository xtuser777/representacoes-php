<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login/index.php');
} else {
    $page_title = 'Gerenciar Vínculos com Tipos de Caminhão';
    $section_container = '/src/view/gerenciar/produto/tipocaminhao.php';
    $section_scripts = '/src/view/gerenciar/produto/tipocaminhao_scripts.php';

    require ROOT . '/src/view/layout.php';
}