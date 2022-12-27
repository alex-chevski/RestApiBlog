export const showFlash = (success) => {
  $('#page-content').append(
    `<div class="alert alert-success">
      <p id="flash"></p>
  </div>`
  );

  $('#flash').html(success);
};
