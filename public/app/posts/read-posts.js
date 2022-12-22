jQuery(($) => {
  showPosts();
  $(document).on('click', '.read-posts-button', () => {
    showPosts();
  });
});

function showPosts(result = []) {
  //result для flush
  $.getJSON('http://10.0.2.10/posts', (data) => {
    // HTML для перечисления товаров
    readPostsTemplate(data, '');

    //Проверка об успешном добавлении flash
    if (result['message']) {
      $('#page-content').append(
        `<div class="alert alert-success">
    <p id="flash"></p>
    </div>`
      );
      $('#flash').html(result['message']);
    }

    // Изменяем заголовок страницы
    changePageTitle('Все посты');
  });
}

//let read_posts_html = `

//<!-- При нажатии загружается форма создания товара -->
//<div id="create-post" class="btn btn-primary pull-right m-b-15px create-post-button">
//    <span class="glyphicon glyphicon-plus"></span> Создание поста
//</div>

//<!-- Таблица товаров -->
//<table class="table table-bordered table-hover">

//<!-- Создание заголовков таблицы -->
//<tr>
//    <th class="w-15-pct">Заголовок</th>
//    <th class="w-10-pct">Описание</th>
//    <th class="w-10-pct">Дата</th>
//    <th class="w-25-pct text-align-center">Действие</th>
//</tr>`;

//$.each(data, function (key, val) {
//  read_posts_html +=
//    `
//<tr>
//<td>` +
//    val.title +
//    `</td>
//<td>` +
//    val.description +
//    `</td>

//<td>` +
//    val.published_date +
//    `</td>

//        <!-- Кнопки "действий" -->
//        <td>
//            <!-- Кнопка чтения товара -->
//            <button class="btn btn-primary m-r-10px read-one-post-button" data-id="` +
//    val.url_key +
//    `">
//                <span class="glyphicon glyphicon-eye-open"></span> Просмотр
//            </button>
//            <!-- Кнопка редактирования -->
//            <button class="btn btn-info m-r-10px update-post-button" data-id="` +
//    val.url_key +
//    `">
//                <span class="glyphicon glyphicon-edit"></span> Редактирование
//            </button>
//            <!-- Кнопка удаления товара -->
//            <button class="btn btn-danger delete-post-button" data-id="` +
//    val.url_key +
//    `">
//                <span class="glyphicon glyphicon-remove"></span> Удаление
//            </button>
//        </td>
//    </tr>`;

//  $('#page-content').html(read_posts_html);
//});

//read_posts_html += `</table>`;

//changePageTitle('Rest-Api-Blog');
//});
//}
