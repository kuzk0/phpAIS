<?php include("./chekauth.php"); // проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа
?>

<!DOCTYPE html>

<head>
    <title>Касса</title>
    <link href="/css/products.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/src/jquery-latest.min.js" type="text/javascript"></script>
    <script src="./index/script.js" type="text/javascript"></script>
</head>

<body>
    <header class="header">
        <!-- шапка сайта -->
        <div class="conteyner">
            <!-- навигация слева -->
            <nav class="nav">
                <ul class="nav__left-nav">
                    <li class="nav__left-item"><a href="/" class="nav__link active">Главная</a></li>
                    <li class="nav__left-item"><a href="/products" class="nav__link">Товары</a></li>
                    <li class="nav__left-item"><a href="/receipt" class="nav__link">Накладные</a></li>
                </ul>
                <!-- навигация справа -->
                <ul class="nav__right-nav">
                    <li class="nav__right-item"><a href="/employee" class="nav__link">Сотрудник</a></li>
                </ul>
            </nav>
        </div>

    </header>
    <dialog id="favDialog" class="dialog">
        <form method="dialog">
            <p class="dialog__title">Покупатель:</p>
            <div class="dialog__input-wrapper">
                <input type="text" name="payment_name" placeholder="ФИО покупателя" class="dialog__text" required>

                <input type="email" name="payment_mail" placeholder="Email" class="dialog__text" required>

            </div>

            <p class="dialog__title">Тип оплаты:</p>
            <div class="dialog__input-wrapper">
                <input type="radio" name="payment_form" id="payment_form-1" value="1" class="dialog__radio" checked>
                <label for="payment_form-1" class="dialog__radio-label">
                    Наличные
                </label>
                <input type="radio" name="payment_form" id="payment_form-2" value="2" class="dialog__radio">
                <label for="payment_form-2" class="dialog__radio-label">
                    Карта
                </label>
            </div>
            <menu class="dialog__menu-wrapper">
                <button class="dialog__button dialog__button-cancel" value="cancel">Отменить</button>
                <button class="dialog__button dialog__button-confirm" id="confirmBtn" value="default">Подтвредить</button>
            </menu>
        </form>
    </dialog>
    <main>

        <section class="products">

            <div class="conteyner">
                <aside class="aside">
                    <div class="aside__wrapper" data-aside="menu">
                        <div class="form__products-add--btns">

                            <button type="button" class="form__products-add--btn aside__menu-btn" data-asideClick="reciept">
                                Накладная
                            </button>
                            <button type="button" class="form__products-add--btn aside__menu-btn" data-asideClick="products">
                                Редактирование
                            </button>
                        </div>

                    </div>
                    <!-- Боковое окно c секциями -->
                    <div class="aside__wrapper hidden" data-aside="reciept">
                        <button type="button" class="form__close aside__menu-btn" data-asideClick="menu"></button>
                        <h2 class="chek__title">Накладная
                            <span class="chek__title-clearBtn">Очистить</span>
                        </h2>

                        <div class="chek__list-wrapper">

                            <table class="chek__list-title">
                                <tr class="">
                                    <th class="chek__list-title-name">Имя</th>
                                    <th class="chek__list-title-price">Цена</th>
                                    <th class="chek__list-title-count">Кол-во</th>
                                    <th class="chek__list-title-amount">Сумма</th>
                                </tr>

                            </table>

                            <table class="chek__list">
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="chek__sum">
                            <span class="chek__sum-text">
                                Сумма
                                <span class="chek__sum-value">0</span>
                                <span class="chek__sum-wallet">₽</span>
                            </span>
                            <button class="chek__sum-order">Оплатить</button>
                        </div>
                    </div>






                    <div class="aside__wrapper hidden" data-aside="products">
                        <form class="form__products-add ">
                            <button type="button" class="form__close aside__menu-btn" data-asideClick="menu"></button>
                            <h1 class="form__products-title">Создать или изменить товар</h1>
                            <label for="form__products-barcode">Номер штрих-кода</label>
                            <input type="text" required placeholder="4601234567890" pattern="[0-9]{13}" name="barcode" class="form__products-add--inputs" id="form__products-barcode">
                            <label for="form__products-name">Наименование товара</label>
                            <input type="text" required placeholder="Арбуз 'мой ценник 1кг'" min="1" name="name" class="form__products-add--inputs" id="form__products-name">
                            <label for="form__products-price">Цена (1 шт/1 кг) /р.</label>
                            <input type="text" required placeholder="111.99" pattern="\d+(\.\d{2})?" name="price" class="form__products-add--inputs" id="form__products-price">
                            <label for="form__products-count">Количество добавляемого товара (1 шт/1 кг)</label>
                            <input type="text" required placeholder="1.42" pattern="\d+(\.\d{2})?" name="count" min="1" class="form__products-add--inputs" id="form__products-count">
                            <div class="form__products-add--btns">

                                <button type="submit" class="form__products-add--btn">
                                    Добавить
                                </button>
                                <button type="reset" class="form__products-add--btn">
                                    Очистить
                                </button>
                            </div>
                        </form>
                        <script src="/index/products.js"></script>
                    </div>
                </aside>
                <div class="products__wrapper">
                    <div class="products__search">
                        <!-- блок поиска товаров -->
                        <input type="text" name="product_name" placeholder="номер или имя товара" tabindex="1" id="search-input-text" class="products__search-text" autofocus>
                        <button class="products__search-button" id="search-input-button">
                            <!-- иконка поиска -->
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.7796 19.7216L16.4522 15.3943C17.5249 14.0865 18.1714 12.4114 18.1714 10.5857C18.1714 6.39796 14.7735 3 10.5857 3C6.39429 3 3 6.39796 3 10.5857C3 14.7735 6.39429 18.1714 10.5857 18.1714C12.4114 18.1714 14.0829 17.5286 15.3906 16.4559L19.718 20.7796C20.0118 21.0735 20.4857 21.0735 20.7796 20.7796C21.0735 20.4894 21.0735 20.0118 20.7796 19.7216ZM10.5857 16.6616C7.23184 16.6616 4.50612 13.9359 4.50612 10.5857C4.50612 7.23551 7.23184 4.50612 10.5857 4.50612C13.9359 4.50612 16.6653 7.23551 16.6653 10.5857C16.6653 13.9359 13.9359 16.6616 10.5857 16.6616Z" fill="currentColor" />
                            </svg>
                        </button>
                    </div>
                    <div class="products__list" id="products__list">
                        <!-- блок товаров (генерируется из запроса ajax) -->
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>