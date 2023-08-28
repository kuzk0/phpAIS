<?php  include("./chekauth.php"); // проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа

$table = 'products'; //задаем имя таблицы в переменной


if(!isset($_GET['product'])&&!isset($_GET['page']))
exit;

$product =  $_GET['product'];
$page =  $_GET['page'];



$query = "SELECT * FROM ".$table." WHERE `name` LIKE '%".$product."%' OR `barcode` LIKE '%".$product."%' LIMIT ".(5*$page).",5;" ; // сам sql запрос к базе данных на поиск необходимого пользователя
$result = mysqli_query($link, $query) or die(
    // mysqli_error($link)
    "Error ".$query
);                         // отправка запроса. Если запрос выдает ошибку, то завершить выполнение с выводом данной ошибки ('die') 
 for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);                     //пройтись по результатам выборки в базе данных
if(count($data)<=0)
exit;
else 
print_r(json_encode($data, JSON_UNESCAPED_UNICODE))// выводим данные в json формате и устанавливаем кадировку в соответствии с unicode
?>