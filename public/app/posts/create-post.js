jQuery(($) => {
  $(document).on('click', '.create-post-button', () => {
    $.getJSON('http://10.0.2.10/posts', (data) => {
      let create_post_html = `
    <!-- Кнопка для показа всех товаров -->
    <div id="read-posts" class="btn btn-primary pull-right m-b-15px read-posts-button">
        <span class="glyphicon glyphicon-list"></span> Все посты
    </div>
	<!-- html форма «Создание поста» -->
<form id="create-post-form" action="#" method="post" border="0">
    <table class="table table-hover table-responsive table-bordered">
        <tr>
            <td>Заголовок</td>
            <td><input type="text" name="title" class="form-control" required /></td>
        </tr>

		<tr>
			<td>Описание</td>
			<td><input type="text" name="description"class="form-control" required></td>
		</tr>

        <tr>
            <td>Контент</td>
            <td><textarea name="content" class="form-control" required></textarea></td>


        <!-- Кнопка отправки формы -->
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-plus"></span> Создать Пост
                </button>
            </td>
        </tr>
    </table>`;

      `</form>`;

      // Вставка html в «page-content» нашего приложения
      $('#page-content').html(create_post_html);

      // Изменяем тайтл
      changePageTitle('Создание товара');
    });
  });

  // Будет работать, если создана форма товара
  $(document).on('submit', '#create-post-form', function () {
    // Получение данных формы
    let form_data = JSON.stringify($(this).serializeObject());

    // Отправка данных формы в API
    $.ajax({
      url: 'http://10.0.2.10/posts',
      type: 'POST',
      contentType: 'application/json',
      data: form_data,
      success: (result) => {
        if (result['status'] === true) {
          showProducts(result);
        } else {
          $('.alert').remove();
          $('#page-content').append(
            `<div class="alert alert-danger">
				<p id="out_err"></p>
			</div>`
          );
          $('#out_err').html(result['message']);
        }
      },
      error: (xhr, resp, text) => {
        // Вывести ошибку в консоль
        console.log(xhr, resp, text);
      },
    });

    return false;
  });
});
