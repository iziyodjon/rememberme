<?php
session_start();

if($_GET['user'] == 'out')
{
    $_SESSION['login'] = '';
    $_SESSION['auth'] = false;
}

if (empty($_SESSION['auth']) or $_SESSION['auth'] == false)
{
    header('Location: /');
}

?>

<a href="/personal.php?user=out">Выйти из системы</a>
