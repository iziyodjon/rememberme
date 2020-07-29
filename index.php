<?php
session_start();
require_once ('libs/func.php');

if(!empty($_SESSION['login']) and $_SESSION['auth'] == true)
{
    header('Location: /personal.php');
}

// Проверка куки
checkCookie();

// Получаем данные из формы
$login = $_POST['login'];
$password = $_POST['password'];
$remember = $_POST['remember'];

// Авторизация юзера
userAuth($login,$password,$remember);


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Remember Me</title>
</head>
<body>
<div align="center">
    <form name="form" action="" method="post" class="form">
        <label>Логин</label>
        <br>
        <input class="inputlp" type="text" name="login" >
        <br><br>
        <label>Пароль</label>
        <br>
        <input class="inputlp" type="password" name="password" >
        <br>
        <input type="checkbox" name="remember" value="1"> Запомнить меня
        <br>
        <input type="submit" id="submit" class="input_button-" value="Войти">
    </form>
</div>
</body>
</html>
