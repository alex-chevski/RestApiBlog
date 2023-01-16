import { setCookie } from '../function/js/setCookie.js';
// import { showPosts } from './read-posts.js';
$(document).on('click', '#logout', function () {
  setCookie('jwt', '', 1);
  // const result = { message: 'Вы вышли' };
  //перезагрузим страницу
  location.reload();
  // showPosts(result);
});
