jQuery(($) => {
  $(document).on('submit', '#search-post-form', function () {
    // Получаем ключевые слова для поиска
    const keywords = $(this).find("input[name='keywords']").val();

    $.ajax({
      url: 'http://10.0.2.10/posts',
      type: 'GET',
      dataType: 'json',
      data: { keywords },
      success: (result) => {
        // Шаблон в products.js
        readPostsTemplate(result, keywords);
        // Изменяем title
        changePageTitle('Поиск товаров: ' + keywords);
      },
    });

    // Предотвращаем перезагрузку всей страницы
    return false;
  });
});
