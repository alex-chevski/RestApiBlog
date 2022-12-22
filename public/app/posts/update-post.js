jQuery(($) => {
  $(document).on('click', '.update-post-button', function () {
    const id = $(this).attr('data-id');
    $.getJSON('http://10.0.2.10/posts/' + id, (data) => {
      const title = data.title;
      const content = data.content;
      const description = data.description;

      // Сохраним html в переменной «update product»
      let update_product_html =
        `
    <div id="read-posts" class="btn btn-primary pull-right m-b-15px read-posts-button">
        <span class="glyphicon glyphicon-list"></span> Все Посты
    </div>

    <!-- Построение формы для изменения товара -->
    <!-- Мы используем свойство "required" html5 для предотвращения пустых полей -->
    <form id="update-product-form" action="#" method="post" border="0">
        <table class="table table-hover table-responsive table-bordered">
            <tr>
                <td>Заголовок</td>
                <td><input value=\"` +
        title +
        `\" type="text" name="title" class="form-control" required /></td>
            </tr>

            <tr>
                <td>Контент</td>
                <td><input value=\"` +
        content +
        `\" type="text" name="content" class="form-control" required /></td>
            </tr>

            <tr>
                <td>Описание</td>
                <td><textarea name="description" class="form-control" required>` +
        description +
        `</textarea></td>
            </tr>

            <tr>
                <!-- Кнопка отправки формы -->
                <td>

                    <button type="submit" class="btn btn-info">
                        <span class="glyphicon glyphicon-edit"></span> Обновить пост
                    </button>
                </td>
            </tr>
        </table>
    </form>
`;

      // Добавим в «page-content» нашего приложения
      $('#page-content').html(update_product_html);

      // Изменим title страницы
      changePageTitle('Обновление Поста');

      $(document).on('submit', '#update-product-form', function () {
        // Получаем данные формы
        const form_data = JSON.stringify($(this).serializeObject());
        // Отправка данных формы в API
        $.ajax({
          url: 'http://10.0.2.10/posts/' + id,
          type: 'PATCH',
          contentType: 'application/json',
          data: form_data,
          success: (result) => {
            if (result['status'] === true) {
              // Товар был успешно обновлён, возврат к списку товаров
              showPosts(result);
            } else {
              //remove чтобы не выходил блок при повторном нажатии
              $('.alert').remove();

              $('#page-content').append(
                `<div class="alert alert-danger">
					<p id="out_err"></p>
				</div>`
              );
              result['message'] ? $('#out_err').html(result['message']) : '';
            }
          },
          error: (xhr, resp, text) => {
            // Вывод ошибки в консоль
            console.log(xhr, resp, text);
          },
        });
        return false;
      });
    });
  });
});
