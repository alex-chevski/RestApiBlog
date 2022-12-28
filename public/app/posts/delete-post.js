import { showPosts } from './read-posts.js';
jQuery(($) => {
  $(document).on('click', '.delete-post-button', function () {
    const post_id = $(this).attr('data-id');
    //bootbox для удобного подтверждения
    bootbox.confirm({
      message: '<h4>Вы уверены?</h4>',
      buttons: {
        confirm: {
          label: "<span class='glyphicon glyphicon-ok'></span> Да",
          className: 'btn-danger',
        },
        cancel: {
          label: "<span class='glyphicon glyphicon-remove'></span> Нет",
          className: 'btn-primary',
        },
      },

      callback: (result) => {
        //Запрос на удаление
        if (result == true) {
          $.ajax({
            url: 'http://10.0.2.10/posts/' + post_id,
            type: 'DELETE',
            dataType: 'json',
            success: (result) => {
              showPosts(result);
            },
            error: (xhr, resp, text) => {
              showError(xhr.responseJSON.message);

              // console.log(xhr, resp, text);
            },
          });
        }
      },
    });
  });
});
