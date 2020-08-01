<?php
session_start();
require_once ('libs/func.php');

// Если user == out то выходим из системы
userExit($_GET['user']);
?>
<h1>Здравствуйте <?= ($_SESSION['login']) ? ucfirst($_SESSION['login']) : ucfirst($_COOKIE['login']);?></h1>
<a href="/personal.php?user=out"> Выйти из системы</a>
