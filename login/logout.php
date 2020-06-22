<?php

require '../header.php';

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
    session_abort();
}

header('Location: /representacoes/login');