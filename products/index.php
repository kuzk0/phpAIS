<?php include("../chekauth.php"); // проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/products.css">
    <script src="/src/jquery-latest.min.js" type="text/javascript"></script>
    <title>Товары</title>
</head>

<body>
    <main>
        <header class="header">
            <div class="conteyner">
                <nav class="nav">
                    <ul class="nav__left-nav">
                        <li class="nav__left-item"><a href="/" class="nav__link">Касса</a></li>
                        <li class="nav__left-item"><a href="/products" class="nav__link active">Товары</a></li>
                        <li class="nav__left-item"><a href="/receipt" class="nav__link">Чеки</a></li>
                    </ul>
                    <ul class="nav__right-nav">
                        <li class="nav__right-item"><a href="/employee" class="nav__link">Сотрудник</a></li>
                    </ul>
                </nav>

            </div>
        </header>
        <main>
            <div class="conteyner">
                <form class="form__products-add">
                    <h1 class="form__products-title">Создать или изменить товар</h1>
                    <label for="form__products-barcode">Номер штрих-кода</label>
                    <input type="text" required placeholder="4601234567890" pattern="[0-9]{13}" name="barcode" class="form__products-add--inputs" id="form__products-barcode">
                    <label for="form__products-name">Наименование товара</label>
                    <input type="text" required placeholder="Арбуз 'мой ценник 1кг'" min="1" name="name" class="form__products-add--inputs" id="form__products-name">
                    <label for="form__products-price">Цена (1 шт/1 кг) /р.</label>
                    <input type="text" required placeholder="111.99" pattern="\d+(\.\d{2})?" name="price" class="form__products-add--inputs" id="form__products-price">
                    <label for="form__products-count">Количество добавляемого товара (1 шт/1 кг)</label>
                    <input type="text" required placeholder="1.42" pattern="\d+(\.\d{2})?" name="count" min="1" class="form__products-add--inputs" id="form__products-count">
                    <button type="submit" class="form__products-add--btn">
                        Добавить товар
                    </button>
                </form>
            </div>
            <script>
                function errorException(jqXHR, exception) {
                    //ошибка выполнения запроса и коды ошибок
                    if (jqXHR.status === 0) {
                        alert("Нет подключения. Проверьте подключение к интернету.");
                    } else if (jqXHR.status == 404) {
                        alert("Страница не найдена или удалена.");
                    } else if (jqXHR.status == 500) {
                        alert("Ошибка выполнения запроса. Попробуйте изменить значение.");
                    } else if (exception === "parsererror") {
                        alert("Сервер прислал неверные данные. Попробуйте изменить значение");
                    } else if (exception === "timeout") {
                        alert("Сервер не отвечает.");
                    } else if (exception === "abort") {
                        alert("Запрос отменен.");
                    } else {
                        alert("Неизвестная ошибка. " + jqXHR.responseText);
                    }
                }

                function change(elem) {
                    $.ajax({
                        url: "/changeProduct.php", //адрес, по которому отправляется запрос
                        method: "GET", // метод запроса, который будет обрабатывать сервер
                        data: {
                            barcode: elem.barcode,
                            name: elem.name,
                            price: elem.price,
                            count: elem.count,
                        }, //сама информация в форме объекта (конвертируется в ?product='value')
                        success: function(data) {
                            //по выполнению запроса, обработать присланные данные
                            if (data == "" || data == null) {
                                //информацию передаем строкой, так как в функции принимается именно строка, которая потом парсится
                                alert("Нет результатов");
                            } else {
                                if (data == "Error")
                                    alert("Ошибка выполнения запроса. Попробуйте изменить данные");
                                else {
                                    data = JSON.parse(data)
                                    if (data.result == 'done') {
                                        alert("Успешно")
                                        $('.form__products-add input').each((i, elem) => {
                                            $(elem).val('')
                                        })
                                    }
                                }
                            }
                        },
                        error: function(jqXHR, exception) {
                            errorException(jqXHR, exception);
                        },
                    });
                }

                function create(elem) {
                    $.ajax({
                        url: "/createProduct.php", //адрес, по которому отправляется запрос
                        method: "POST", // метод запроса, который будет обрабатывать сервер
                        data: {
                            barcode: elem.barcode,
                            name: elem.name,
                            price: elem.price,
                            count: elem.count,
                        }, //сама информация в форме объекта (конвертируется в ?product='value')
                        success: function(data) {
                            //по выполнению запроса, обработать присланные данные
                            if (data == "" || data == null) {
                                //информацию передаем строкой, так как в функции принимается именно строка, которая потом парсится

                                alert("Нет результатов");


                            } else {
                                data = JSON.parse(data)
                                if (data.result.error)
                                    alert("Ошибка выполнения запроса. Попробуйте изменить данные");
                                else {

                                    if (data.result == 'done') {
                                        alert("Успешно создан")
                                        $('.form__products-add input').each((i, elem) => {
                                            $(elem).val('')
                                        })
                                    }
                                }
                            }
                        },
                        error: function(jqXHR, exception) {
                            errorException(jqXHR, exception);
                        },
                    });
                }

                $('.form__products-add').on('submit', (e) => {
                    let form = {
                        barcode: $('#form__products-barcode').val(),
                        name: $('#form__products-name').val(),
                        price: $('#form__products-price').val(),
                        count: $('#form__products-count').val(),

                    };
                    e.preventDefault();
                    $.ajax({
                        url: "/searchProduct.php", //адрес, по которому отправляется запрос
                        method: "GET", // метод запроса, который будет обрабатывать сервер
                        data: {
                            product: $('#form__products-barcode').val(),
                            page: 0,
                        }, //сама информация в форме объекта (конвертируется в ?product='value')
                        success: function(data) {
                            //по выполнению запроса, обработать присланные данные
                            if (data.trim() == "" || data == null) {
                                //информацию передаем строкой, так как в функции принимается именно строка, которая потом парсится

                                create(form)

                            } else {
                                if (data == "Error")
                                    alert("Ошибка выполнения запроса. Попробуйте изменить данные");
                                else {
                                    data = JSON.parse(data);
                                    console.log(data);
                                    if (data.barcode == $('#form__products-barcode').val()) {
                                        const recreate = confirm(`Изменить товар '${data[0].name}'?`);
                                        if (recreate) {
                                            change(form)
                                        }
                                    }
                                }
                            }
                        },
                        error: function(jqXHR, exception) {
                            errorException(jqXHR, exception);
                        },
                    });
                })
            </script>
        </main>
    </main>
</body>

</html>