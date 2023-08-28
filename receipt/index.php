<?php include("../chekauth.php"); // проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа


// header('Content-Type: json');
if (!isset($_GET['page']))
    $page =  0;
else
    $page =  $_GET['page'];


$searchResult = [];
$query = "SELECT `order_id`, `products_list`, `amount`, `date`, `name` AS 'employee_name', `client_name`, `client_email`, `type` AS 'payment_form'  FROM `orders`, employees, payment_forms 
WHERE employees.employee_id = orders.employee AND payment_forms.form_id = orders.payment_form"; // сам sql запрос к базе данных на поиск необходимого Накладнаяа
$result = mysqli_query($link, $query);                         // отправка запроса. Если запрос выдает ошибку, то завершить выполнение с выводом данной ошибки ('die') 
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);                     //пройтись по результатам выборки в базе данных
if (!(count($data) <= 0)) {
    if ($page !=  0) {
        header('Content-Type: application/json; charset=UTF-8');
        print_r(json_encode($data, JSON_UNESCAPED_UNICODE));
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/receipt.css">
    <script src="/src/jquery-latest.min.js" type="text/javascript"></script>
    <title>Накладные</title>
</head>

<body>
    <header class="header">
        <div class="conteyner">
            <nav class="nav">
                <ul class="nav__left-nav">
                    <li class="nav__left-item"><a href="/" class="nav__link">Касса</a></li>
                    <li class="nav__left-item"><a href="/products" class="nav__link">Товары</a></li>
                    <li class="nav__left-item"><a href="/receipt" class="nav__link active">Накладные</a></li>
                </ul>
                <ul class="nav__right-nav">
                    <li class="nav__right-item"><a href="/employee" class="nav__link">Сотрудник</a></li>
                </ul>
            </nav>

        </div>
    </header>
    <main>
        <div class="conteyner">
            <ul class="receipt__list">
                <?php

                foreach ($data as &$receipt) {
                    // var_dump($receipt);
                    $products = "";
                    $products_array = json_decode($receipt['products_list'], true);
                    foreach ($products_array as &$product) {
                        $products .=
                            "
                                <tr class='receipt__item-products-item'>
                                
                                <td class='receipt__item-products-item--name'>{$product['name']}</td>
                            <!--<td class='receipt__item-products-item--code'>{$product['product_id']}</td> -->
                            <td class='receipt__item-products-item--price'>{$product['price']}</td>
                            <td class='receipt__item-products-item--count'>{$product['count']}</td>
                            <td class='receipt__item-products-item--summ'>" . ($product['count'] * $product['price']) . "</td>
                            
                                
                            </tr>

                            ";
                    };

                    // <div class="chek__list-wrapper">
                    //     <!-- Первая таблица с заголовками таблицы -->
                    //     <table class="chek__list-title">
                    //         <tr class="">
                    //             <th class="chek__list-title-name">Имя</th>
                    //             <th class="chek__list-title-price">Цена</th>
                    //             <th class="chek__list-title-count">Кол-во</th>
                    //             <th class="chek__list-title-amount">Сумма</th>
                    //         </tr>

                    //     </table>
                    //     <!-- Сама таблица с позициями товаров -->
                    //     <table class="chek__list">
                    //         <tbody></tbody>
                    //     </table>
                    // </div>



                    echo ("
                        <li class='receipt__item'>
                        <div class='receipt__item-id'>Накладная №{$receipt['order_id']}</div>
                        <div class='receipt__item-date'><span>Дата: </span>{$receipt['date']}</div>
                        
                        <div class='receipt__item-employee-name'><span>Кассир: </span>{$receipt['employee_name']}</div>
                        <div class='receipt__item-employee-name'><span>Покупатель: </span>{$receipt['client_name']}</div>
                        <div class='receipt__item-employee-name'><span>Email: </span>{$receipt['client_email']}</div>
                        <div class='receipt__item-payment-form'><span>Тип оплаты: </span>{$receipt['payment_form']}</div>
                        <ul class='receipt__item-products-list'>
                        <div class='chek__list-wrapper'>
                                <table class='chek__list-title'>
                                <tr class=''>
                                    <th class='chek__list-title-name'>Наименование</th>
                                    <th class='chek__list-title-price'>Цена</th>
                                    <th class='chek__list-title-count'>Кол-во</th>
                                    <th class='chek__list-title-amount'>Сумма</th>
                                </tr>

                            </table>
                            <!-- Сама таблица с позициями товаров -->
                            <table class='chek__list'>
                                <tbody>
                        {$products}
                        </tbody>
                        </table>
                        </div>
                        </ul>
                        <div class='receipt__item-amount'><span>Итого: </span>{$receipt['amount']}</div>
                        <a href='./print.php?id={$receipt['order_id']}'>Распечатать</a>
                    </li>");
                    
                }
                ?>
            </ul>
        </div>
       
    </main>
</body>

</html>