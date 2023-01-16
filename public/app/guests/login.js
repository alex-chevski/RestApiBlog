import { showLoginPage } from './create_user.js';
import { showPosts } from '../guests/read-posts.js';
import { setCookie } from '../function/js/setCookie.js';
import { showError } from '../function/js/showError.js';
jQuery(($) => {
  $(document).on('click', '#login', () => {
    showLoginPage();
  });

  $(document).on('submit', '#login_form', function () {
    const login_form = $(this);
    const form_data = JSON.stringify(login_form.serializeObject());

    $.ajax({
      url: 'http://10.0.2.10/users/sign_in',
      type: 'POST',
      contentType: 'application/json',
      data: form_data,
      success: (result) => {
        setCookie('jwt', result.jwt, 1);
        showPosts(result);

        location.reload();
      },

      error: (xhr) => {
        showError(xhr.responseJSON.message);
        login_form.find('input').val('');
      },
    });
    return false;
  });
});
