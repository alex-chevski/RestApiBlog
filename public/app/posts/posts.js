// import { getCookie } from '../function/js/getCookie.js';

export function readPostsTemplate(data = [], keywords = '') {
  // const jwt = getCookie('jwt');
  // $.getJSON('/', JSON.stringify({ jwt: jwt }))((result) => {});

  let read_posts_html =
    `
	<form id="search-post-form" action="#" method="GET">
		<div class="input-group pull-left w-30-pct">
		<input type="text" value="` +
    keywords +
    `"name="keywords" class="form-control post-search-keywords" placeholder="Поиск постов" />
			<span class="input-group-btn">
				<button type="submit" class="btn btn-default" type="button">
					<span class="glyphicon glyphicon-search"></span>
				</button>
			</span>
		</div>
	</form>

   <!-- При нажатии загружается форма создания поста -->
	<div id="create-post" class="btn btn-primary pull-right m-b-15px create-post-button">
		<span class="glyphicon glyphicon-plus"></span> Создать пост
	</div>

  <!-- Начало таблицы -->
        <table class="table table-bordered table-hover">
            <!-- Создание заголовков колонок -->
            <tr>
                <th class="w-25-pct">Заголовок</th>
                <th class="w-10-pct">Описание</th>
                <th class="w-10-pct">Дата</th>
                <th class="w-25-pct text-align-center">Действие</th>
            </tr>`;

  if (data.records) {
    // Перебор возвращаемого списка данных
    $.each(data['records'], (key, val) => {
      // Создание новой строки таблицы для каждой записи
      read_posts_html +=
        `<tr>
            <td>` +
        val.title +
        `</td>
            <td>` +
        val.description +
        `</td>
            <td>` +
        val.published_date +
        `</td>

            <!-- Кнопки "действий" -->
            <td>
                <!-- Кнопка для просмотра поста -->
                <button class="btn btn-primary m-r-10px read-one-post-button" data-id="` +
        val.url_key +
        `">
                    <span class="glyphicon glyphicon-eye-open"></span> Просмотр
                </button>
                <!-- Кнопка для изменения поста -->
                <button class="btn btn-info m-r-10px update-post-button" data-id="` +
        val.url_key +
        `">
                    <span class="glyphicon glyphicon-edit"></span> Редактировать
                </button>
                <!-- Кнопка для удаления поста -->
                <button class="btn btn-danger delete-post-button" data-id="` +
        val.url_key +
        `">
                    <span class="glyphicon glyphicon-remove"></span> Удалить
                </button>
            </td>
        </tr>`;
    });
  }

  // Конец таблицы
  read_posts_html += `</table>`;

  if (data.paging) {
    read_posts_html += `<ul class="pagination">`;

    // Первая страница
    if (data.paging.first != '') {
      read_posts_html += `<li><a data-page="${data.paging.first}">Первая страница</a></li>`;
    }

    // Перебор страниц
    $.each(data.paging.pages, (key, val) => {
      const active_page = val.current_page == 'yes' ? "class='active'" : '';

      read_posts_html += `<li ${active_page}><a data-page="${val.url}">${val.page}</a></li>`;
    });

    // Последняя страница
    if (data.paging.last != '') {
      read_posts_html += `<li><a data-page="${data.paging.last}">Последняя страница</a></li>`;
    }

    read_posts_html += '</ul>';
  }

  // Добавим в «page-content» нашего приложения
  $('#page-content').html(read_posts_html);
}
