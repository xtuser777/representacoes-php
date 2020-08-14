<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Gerenciar Formas de Pagamento';
    $section_container = '/src/view/gerenciar/formapagamento/index.php';
    $section_scripts = '/src/view/gerenciar/formapagamento/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}