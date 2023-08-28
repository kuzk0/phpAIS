<?php 




// проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа




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
else
$employee = $data[0]['employee_id'];








/**
 * Проверяем на наличие всех присланных данных
 * если каких-либо нет, прерываем действие кода 
 */

if(!isset($_POST['barcode']) && !isset($_POST['name']) && !isset($_POST['price']) && !isset($_POST['count']))
exit;

$barcode =  $_POST['barcode'];
$name =  $_POST['name'];
$price =  $_POST['price'];
$count =  $_POST['count'];






$query = "INSERT INTO `products`(`name`, `price`, `count`, `barcode`) " 
    ." VALUES ('".$name."','".$price."', '".$count."','".$barcode."')";
    
    
if (mysqli_query($link, $query)) {
    echo(json_encode(array('result'=>'done')));
 } else {
   echo(json_encode(array('result'=>array("error"=>"Ошибка: " . $query . "<br>" . mysqli_error($link)))));
 }
