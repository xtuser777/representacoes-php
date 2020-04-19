<?php

if (session_status() !== PHP_SESSION_ACTIVE)
{
    session_cache_expire(1440);
    session_start();
}

if (isset($_SESSION['USER_ID']))
{
    header('Location: /inicio');
}
else
{
    header('Location: /login');
}