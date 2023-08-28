<?php  include("./chekauth.php"); // проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа


if(!isset($_GET['barcode']) && !isset($_GET['name']) && !isset($_GET['price']) && !isset($_GET['count']))
exit;

$barcode =  $_GET['barcode'];
$name =  $_GET['name'];
$price =  $_GET['price'];
$count =  $_GET['count'];




$query = "UPDATE `products` SET `name`='".$name."', `price`='".$price."', `count`='".$count."' WHERE `barcode` = ".$barcode."";// сам sql запрос к базе данных на поиск необходимого пользователя
$result = mysqli_query($link, $query) or die(
    // mysqli_error($link)
    "Error ".$query
);                         // отправка запроса. Если запрос выдает ошибку, то завершить выполнение с выводом данной ошибки ('die') 
if (mysqli_query($link, $query)) {
    echo(json_encode(array('result'=>'done')));
 } else {
   echo(json_encode(array('result'=>array("error"=>"Ошибка: " . $query . "<br>" . mysqli_error($link)))));
 }
