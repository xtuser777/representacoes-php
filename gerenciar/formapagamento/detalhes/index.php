<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Detalhes da Forma de Pagamento';
    $section_container = '/src/view/gerenciar/formapagamento/detalhes.php';
    $section_scripts = '/src/view/gerenciar/formapagamento/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}