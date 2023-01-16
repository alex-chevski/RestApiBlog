import { showPosts } from './read-posts.js';
import { showError } from '../function/js/showError.js';

jQuery(($) => {
  $(document).on('click', '.create-post-button', () => {
    // $.getJSON('http://10.0.2.10/posts', (data) => {
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

		  <tr>
			<td>Фотография</td>
			<td>
			<label for="formFileLg" class="form-label">Choose photo to upload</label>
			<input type="file" name="post_image" class="form-control form-control-lg" accept="image/*">
			</td>
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

    // Вставка html в «page-content» нашего приложения
    $('#page-content').html(create_post_html);

    // Изменяем тайтл
    changePageTitle('Создание поста');
  });
});

// Будет работать, если создана форма поста
$(document).on('submit', '#create-post-form', function () {
  $.ajax({
    url: 'http://10.0.2.10/posts',
    type: 'POST',
    contentType: false,
    processData: false,
    cache: false,
    data: new FormData(this),
    success: (result) => {
      showPosts(result);
    },
    error: (xhr) => {
      showError(xhr.responseJSON.message);
    },
  });
  return false;
});
