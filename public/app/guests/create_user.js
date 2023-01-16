import { showError } from '../function/js/showError.js';
import { showPosts } from '../guests/read-posts.js';

jQuery(($) => {
  $(document).on('click', '#sign_up', () => {
    let sign_up_html = `

    <!-- Кнопка для показа всех товаров -->
    <div id="read-posts" class="btn btn-primary pull-right m-b-15px read-posts-button">
        <span class="glyphicon glyphicon-list"></span> Все посты
    </div>

                <h2>Регистрация</h2>
                <form id="sign_up_form">
                    <div class="form-group">
                        <label for="firstname">Никнейм</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" required />
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required />
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" name="password" id="password" autocomplete="on" required />
                    </div>

                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                </form>
            `;

    // clearResponse();
    $('#page-content').html(sign_up_html);
    changePageTitle('Регистрация пользователя');
  });

  //обработка формы
  $(document).on('submit', '#sign_up_form', function () {
    // Получаем данные формы
    const sign_up_form = $(this);
    const form_data = JSON.stringify(sign_up_form.serializeObject());

    // Отправка данных формы в API
    $.ajax({
      url: 'http://10.0.2.10/users/sign_up',
      type: 'POST',
      contentType: 'application/json',
      data: form_data,
      success: (result) => {
        showPosts(result);
        // В случае удачного завершения запроса к серверу,
        // сообщим пользователю, что он успешно зарегистрировался и очистим поля ввода
        // sign_up_form.find('input').val('');
      },
      error: (xhr, resp, text) => {
        // При ошибке сообщить пользователю, что регистрация не удалась
        showError(xhr.responseJSON.message);
      },
    });

    return false;
  });
});

export const showLoginPage = () => {
  // setCookie('jwt', '', 1);
  // Форма входа
  let login_html = `
		<!-- Кнопка для показа всех товаров -->
		<div id="read-posts" class="btn btn-primary pull-right m-b-15px read-posts-button">
			<span class="glyphicon glyphicon-list"></span> Все посты
		</div>

        <h2>Вход</h2>

        <form id="login_form">
            <div class="form-group">
                <label for="email">Email адрес либо Никнейм</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Введите email либо ваш никнейм">
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" autocomplete="on">
            </div>
            <button type="submit" class="btn btn-primary">Войти</button>
        </form>
    `;
  $('#page-content').html(login_html);
  changePageTitle('Вход в систему');
  // clearResponse();
  // showLoggedOutMenu();
};
