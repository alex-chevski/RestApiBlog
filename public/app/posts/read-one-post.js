jQuery(($) => {
  $(document).on('click', '.read-one-post-button', function () {
    const id = $(this).attr('data-id');
    $.getJSON('http://10.0.2.10/posts/' + id, (data) => {
      let read_one_post_html =
        `

    <div id="read-posts" class="btn btn-primary pull-right m-b-15px read-posts-button">
        <span class="glyphicon glyphicon-list"></span> Все Посты
    </div>

	<!-- Полные данные о товаре будут показаны в этой таблице -->
	<table class="table table-bordered table-hover">

    <tr>
        <td class="w-30-pct">Заголовок</td>
        <td class="w-70-pct">` +
        data.title +
        `</td>
    </tr>

    <tr>
        <td>Описание</td>
        <td>` +
        data.description +
        `</td>
    </tr>

    <tr>
        <td>Content</td>
        <td>` +
        data.content +
        `</td>
    </tr>

    <tr>
        <td>Дата</td>
        <td>` +
        data.published_date +
        `</td>
    </tr>
</table>`;
      $('#page-content').html(read_one_post_html);

      // Изменяем заголовок страницы
      changePageTitle('Просмотр поста');
    }).fail(function (data) {
      showError(data['message']);
    });
  });
});
