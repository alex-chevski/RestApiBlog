jQuery(($) => {
  // HTML приложения
  let app_html = `
        <div class="container">
            <div class="page-header">
                <h1 id="page-title">RestApiBlog</h1>
            </div>

            <!-- Здесь будет показано содержимое страницы -->
            <div id="page-content"></div>
        </div>`;

  // Вставка кода на страницу
  $('#app').html(app_html);
});

// Изменение заголовка страницы
function changePageTitle(page_title) {
  // Изменение заголовка страницы

  // Изменение заголовка вкладки браузера
  document.title = page_title;

  if (page_title === 'Все посты') {
    $('#page-title').html(`
   <h1 class="d-inline">${page_title}</h1>
   <a class="btn btn-primary" id="login" role="button">
   Войти
   </a>
   <button class="btn btn-primary" id="sign_up" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
   Регистрация
   </button>
  `);
  } else {
    $('#page-title').html(`
   <h1 class="d-inline">${page_title}</h1>

   `);
  }
}

// Функция для создания значений формы в формате json
$.fn.serializeObject = function () {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function () {
    if (o[this.name] !== undefined) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};
