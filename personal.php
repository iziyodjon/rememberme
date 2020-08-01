<?php
session_start();
require_once ('libs/func.php');

// Если user == out то выходим из системы
if($_GET['user'] == 'out')
{
    $_SESSION['login'] = '';
    $_SESSION['auth'] = 0;
    setcookie("login","",time()-3600);
    setcookie("key","",time()-3600);
    header('Location: /');
}

?>
<h1>Здравствуйте <?= ($_SESSION['login']) ? ucfirst($_SESSION['login']) : ucfirst($_COOKIE['login']);?></h1>
<a href="/personal.php?user=out"> Выйти из системы</a>
