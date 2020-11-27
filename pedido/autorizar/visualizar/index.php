<?php

require '../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif ($_COOKIE["USER_LEVEL"] !== "1") {
    header("Location: /representacoes/login/denied");
} else {
    $page_title = 'Autorizar Carregamento de Pedidos de Frete';
    $section_container = '/src/view/pedido/autorizar/visualizar.php';
    $section_scripts = '/src/view/pedido/autorizar/visualizar_scripts.php';

    require ROOT . '/src/view/layout.php';
}