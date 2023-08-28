<?php
// require_once '../dompdf/autoload.inc.php';
include("../chekauth.php"); // проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once($base_dir . '/dompdf/autoload.inc.php');
$options = new Dompdf\Options();
$options->set('isRemoteEnabled', true);
$options->setChroot([$base_dir]);
$dompdf = new Dompdf\Dompdf($options);
// $dompdf = new Dompdf\Dompdf();
// $dompdf->set_option('isRemoteEnabled',,TRUE);





// header('Content-Type: json');
if (!isset($_REQUEST['id']))
    exit;
else
    $id =  $_REQUEST['id'];


$searchResult = [];
$query = "SELECT `order_id`, `products_list`, `amount`, `date`, `name` AS 'employee_name', `client_name`, `client_email`, `type` AS 'payment_form'  FROM `orders`, employees, payment_forms 
WHERE employees.employee_id = orders.employee AND payment_forms.form_id = orders.payment_form AND `order_id` = '{$id}'"; // сам sql запрос к базе данных на поиск необходимого Накладная
$result = mysqli_query($link, $query);                         // отправка запроса. Если запрос выдает ошибку, то завершить выполнение с выводом данной ошибки ('die') 
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);                     //пройтись по результатам выборки в базе данных
if (!(count($data) <= 0)) {




    $mes = '';
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


        $message = "



    <html>
    <head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
        <style>







        * {font-family: 'DejaVu Sans' ;font-size: 14px;line-height: inherit;}
        * {
          margin: 0;
          padding: 0;
        }

        :root {
          --btn: #5aaac9;
          --text:#4b7caf;
        }

        .conteyner {
          width: 1100px;
          padding: 0 20px;
          margin: 0 auto;
      
          font-size: 21px;
        }

        .hidden {
          display: none !important;
          width: 0 !important;
          height: 0 !important;
          overflow: hidden !important;
          opacity: 0 !important;
        }

        .header {
          width: 100%;
          height: 65px;
          display: flex;
          align-items: center;
        }

        .nav {
          display: flex;
          flex-direction: row;
          justify-content: space-between;
        }
        .nav__link {
          text-decoration: none;
          color: inherit;
        }
        .nav__link.active {
          color: var(--text);
        }
        .nav__left-nav, .nav__right-nav {
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          list-style-type: none;
        }
        .nav__left-item, .nav__right-item {
          height: 100%;
          width: max-content;
        }
        .nav__left-item:hover, .nav__right-item:hover {
          color: var(--text);
        }
        .nav__right-nav {
          width: max-content;
        }
        .nav__left-nav {
          width: 400px;
        }

        .green {
          color: #72af72;
        }

        .red {
          color: lightcoral;
        }

        .load-gif {
          display: block;
          width: 200px;
          text-align: center;
          margin: 0 auto;
        }

        .receipt__list {
          list-style-type: none;
          margin: 0 auto;
          display: grid;
          grid-gap: 20px;
          grid-auto-flow: dense;
          grid-template-columns: 1fr 1fr;
        }
        .receipt__item {
          margin: 0 auto;
          padding: 20px;
          border: 1px solid lightgrey;
          border-radius: 6px;
          width: 460px;
        //   height: max-content;
        }
        .receipt__item-id {
          text-align: center;
          margin-bottom: 10px;
        }
        .receipt__item-amount, .receipt__item-date, .receipt__item-employee-name, .receipt__item-payment-form {
          display: inline-flex;
          width: 100%;
          justify-content: space-between;
         
        }
        .receipt__item-payment-form span, .receipt__item-date span, .receipt__item-employee-name span {
            margin-right: auto;
            width: 50%;
            display:inline-block;
       
           
        
        }
        .receipt__item-payment-form {
          border-bottom: 1px dashed grey;
        }
        .receipt__item-amount {
          margin-top: 15px;
          text-transform: uppercase;
          font-weight: 700;
        }
        .receipt__item-products-list {
          list-style-type: none;
        }

        .chek {
          float: right;
        }
        .chek__wrapper {
          min-height: 400px;
          width: 100%;
          border: 1px solid lightgrey;
          border-radius: 6px;
          position: relative;
        }
        .chek__title {
          text-align: center;
          color: #3d6e61;
          padding-top: 9px;
        }
        .chek__title-clearBtn {
          width: 100px;
          color: white;
          display: inline-block;
          height: 40px;
          background: var(--btn);
          font-size: 17px;
          line-height: 40px;
          border-radius: 6px;
          margin-left: 210px;
          cursor: pointer;
        }
        .chek__title-clearBtn:hover {
          opacity: 0.6;
        }
        .chek__list, .chek__list-title {
          width: 100%;
          border-spacing: 0;
        }
        .chek__list-title th, .chek__list td {
          border-bottom: 1px solid lightgrey;
          border-right: 1px solid lightgrey;
          box-sizing: border-box;
          width: 70px;
          text-align: center;
        }
        .chek__list-title th:last-of-type, .chek__list td:last-of-type {
          border-right: 0;
        }
        .chek__list-title th:first-of-type, .chek__list td:first-of-type {
          width: 170px;
          text-align: center;
        }
        .chek__list-title th {
          font-weight: normal;
          padding: 10px 0;
          font-size: 19px;
        }
        .chek__item td {
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
          font-size: 14px;
          line-height: 25px;
        }
        .chek__item-count {
          display: flex;
          justify-content: space-between;
        }
        .chek__item-count--text {
          overflow: hidden;
          width: 34px;
          text-align: center;
        }
        .chek__item-count--less, .chek__item-count--more {
          cursor: pointer;
          display: inline-block;
          font-size: 18px;
          width: 16px;
          user-select: none;
          border: none;
          background: none;
        }
        .chek__item-count--less:hover, .chek__item-count--more:hover {
          background: #e5e7ff;
        }
        .chek__sum {
          width: -webkit-fill-available;
          position: absolute;
          bottom: 0;
          padding: 12px;
          display: flex;
          justify-content: space-between;
          align-items: center;
        }
        .chek__sum-value {
          margin-left: 30px;
        }
        .chek__sum-order {
          background: var(--btn);
          border: 0;
          width: 100px;
          height: 40px;
          border-radius: 6px;
          color: white;
          font-size: 17px;
          cursor: pointer;
        }
        .chek__sum-order:hover {
          background: #8c94d9;
        }

        /*# sourceMappingURL=receipt.css.map */
        </style>
    </head>
    <body>







            <div class='receipt__item'>
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
                <table class='chek__list'>
                    <tbody>
            {$products}
            </tbody>
            </table>
            </div>
            </ul>
            <div class='receipt__item-amount'><span>Итого: </span>{$receipt['amount']}</div>
        </div>
    </body>
    </html>
        ";
    }

    $dompdf->setPaper('A4', 'portrait');
    $dompdf->loadHtml($message, 'UTF-8');
    // $dompdf->render();
    // $dompdf->load_html($html);
    $dompdf->render();

    // $dompdf->stream();
    $dompdf->stream("reciept-Lancelot{$id}.pdf");
}
