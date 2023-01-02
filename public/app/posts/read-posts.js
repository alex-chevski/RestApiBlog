import { getQueryParam } from '../function/js/getQueryParam.js';
import { showFlash } from '../function/js/showFlash.js';
import { showError } from '../function/js/showError.js';
//все начинается отсюда
jQuery(($) => {
  showPosts();

  $(document).on('click', '.read-posts-button', function () {
    showPosts();
  });

  $(document).on('click', '.pagination li', function () {
    const json_url = $(this).find('a').attr('data-page');
    showPosts([], json_url);
  });
});

export function showPosts(
  result = [],
  json_url = `http://10.0.2.10/posts?page=${getQueryParam('page') ?? 1}`
) {
  //вывод постов вместе с  пагинаций
  $.getJSON(json_url, function (data) {
    // HTML для перечисления товаров
    readPostsTemplate(data, '');

    //Проверка об успешном добавлении flash
    if (result['message']) {
      showFlash(result['message']);
    }

    // Изменяем заголовок страницы
    changePageTitle('Все посты');
  }).fail(function (data) {
    showError(data.responseJSON.message);
    // readPostsTemplate();
  });
}
