/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************!*\
  !*** ./client/src/main.js ***!
  \****************************/
window.addEventListener('DOMContentLoaded', function () {
  var casesFilterForm = document.getElementById('WeDevelop_Portfolio_CasesFilterForm');
  casesFilterForm.addEventListener('submit', function (e) {
    var target = e.target;
    var formData = {};
    for (var i = 0; i < target.length; i += 1) {
      var element = target.elements[i];
      var name = element.name;
      if (element.type === 'text' && element.value) {
        formData[name] = element.value;
      }
      if (element.type === 'checkbox' && element.checked) {
        var _formData$name;
        name = element.name.substring(0, element.name.indexOf('['));
        formData[name] = (_formData$name = formData[name]) !== null && _formData$name !== void 0 ? _formData$name : [];
        formData[name].push(element.value);
      }
      if (element.type === 'select-one' && element.value) {
        formData[name] = element.value;
      }
    }

    // eslint-disable-next-line no-restricted-globals
    var currentURL = location.protocol + '//' + location.host + location.pathname;
    var newQueryParams = decodeURIComponent(new URLSearchParams(formData).toString());
    window.location.href = "".concat(currentURL, "?").concat(newQueryParams);
    e.preventDefault();
  });
});
/******/ })()
;
//# sourceMappingURL=main.js.map