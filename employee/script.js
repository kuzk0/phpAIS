$(document).ready(() => {
  $(".employee__exit").on("click", (e) => {

    $.removeCookie('login', { path: '/' });
    $.removeCookie('password', { path: '/' });
    window.location.reload()
  });

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

  function create(elem) {
    $.ajax({
      url: "/createEmployee.php", //адрес, по которому отправляется запрос
      method: "POST", // метод запроса, который будет обрабатывать сервер
      data: elem, //сама информация в форме объекта (конвертируется в ?product='value')
      success: function (data) {
        //по выполнению запроса, обработать присланные данные
        if (data == "" || data == null) {
          //информацию передаем строкой, так как в функции принимается именно строка, которая потом парсится

          alert("Нет результатов");
        } else {
          data = JSON.parse(data);
          if (data.result.error)
            alert("Ошибка выполнения запроса. Попробуйте изменить данные");
          else {
            if (data.result == "done") {
              alert("Успешно создан");
              $(".form__employee-add input").each((i, elem) => {
                $(elem).val("");
              });
            }
          }
        }
      },
      error: function (jqXHR, exception) {
        errorException(jqXHR, exception);
      },
    });
  }

  $(".form__employee-add").on("submit", (e) => {
    let form = {
      name: $("#form__employee-name").val(),
      login: $("#form__employee-login").val(),
      password: $("#form__employee-password").val(),
    };
    e.preventDefault();
    create(form);
  });
});
