<?php
// DB connection
require_once ('libs/Db.php');

// Debug array
function dd($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function userAuth($login,$userpass,$remember = '')
{
    // Подключения к БД
    $pdo = PDO();

    $userpass = md5($userpass);
    $bdpass = checkPassByLogin($login);

    if($userpass == $bdpass)
    {
        $_SESSION['auth'] = true;
        $_SESSION['login'] = $login;

        // Если кнопк запомнить меня нажата
        if(!empty($remember) and $remember == 1)
        {
            // Генерация token а
            $key = crypToken($userpass);

            // Пишем куки (имя куки, значение, время жизни - сейчас+месяц)
            setcookie('login', $login, time()+60*60*24*30); //логин
            setcookie('key', $key, time()+60*60*24*30); //случайная строка

            $data = [
                'token' => $key,
                'login' => $login
            ];


            $sql = "UPDATE users SET token =:token WHERE login =:login";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
        }


    }
}

function checkCookie()
{
    // Подключения к БД
    $pdo = PDO();

    if (empty($_SESSION['auth']) or $_SESSION['auth'] == false)
    {
        if(!empty($_COOKIE['login']) and !empty($_COOKIE['key']))
        {
            $login = $_COOKIE['login'];
            $key = $_COOKIE['key'];

            $stmt = $pdo->prepare("SELECT * FROM users WHERE login=:login AND token=:token");
            $stmt->execute(['login' => $login,'token' => $key]);
            $res = $stmt->fetchColumn();

            if (!empty($res))
            {
                // Обновляем сессию
                $_SESSION['auth'] = true;
                $_SESSION['login'] = $login;
            }
        }
    }
}

function checkPassByLogin($login)
{
    // Подключения к БД
    $pdo = PDO();

    $stmt = $pdo->prepare("SELECT password FROM users WHERE login=:login");
    $stmt->execute(['login' => $login]);
    $pass = $stmt->fetchColumn();
    return $pass;

}
function crypToken($password)
{
    if(!empty($password))
    {
        $pass = md5($password);
        $ip = $_SERVER['REMOTE_ADDR'];
        $token = $ip.$pass;

        return $token;
    }
}