<?php

include("sqlConfig.php"); // импортируем файл с конфигурацией для подключения к бд

if ((isset($_COOKIE["login"])) && (isset($_COOKIE["password"]))) { // проверяем наличие куки у пользователя, чтобы по ним найти информацию в бд
  
    $login = $_COOKIE["login"];                                    // если каких-либо куков нет, то вывести информацию о плохом прохождении аутентификации и преостановить выполнение кода
    $passw = $_COOKIE["password"];                                 // если все есть, то занести информацию в соответствующие переменные  
}
else {
    header('Location: /login');
    exit;
}
 

$link = mysqli_connect($host, $user, $password, $db_name); // подключение к базе данных как раз по информации из конфигурации к бд

$table = 'employees'; //задаем имя таблицы в переменной

$query = "SELECT*FROM ".$table." WHERE login = '".$login."' AND password = '".$passw."'" ; // сам sql запрос к базе данных на поиск необходимого пользователя
$result = mysqli_query($link, $query) or die(mysqli_error($link));                         // отправка запроса. Если запрос выдает ошибку, то завершить выполнение с выводом данной ошибки ('die') 
 for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);                     //пройтись по результатам выборки в базе данных
if(count($data)<=0)
header('Location: /login');
else if($data[0]['login'] != $login)
header('Location: /login');
else{
    $log = $data;
}
?>