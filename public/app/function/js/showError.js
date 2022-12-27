export const showError = (mes) => {
  $('.alert').remove();
  $('#page-content').append(
    `<div class="alert alert-danger">
		<p id="out_err"></p>
	</div>`
  );
  $('#out_err').html(mes);
};
