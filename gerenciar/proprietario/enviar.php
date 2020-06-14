<?php

require '../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} else {
    $id = $_POST['id'];

    header('Content-type: application/json');
    echo (new scr\control\ProprietarioControl())->enviar($id);
}