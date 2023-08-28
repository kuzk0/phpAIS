<?php include("../chekauth.php"); // проверяем вход на страницу. Если учетные данные верные, то пускаем, иначе перекидываем на страницу входа


// header('Content-Type: json');
if (!isset($_GET['page']))
    $page =  0;
else
    $page =  $_GET['page'];




?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/employees.css">
    <script src="/src/jquery-latest.min.js" type="text/javascript"></script>
    <script src="/src/jquery.cookie.js" type="text/javascript"></script>
    <script src="/employee/script.js" type="text/javascript"></script>
    <title>Сотрудник</title>
</head>

<body>
    <header class="header">
        <div class="conteyner">
            <nav class="nav">
                <ul class="nav__left-nav">
                    <li class="nav__left-item"><a href="/" class="nav__link">Касса</a></li>
                    <li class="nav__left-item"><a href="/products" class="nav__link">Товары</a></li>
                    <li class="nav__left-item"><a href="/receipt" class="nav__link">Чеки</a></li>
                </ul>
                <ul class="nav__right-nav">
                    <li class="nav__right-item"><a href="/employee" class="nav__link active">Сотрудник</a></li>
                </ul>
            </nav>

        </div>
    </header>
    <main>
        <div class="conteyner">
            <div class="employee">
                <div class="employee__wrapper">
                    <span class="employee__name">
                        <?php echo $log[0]['name'] ?>
                    </span>
                    <button class="employee__exit">Выйти</button>
                </div>
                <form class="form__employee-add">
                    <h1 class="form__employee-title">Добавить сотрудника</h1>
                    <label for="form__employee-name">Имя сотруника</label>
                    <input type="text" required placeholder="Иванов И.И." name="name" class="form__employee-add--inputs" id="form__employee-name">
                    <label for="form__employee-login">Логин</label>
                    <input type="text" required placeholder="login" minlength="1" name="login" class="form__employee-add--inputs" id="form__employee-login">
                    <label for="form__employee-password">Пароль</label>
                    <input type="text" required placeholder="***" name="password" class="form__employee-add--inputs" id="form__employee-password">
                    <button type="submit" class="form__employee-add--btn">
                        Добавить
                    </button>
                </form>

                <script>
                  
                </script>
            </div>
            <?php

            ?>
        </div>
    </main>
</body>

</html>