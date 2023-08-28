$(document).ready(() => {
    /*
     *    предзагружаем картинку с загрузкой,
     *    чтобы когда появится на экране сама картинка,
     *    она уже была на экране полностью загруженной
     *    и пользователь не ждал еще и загрузки самой картинки
     */
    var loadImg = new Image();
    loadImg.src = "/src/load.gif";
    loadImg.alt = "loading";
    loadImg.title = "loading";
    loadImg.className = "load-gif";

    /*
     * обозначаем необходимые переменные
     * сами html элементы, с которыми работать
     * и переменную для setTimeout
     */
    var searchBtn = $("#search-input-button");
    var searchInput = $("#search-input-text");
    var list = $("#products__list");
    var timeoutsend;
    var page = 0;

    /*
     *поиск боковых окошечек по data-attr (data-aside), для того, чтобы его открыть и считывает текущий
     */
    var forms = $("[data-aside]");
    var currentFormOpen = "menu";

    /**
     * функция для закрытия всех боковых формочек и открытия необходимой (принимает в себя значение data-aside формы, которую необходимо открыть)
     */
    function formOpen(dataForm) {
        currentFormOpen = dataForm;
        forms.each((index, elem) => {
            if ($(elem).data("aside") != dataForm) elem.classList.add("hidden");
            else elem.classList.remove("hidden");
        });
    }
    /*
     * навешиваем события отправки данных на сервер
     * по нажатию на кнопку поиска
     */
    searchBtn.on("click", () => {
        send();
    });

    /**
     * и другие ивенты
     *
     * Событие прописывается в форме с 3 значениями (внутри ".on")
     * чтобы также присвоить его и будущим элементом с опреденным классом
     * так называемые динамические элементы, которых при первом отрабатывании
     * скрипта не существуют, но могут быть добавлены позже
     */

    $(".aside__wrapper").on("keydown", $(".chek__item-count--text"), (e) => {
        /**
         * если мы кликаем на поле с количеством в чеке,
         * то его можно отредактировать вручную, то есть изменить в цифровом количестве руками,
         * но чтобы пользователь ничего не сломал,
         * необходимо ограничить условия ввода данных
         *
         * для этого мы проверяем точно ли было нажатие клавиши
         * именно на в поле .chek__item-count--text
         * затем проверяем на допустимые клавиши
         * если это tab (9) или enter (13),
         * то снимаем фокус с данного элемента,
         * чтобы завершить редактирование
         *
         * затем проверяем на стрелочки,
         * чтобы пользователь мог ориентироваться по ним
         * как в обычном тексте (с 37 по 40)
         * и проверяем на Delete и Backspace
         *
         * дальше проверяем по регулярному выражение введенное значение
         * на цифру или точку
         *
         * и если последние условия не срабатывают,
         * то отменяем их действие функцией .preventDefault();
         *
         */
        if ($(e.target).is($(".chek__item-count--text"))) {
            if (e.which == 9 || e.which == 13) {
                e.preventDefault();
                e.target.blur();
            }
            if (!(e.which == 46 || e.which == 8 || e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40)) {
                if (!e.key.match(/\d|[.]/)) {
                    e.preventDefault();
                }
            }
        }
    });
    function clearChekItem(rowNum) {
        /**
         * если переданная переменная существует,
         * то по переданному номеру строки в таблице
         * удаляем строку
         */
        if (rowNum != undefined) {
            $($(".chek__list")[0].rows[rowNum]).remove();
        }
    }
    function itemSumm(rowNum) {
        /**
         * если номер строки в функцию передали,
         * то на данной строке в таблице чека
         * выбираем цену, количество и сумму
         * если количество = 0,
         * то удаляем элемент,
         * иначе в сумму позиции в чеке указываем
         * цена*количество
         */
        if (rowNum != undefined) {

         
        


          
            let price = $($(".chek__list")[0].rows[rowNum]).find(" .chek__item-price");
            let code = $($(".chek__list")[0].rows[rowNum]).data().code;
            let count = $($(".chek__list")[0].rows[rowNum]).find(" .chek__item-count--text");
            let anount = $($(".chek__list")[0].rows[rowNum]).find(" .chek__item-amount");
            if (Number(count.html()) == 0) {
                clearChekItem(rowNum);
            } else {

              $(".product").each((index, elem) => {

               if(+$(elem).find('.product__code').html() == +code){
                 if(+$(elem).find('.product__count').html() < +count.html()){
                  count.html($(elem).find('.product__count').html())
                 }
               }
            });







                // if()
                anount.html((price.html() * count.html()).toFixed(2));
            }
        }

        /**
         * затем проходимся по всем элементам
         * и считаем финальную сумму чека
         */
        let finalSum = 0.0;
        $(".chek__item-amount").each((index, elem) => {
            finalSum = finalSum.toFixed(2) - -Number($(elem).html()).toFixed(2);
        });
        $(".chek__sum-value").html(finalSum);
    }
    $(document).on("blur", ".chek__item-count--text", (e) => {
        /**
         * когда на поле с количеством в чеке пропадает фокус,
         * необходимо посчитать сумму, так как скорее всего
         * поле было отредактировано, а значит количество изменилось
         */
        itemSumm(e.target.parentNode.parentNode.rowIndex);
    });

    // Это вычитание количества из Накладной

    $(".aside__wrapper").on("click", $(".chek__item-count--less"), (e) => {
        if ($(e.target).is($(".chek__item-count--less"))) {
            /**
             * 1 получить номер строки в таблице
             * 2 найти сам html элемент с таким номером строки
             * 3 внутри него найти элемент с текстом, который хотим изменить
             * 4 получить текущее значение данного элемента
             * 5 убавить единичку
             * 6 присвоить новое значение данному элементу
             */
            let rowNum = e.target.parentNode.parentNode.rowIndex;
            let textElem = $($(".chek__list")[0].rows[rowNum]).find(" .chek__item-count--text");
            if (textElem.html() - 1.0 <= 0) {
                clearChekItem(rowNum);
            } else {
                textElem.html((textElem.html() - 1.0).toFixed(2));
            }
            itemSumm(rowNum);
        }
    });

    // Это добавление количества в Накладной

    $(".aside__wrapper").on("click", $(".chek__item-count--more"), (e) => {
        if ($(e.target).is($(".chek__item-count--more"))) {
            /**
             * 1 получить номер строки в таблице
             * 2 найти сам html элемент с таким номером строки
             * 3 внутри него найти элемент с текстом, который хотим изменить
             * 4 получить текущее значение данного элемента
             * 5 прибавить единичку
             * 6 присвоить новое значение данному элементу
             */
            let rowNum = e.target.parentNode.parentNode.rowIndex;
            let textElem = $($(".chek__list")[0].rows[rowNum]);
            let code = textElem.data().code;

            // let textElem = $($(".chek__list")[0].rows[rowNum]).find(".chek__item-count--text");

            $(".chek__list tbody")
                .find(".chek__item")
                .each((index, elem) => {
                    /**
                     * пройтись по всему списку в накладной
                     * получить коды каждой позиции
                     * проверить код на совпадение с выбранным в списке товаров
                     * если совпадает, то просто к количеству в чеке прибавить 1
                     * и пересчитать сумму
                     */
                    let elemInChek = $(elem);
                    let elemInChek_code = elemInChek.data().code;
                    if (elemInChek_code == Number(code.html())) {
                        added = true;
                        let elemInChek_count = elemInChek.find(".chek__item-count--text");
                        if (Number(elemInChek_count.html()) < Number(count.html())) {
                            /**
                             * также необходимо проверить, на количество товаров
                             * не будет ли превышать количество в накладной
                             * количества в поиске
                             */
                            if (elemInChek_count.html() - -1.0 <= Number(count.html())) {
                                elemInChek_count.html((elemInChek_count.html() - -1.0).toFixed(2));
                                itemSumm(elemInChek_count[0].parentNode.parentNode.rowIndex);
                            } else {
                                let c = count.html() - elemInChek_count.html();
                                elemInChek_count.html((elemInChek_count.html() - -c).toFixed(2));

                                itemSumm(elemInChek_count[0].parentNode.parentNode.rowIndex);
                            }
                        } else {
                            alert("Больше нет в наличии");
                        }
                    }
                });

            if ((textElem.html() - -1.0).toFixed(2)) textElem.html((textElem.html() - -1.0).toFixed(2));
            itemSumm(rowNum);
            /**
             * если написать "+1", то воспримет как строку и добавит в конец строки 1
             * пример: '23.2'+1 = '23.21'  :)
             */
        }
    });
    $(".aside__wrapper").on("click", $(".aside__menu-btn"), (e) => {
        if ($(e.target).is($(".aside__menu-btn"))) {
            formOpen($(e.target).data("asideclick"));
        }
    });

    /**
     * функция для обработки пришедшего с сервера ответ
     */

    // Функция для вывода списка товаров и Загрузить больше

    function result(val, pages) {
        val = JSON.parse(val);
        if (pages == undefined || pages == 0) {
            list.html(""); //очистить весь список товаров
        }
        $(".product__more").remove();
        $(".load-gif").remove();

        // Добавить header списка товаров, только при первом поиске

        list.append(
            pages == undefined || pages == 0
                ? `
                <div class="product-header" tabindex="0">
                <div class="product__name">Наименование</div>
                <div class="product__price">Цена</div>
                <div class="product__count">Наличие</div>
                <div class="product__code">Штрих-код</div>
                </div>
                `
                : ""
        );
        $.each(val, function (index, value) {
            /**
             * пройтись по каждому элементу JSON массива
             * и в список товаров добавить товар
             * с заданными параметрами
             */
            list.append(
                /**
                 * также проверить, не является ли первый элемент ошибкой
                 * если да, то вывести только её,
                 * иначе сами товары
                 *
                 * (условие)?(значение если true):(значение если false)
                 * - условный (тернарный) оператор
                 * https://learn.javascript.ru/ifelse#uslovnyy-operator
                 *
                 */
                pages == undefined || pages == 0
                    ? value.error
                        ? `<p class="products__error">${value.error}</p>`
                        : `
                <button class="product" tabindex="1" data-id="${value.product_id}">
                <div class="product__name" title="${value.name}">${value.name}</div>
                <div class="product__price">${value.price}</div>
                <div class="product__count ${value.count > 100 ? "green" : "red"}">${value.count}</div>
                <div class="product__code">${value.barcode}</div>
                </button>
                `
                    : `
                <button class="product" tabindex="1" data-id="${value.product_id}">
                <div class="product__name" title="${value.name}">${value.name}</div>
                <div class="product__price">${value.price}</div>
                <div class="product__count ${value.count > 100 ? "green" : "red"}">${value.count}</div>
                <div class="product__code">${value.barcode}</div>
                </button>
                `
                //  : ""
            );
        });
        if (val.length >= 5) {
            list.append(
                `
        <button class="product__more" tabindex="1">
        Загрузить больше
        </button>
        `
            );
        }
    }

    /**
     * функция обработки ошибок ajax запроса
     */
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

    /**
     * функция отправки данных на сервер
     * средствами ajax
     * библиотеки jquery
     *
     * Выполняет запрос к серверу
     * и при получениии ответа, обрабатывает полученную информацию
     * если ошибка, то вывести соответсвующее значение
     * в соответствии со статусом ошибки
     *
     * Делает то же, что https://learn.javascript.ru/xmlhttprequest
     * но короче записывать :)
     *
     */

    // Enter, кнопка Загрузить больше, Search

    async function send(pages) {
        if (pages == undefined) page = 0;
        else page = pages;
        //проверить, не пустое ли значение в поле, чтобы не отправлять пустой запрос
        if (searchInput.val().trim() != "" && searchInput.val().trim() != null) {
            await $.ajax({
                url: "/searchProduct.php", //адрес, по которому отправляется запрос
                method: "GET", // метод запроса, который будет обрабатывать сервер
                data: {
                    product: searchInput.val(),
                    page: page,
                }, //сама информация в форме объекта (конвертируется в ?product='value')
                beforeSend: function () {
                    //перед отправой очистить поле и показать саму загрузку
                    // list.html("");

                    $(".product").length > 1 ? $(".product").last().after(loadImg) : list.append(loadImg);
                    clearInterval(timeoutsend);
                },
                success: function (data) {
                    //по выполнению запроса, обработать присланные данные
                    if (data == "" || data == null) {
                        //информацию передаем строкой, так как в функции принимается именно строка, которая потом парсится
                        result(`[{"error":"Нет результатов"}]`, page);
                    } else {
                        if (data == "Error") result(`[{error:"Ошибка выполнения запроса. Попробуйте изменить запрос"}]`, page);
                        else result(data, page);
                    }
                },
                error: function (jqXHR, exception) {
                    errorException(jqXHR, exception);
                },
            });
        }
    }

    // Кнопка загрузить больше

    $(".products__list").on("click", $(".product__more"), (e) => {
        if ($(e.target).closest(".product__more").length) {
            page++;
            send(page);
        }
    });

    searchInput.on("keydown", async (e) => {
        /**
         *  обработать нажатия на клавиатуру в поле ввода
         *  если нажат enter, то отправить запрос сейчас же.
         *  если другая клавиша, то отправить запрос через 600 мс
         *  перед этим убрав данный интервал, если он уже был установлен,
         *  чтобы не отправилось много запросов на одно и то же
         */
        if (e.which == 13) send();
        else if (e.which != 9) {
            await clearTimeout(timeoutsend);
            timeoutsend = setTimeout(() => {
                send();
            }, 600);
        }
    });

    // Добавление товаров в Накладную по клику из самого списка товаров

    $(".products__list").on("click", $(".product"), (e) => {
        if ($(e.target).closest(".product").length) {
            let el = $(e.target).closest(".product");
            let name = el.find(".product__name");
            let price = el.find(".product__price");
            let count = el.find(".product__count");
            let code = el.find(".product__code");

            // Определяем что у нас выбрано Накладная или Изменение

            switch (currentFormOpen) {
                case "reciept":
                    {
                        let added = false; // переменная на проверку есть ли такой товар в наличии
                        if (count.html() <= 0) {
                            alert("Нет в наличии");
                            return;
                        } else {
                            $(".chek__list tbody")
                                .find(".chek__item")
                                .each((index, elem) => {
                                    /**
                                     * пройтись по всему списку в накладной
                                     * получить коды каждой позиции
                                     * проверить код на совпадение с выбранным в списке товаров
                                     * если совпадает, то просто к количеству в чеке прибавить 1
                                     * и пересчитать сумму
                                     */
                                    let elemInChek = $(elem);
                                    let elemInChek_code = elemInChek.data().code;
                                    if (elemInChek_code == Number(code.html())) {
                                        added = true;
                                        let elemInChek_count = elemInChek.find(".chek__item-count--text");
                                        if (Number(elemInChek_count.html()) < Number(count.html())) {
                                            /**
                                             * также необходимо проверить, на количество товаров
                                             * не будет ли превышать количество в накладной
                                             * количества в поиске
                                             */
                                            if (elemInChek_count.html() - -1.0 <= Number(count.html())) {
                                                elemInChek_count.html((elemInChek_count.html() - -1.0).toFixed(2));
                                                itemSumm(elemInChek_count[0].parentNode.parentNode.rowIndex);
                                            } else {
                                                let c = count.html() - elemInChek_count.html();
                                                elemInChek_count.html((elemInChek_count.html() - -c).toFixed(2));

                                                itemSumm(elemInChek_count[0].parentNode.parentNode.rowIndex);
                                            }
                                        } else {
                                            alert("Больше нет в наличии");
                                        }
                                    }
                                });

                            if (!added) {
                                /**
                                 * Если товара в накладной нет, то добавить с указанными параметрами
                                 * в единичном количестве. если количества меньше, чем 1,
                                 * то добавить остатки
                                 */
                                $(".chek__list tbody").append(
                                    `
            <tr class="chek__item" data-code="${code.html()}">
            <td class="chek__item-name">${name.html()}</td>
            <td class="chek__item-price">${price.html()}</td>
            <td class="chek__item-count"><button class="chek__item-count--less">-</button><span class="chek__item-count--text" contenteditable="PLAINTEXT-ONLY">1.00</span><button class="chek__item-count--more">+</button></td>
            <td class="chek__item-amount">${price.html()}</td>
            </tr>
            `
                                );
                                itemSumm();
                            }
                        }
                    }
                    break;

                case "products":
                    {
                        $("#form__products-barcode").val(code.html());
                        $("#form__products-name").val(name.html());
                        $("#form__products-price").val(price.html());
                        $("#form__products-count").val(count.html());
                    }
                    break;

                default:
                    break;
            }
        }
    });

    $(".chek__title-clearBtn").on("click", (e) => {
        /**
         * по нажатию на кнопку "очистить" в чеке:
         * 1 - удалить все позиции в чеке
         * 2 - пересчитать финальную сумму
         * (можно в целом просто 0 поставить..)
         */
        $(".chek__item").remove();
        itemSumm();
    });

    //   var updateButton = document.getElementById("updateDetails");
    var favDialog = document.getElementById("favDialog");
    //   var outputBox = document.querySelector("output");
    var selectEl = document.querySelector("select");
    var confirmBtn = document.getElementById("confirmBtn");

    $(".chek__sum-order").on("click", (e) => {
        /**
         * по нажатию на кнопку оплатить оператор должен
         * исходя из условий, выбрать форму оплаты
         * и подтвердить операцию, сообщив сумму покупателю
         *
         * также проверить браузер на поддержку
         * самого диалогового окна
         */
        if ($(".chek__item").length > 0) {
            if (typeof favDialog.showModal === "function") {
                favDialog.showModal();
                $("#confirmBtn").focus();
            } else {
                alert("<dialog> API не поддерживается.");
            }
        }
    });

    favDialog.addEventListener("close", function onClose() {
        console.log(favDialog.returnValue);
        if (favDialog.returnValue == "default") {
            let products_list = [];
            $(".chek__item").each((index, elem) => {
                products_list.push({
                    product_id: $(elem).data().code,
                    name: $(elem).find(".chek__item-name").html(),
                    count: $(elem).find(".chek__item-count--text").html(),
                    price: $(elem).find(".chek__item-price").html(),
                });
            });

            $.ajax({
                url: "/createOrder.php", //адрес, по которому отправляется запрос
                method: "POST", // метод запроса, который будет обрабатывать сервер
                data: {
                    products_list: JSON.stringify(products_list),
                    amount: $(".chek__sum-value").html(),
                    payment_form: $('[name="payment_form"]:checked').val(),
                    client_name: $('[name="payment_name"]').val(),
                    client_email: $('[name="payment_mail"]').val(),
                }, //сама информация в форме объекта (конвертируется в ?product='value')
                beforeSend: function () {
                    //перед отправой очистить поле и показать саму загрузку
                    list.html("");
                    list.append(loadImg);
                    clearInterval(timeoutsend);
                },
                success: function (data) {
                    //по выполнению запроса, обработать присланные данные
                    let res = JSON.parse(data);
                    if (res.result == "done") {
                        //информацию передаем строкой, так как в функции принимается именно строка, которая потом парсится
                        alert("Успешно");
                        $(".chek__item").remove();
                        list.html("");
                        searchInput.val("");
                        itemSumm();
                    } else {
                        alert("Ошибка запроса");
                        list.html("");
                    }
                },
                error: function (jqXHR, exception) {
                    errorException(jqXHR, exception);
                },
            });
        }
    });
});
