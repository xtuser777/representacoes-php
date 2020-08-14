<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Nova Forma de Pagamento';
    $section_container = '/src/view/gerenciar/formapagamento/novo.php';
    $section_scripts = '/src/view/gerenciar/formapagamento/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}