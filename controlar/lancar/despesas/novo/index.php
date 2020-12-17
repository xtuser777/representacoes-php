<?php

require '../../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_COOKIE["USER_LEVEL"] === "3") {
    header('Location: /representacoes/login/denied');
} else {
    $page_title = "Lançar Nova Despesa";
    $section_container = "/src/view/controlar/lancar/despesas/novo.php";
    $section_scripts = '/src/view/controlar/lancar/despesas/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}