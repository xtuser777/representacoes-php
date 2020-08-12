<?php

require '../header.php';

setcookie("USER_ID", "", time() - 1, "/", "", 0, 1);
setcookie("USER_LOGIN", "", time() - 1, "/", "", 0, 1);
setcookie("USER_LEVEL", "", time() - 1, "/", "", 0, 1);

header('Location: /representacoes/login');