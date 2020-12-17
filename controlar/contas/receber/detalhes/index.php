<?php

require '../../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_COOKIE["USER_LEVEL"] === "3") {
    header('Location: /representacoes/login/denied');
} else {
    $page_title = "Detalhes da Contas a Receber";
    $section_container = "/src/view/controlar/contas/receber/detalhes.php";
    $section_scripts = '/src/view/controlar/contas/receber/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}