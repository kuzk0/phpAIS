<!DOCTYPE html>

<head>
    <title>Авторизация</title>
    <link href="./style_login.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="/src/jquery-latest.min.js" type="text/javascript"></script>
</head>

<body>
    <main>
        <header class="header">
            <div class="conteyner">
                <nav class="nav">
                    <ul class="nav__left-nav">
                    <!-- <li class="nav__left-item"><a href="/" class="nav__link">Касса</a></li>
                    <li class="nav__left-item"><a href="/products" class="nav__link">Товары</a></li>
                    <li class="nav__left-item"><a href="/receipt" class="nav__link">Чеки</a></li> -->
                    </ul>
                    <ul class="nav__right-nav">
                        <!-- <li class="nav__right-item"><a href="/employee" class="nav__link">Сотрудник</a></li> -->
                    </ul>
                </nav>
            </div>

        </header>
        <section class="login">
            <div class="conteyner">
                <div class="wrapper">
                    <div class="login-wr">
                        <h2>Вход</h2>
                        <div class="form">
                            <input type="text" autofocus id="login" placeholder="Пользователь">
                            <input type="password" id="password" placeholder="Пароль">
                            <button id="log"> Вход </button>
                            <div id="error_logged">Неверное имя пользователя или пароль!</div>
                        </div>
                    </div>
                    <script>
                        var b = $("#log");
                        var err = $("#error_logged");
                        b.on("click", send);
                        async function send() {
                            if ($("input[type=text]").val().trim() != "" && $("input[type=password]").val().trim() != "" && $("input[type=text]").val().trim() != null && $("input[type=password]").val().trim() != null) {
                                document.cookie = await "login = " + $("input[type=text]").val().trim() + "; path=/";
                                document.cookie = await "password = " + $("input[type=password]").val().trim() + "; path=/";
                                await $.ajax({
                                    url: "./checkLogin.php",
                                    context: document.body,
                                    // method:"Post",
                                    success: (function(data) {
                                        console.log(data);
                                        if (data == "" || data == null || data == 'no_logged' ) {
                                            err.css("opacity", "1");
                                            setTimeout(() => {
                                                err.css("opacity", "0");
                                            }, 1500);
                                        } else {
                                            window.location.assign("<?php if(isset($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; else echo'/' ?>");
                                        }
                                    }),

                                });
                            } else {
                                err.css("opacity", "1");
                                setTimeout(() => {
                                    err.css("opacity", "0");
                                }, 1500);
                            }

                        }
                        $("input[type=text]").on("keydown", (e) => {
                            if (e.which == 13) $("input[type=password]").focus();
                            $("input[type=text]").val($("input[type=text]").val().trim());
                        })
                        $("input[type=password]").on("keydown", (e) => {
                            if (e.which == 13) send();
                            $("input[type=password]").val($("input[type=password]").val().trim());
                        })
                    </script>
                </div>
            </div>
        </section>
    </main>
</body>

</html>