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

// Авторизация пользователя по логин пароль, запомнить меня
function userAuth($login = '',$userpass = '',$remember = '')
{
    // Подключения к БД
    $pdo = PDO();

    if(!empty($login) and !empty($userpass))
    {
        $userpass = md5($userpass);
        $bdpass = checkPassByLogin($login);

        if($userpass == $bdpass)
        {
            $_SESSION['auth'] = true;
            $_SESSION['login'] = $login;

            // Если кнопка запомнить меня нажата
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

    // Если в сессии login и auth или в куки login и token есть данные то редерект на personal.php
    if(!empty($_COOKIE['key']) or $_SESSION['auth'] == true)
    {
        header('Location: /personal.php');
    }


}

// Проверка паролья по логину
function checkPassByLogin($login)
{
    // Подключения к БД
    $pdo = PDO();

    $stmt = $pdo->prepare("SELECT password FROM users WHERE login=:login");
    $stmt->execute(['login' => $login]);
    $pass = $stmt->fetchColumn();
    return $pass;

}

// Шифрование токена
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

function userExit($get)
{
    // Если user == out то выходим из системы
    if($get == 'out')
    {
        $_SESSION['login'] = '';
        $_SESSION['auth'] = 0;
        setcookie("login","",time()-3600);
        setcookie("key","",time()-3600);
        header('Location: /');
    }
}