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

// Вызывается при нажатии в Редактирование и при Добавить/изменить - Да 

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

// В зависимости от того, есть ли такой товар уже в БД, сравнение по barcode - выбирается создать или изменить

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

// При нажатии Добавить/изменить происходит либо создание, либо изменение, алертится JSом

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
                    if (data[0].barcode == $('#form__products-barcode').val()) {
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