//Выдает в QueryString queryParam value без ключа
export const getQueryParam = (item) => {
  var svalue = window.location.search.match(
    new RegExp('[?&]' + item + '=([^&]*)(&?)', 'i')
  );
  return svalue ? svalue[1] : svalue;
};
