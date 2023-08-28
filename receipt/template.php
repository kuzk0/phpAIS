<!DOCTYPE html>

<head>
    <title>Товары</title>
    <link href="/css/products.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/src/jquery-latest.min.js" type="text/javascript"></script>
    <script src="./script.js" type="text/javascript"></script>
</head>

<body>
    <aside class="chek">
        <!-- Боковое окно -->
        <div class="chek__wrapper">
            <h2 class="chek__title">Чек
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
                    <!-- <tr>
                                <td cellspacing="3" >
                                    <table class="chek__list-wrapper">
                                        <tbody>
        
                                        </tbody>
                                    </table>

                                </td>
                            </tr> -->
                    <!-- <tr class="chek__item" data-code="4601231231230">
                                <td class="chek__item-name">Сахарный песок</td>
                                <td class="chek__item-price">130</td>
                                <td class="chek__item-count"><button class="chek__item-count--less">-</button><span class="chek__item-count--text" contenteditable="PLAINTEXT-ONLY">41.3</span><button class="chek__item-count--more">+</button></td>
                                <td class="chek__item-amount">260</td>
                            </tr> -->
                </table>
                <table class="chek__list">
                    <tbody></tbody>
                </table>
            </div>
            <div class="chek__sum">
                <span class="chek__sum-text">
                    Сумма
                </span>
                <span class="chek__sum-value">
                    0
                </span>
                <span class="chek__sum-wallet">₽</span>
                <button class="chek__sum-order">Оплатить</button>
            </div>
        </div>
    </aside>
</body>

</html>