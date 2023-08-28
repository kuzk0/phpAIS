<?php




// проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа




include("sqlConfig.php"); // импортируем файл с конфигурацией для подключения к бд
include 'temp.php';
if ((isset($_COOKIE["login"])) && (isset($_COOKIE["password"]))) { // проверяем наличие куки у пользователя, чтобы по ним найти информацию в бд

    $login = $_COOKIE["login"];                                    // если каких-либо куков нет, то вывести информацию о плохом прохождении аутентификации и преостановить выполнение кода
    $passw = $_COOKIE["password"];                                 // если все есть, то занести информацию в соответствующие переменные  
} else {
    header('Location: /login');
    exit;
}


$link = mysqli_connect($host, $user, $password, $db_name); // подключение к базе данных как раз по информации из конфигурации к бд

$table = 'employees'; //задаем имя таблицы в переменной

$query = "SELECT*FROM " . $table . " WHERE login = '" . $login . "' AND password = '" . $passw . "'"; // сам sql запрос к базе данных на поиск необходимого пользователя
$result = mysqli_query($link, $query) or die(mysqli_error($link));                         // отправка запроса. Если запрос выдает ошибку, то завершить выполнение с выводом данной ошибки ('die') 
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);                     //пройтись по результатам выборки в базе данных
if (count($data) <= 0)
    header('Location: /login');
else if ($data[0]['login'] != $login)
    header('Location: /login');
else
    $employee = $data[0]['employee_id'];








/**
 * Проверяем на наличие всех присланных данных
 * если каких-либо нет, выводим ошибку и прерываем действие кода 
 */
if (
    !isset($_POST['products_list']) &&
    !isset($_POST['amount'])  &&
    !isset($_POST['payment_form']) &&
    !isset($_POST['client_name'])  &&
    !isset($_POST['client_email'])
) {
    echo (json_encode(array('error' => "не все данные присланы")));
    exit;
} else {
    /**
     * если все есть, то записать все в перменные 
     */
    $products_list =  $_POST['products_list'];
    $amount =  $_POST['amount'];
    $payment_form =  $_POST['payment_form'];
    $client_name =  $_POST['client_name'];
    $client_email =  $_POST['client_email'];
}

$list = json_decode($products_list, true);
foreach ($list as $key => $value) {

    $query = "UPDATE `products` SET `count`= ((SELECT `count` WHERE `barcode` = " . $value['product_id'] . ") - " . $value['count']  . ") WHERE `barcode` = " . $value['product_id'] . "";
    mysqli_query($link, $query);
}




$query = "INSERT INTO `orders` (`products_list`, `amount`, `date`, `employee`,`client_name`,`client_email`, `payment_form`)"
    . " VALUES ('" . $products_list . "','" . $amount . "', CURRENT_TIMESTAMP,'" . $employee . "','" . $client_name . "','" . $client_email . "','" . $payment_form . "')";
if (mysqli_query($link, $query)) {










    $to  = "" . $client_email . "";













    $query = "SELECT `order_id`, `products_list`, `amount`, `date`, `name` AS 'employee_name', `client_name`, `client_email`, `type` AS 'payment_form'  FROM `orders`, employees, payment_forms WHERE employees.employee_id = orders.employee AND payment_forms.form_id = orders.payment_form AND `products_list` = '" . $products_list . "' AND `amount` = '" . $amount . "' AND `client_name` = '" . $client_name . "' AND `client_email` = '" . $client_email . "'";
    $result = mysqli_query($link, $query);
    // var_dump($result);
    // if(!$result){
    //     echo mysqli_error($link);
    // }
    // else
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);



    $message = '';
    foreach ($data as &$receipt) {














        $products = "";
        $products_array = json_decode($products_list, true);
        foreach ($products_array as &$product) {
            $products .= prod($product);
        };


        $message = (rec($receipt, $products));
        
    }











    $filename = "reciept.txt"; //Имя файла для прикрепления
    // $to = "abc@mail.ru"; //Кому
    $from = "def@gmail.com"; //От кого
    $subject = "Чек оплаты"; //Тема
    // $message = "Текстовое сообщение"; //Текст письма
    $boundary = "---"; //Разделитель
    /* Заголовки */
    $headers = "From: $from\nReply-To: $from\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"";
    $body = "--$boundary\n";
    /* Присоединяем текстовое сообщение */
    $body .= "Content-Type: text/plain; charset='UTF-8'\n";
    $body .= "Content-Transfer-Encoding: quoted-printablenn";
    // $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
    $body .= $message."\n";
    $body .= "--$boundary\n";
    // $file = fopen($filename, "r"); //Открываем файл
    // $text = fread($file, filesize($filename)); //Считываем весь файл
    $text = $message; //Считываем весь файл
    // fclose($file); //Закрываем файл
    /* Добавляем тип содержимого, кодируем текст файла и добавляем в тело письма */
    $body .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode($filename)."?=\n";
    $body .= "Content-Transfer-Encoding: base64\n";
    $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
    $body .= chunk_split(base64_encode($text))."\n";
    $body .= "--".$boundary ."--\n";
    mail($to, $subject, $body, $headers); //Отправляем письмо















   



// m
// Content-Type: multipart/alternative; boundary="00000000000054ad3d05dfdb89e0"

// --00000000000054ad3d05dfdb89e0
// Content-Type: text/plain; charset="UTF-8"
// Content-Transfer-Encoding: base64

// INCd0L7QstGL0Lkg0YjQsNCx0LvQvtC9IDIwMjItMDUtMjUNCg0K0J3QsNC60LvQsNC00L3QsNGP
// DQoNCtCU0LDRgtCwDQoNCjIwMjItMDMtMjcgMjM6NDE6MDANCg0K0JrQsNGB0YHQuNGAOg0KDQrQ
// mNCy0LDQvdC+0LIg0Jgu0JguDQoNCtCf0L7QutGD0L/QsNGC0LXQu9GMOg0KDQrQmNCy0LDQvdC+
// 0LIg0JjQstCw0L0g0JjQstCw0L3QvtCy0LjRhw0KDQpFbWFpbDoNCg0KaXZAbWFpbC5ydQ0KDQrQ
// otC40L8g0L7Qv9C70LDRgtGLOg0KDQrQndCw0LvQuNGH0L3Ri9C1DQoNCtCd0LDQuNC80LXQvdC+
// 0LLQsNC90LjQtQ0KDQrQptC10L3QsA0KDQrQmtC+0Lst0LLQvg0KDQrQodGD0LzQvNCwDQoNCtCd
// 0LDQuNC80LXQvdC+0LLQsNC90LjQtQ0KDQrQptC10L3QsA0KDQrQmtC+0Lst0LLQvg0KDQrQodGD
// 0LzQvNCwDQoNCtCd0LDQuNC80LXQvdC+0LLQsNC90LjQtQ0KDQrQptC10L3QsA0KDQrQmtC+0Lst
// 0LLQvg0KDQrQodGD0LzQvNCwDQoq0JjQotCe0JPQnioNCioxMjIwKg0K
// --00000000000054ad3d05dfdb89e0
// Content-Type: text/html; charset="UTF-8"
// Content-Transfer-Encoding: quoted-printable



























    $subject = "Заказ успешно оформлен";

    // $message = ' <p>Текст письма</p> </br> <b>1-ая строчка </b> </br><i>2-ая строчка </i> </br>';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    // $headers = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: lancelot.ru <shop@lancelot.ru>\r\n";
    // $headers .= "Reply-To: reply-to@example.com\r\n";

    mail($to, $subject, $message, $headers);
    
    echo (json_encode(array('result' => 'done')));
} else {
    echo (json_encode(array('result' => array("error" => "Ошибка: " . $query . "<br>" . mysqli_error($link)))));
}
