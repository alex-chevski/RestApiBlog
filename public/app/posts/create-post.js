import { showPosts } from './read-posts.js';
import { showError } from '../function/js/showError.js';

jQuery(($) => {
  $(document).on('click', '.create-post-button', () => {
    $.getJSON('http://10.0.2.10/posts', (data) => {
      let create_post_html = `
    <!-- Кнопка для показа всех товаров -->
    <div id="read-posts" class="btn btn-primary pull-right m-b-15px read-posts-button">
        <span class="glyphicon glyphicon-list"></span> Все посты
    </div>
	<!-- html форма «Создание поста» -->
<form id="create-post-form" action="#" method="post" border="0" enctype="multipart/form-data">
    <table class="table table-hover table-responsive table-bordered">
        <tr>
            <td>Заголовок <br><small class="text-primary fst-light">(должен быть уникальным)</small></td>
            <td><input type="text" name="title" class="form-control" required /></td>
        </tr>

		<tr>
			<td>Описание</td>
			<td><input type="text" name="description"class="form-control" required></td>
		</tr>

        <tr>
            <td>Контент</td>
            <td><textarea name="content" class="form-control" required></textarea></td>
		</tr>

        <!-- Кнопка отправки формы -->
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-plus"></span> Создать Пост
                </button>
            </td>
        </tr>
    </table>
</form>`;

      // <tr>
      // 	<td>Фотография</td>
      // 	<td>
      // 	<label for="formFileLg" class="form-label">Choose photo to upload</label>
      // 	<input type="file" name="post_image" class="form-control form-control-lg" accept=".jpg .jpeg .png">
      // 	</td>
      // </tr>

      // Вставка html в «page-content» нашего приложения
      $('#page-content').html(create_post_html);

      // Изменяем тайтл
      changePageTitle('Создание поста');
    });
  });

  $(document).on('change', 'input[type=file]', function () {
    let form_data = this.files;
    create_post_img(form_data);
  });

  // Будет работать, если создана форма поста
  $(document).on('submit', '#create-post-form', function () {
    // Получение данных формы
    let form_data = JSON.stringify($(this).serializeObject());

    // console.log(form_data);

    // event.stopPropagation(); // остановка всех текущих JS событий
    // event.preventDefault(); // остановка дефолтного события для текущего элемента - клик для <a> тега

    // ничего не делаем если files пустой
    // if (typeof files == 'undefined') console.log('Yes');

    // создадим объект данных формы
    // const data = [];

    // // заполняем объект данных файлами в подходящем для отправки формате
    // $.each(files, function (key, value) {
    //   form_data += value.name;
    //   form_data += value.size;

    //тут остановился console log что выше выдает имя
    // });

    // Отправка данных формы в API
    $.ajax({
      url: 'http://10.0.2.10/posts',
      type: 'POST',
      contentType: 'application/json, image/jpeg',
      data: form_data,
      success: (result) => {
        if (result['status'] === true) {
          showPosts(result);
        }
      },
      error: (xhr, resp, text) => {
        showError(xhr.responseJSON.message);
        // Вывести ошибку в консоль
        // console.log(xhr, resp, text);
      },
    });
    return false;
  });
});
