<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login/index.php');
} else {
    $page_title = 'Detalhes do Tipo de Caminhão';
    $section_container = '/src/view/gerenciar/tipocaminhao/detalhes.php';
    $section_scripts = '/src/view/gerenciar/tipocaminhao/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}