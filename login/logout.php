<?php

require_once '../header.php';

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
    session_abort();
}

header('Location: /login');