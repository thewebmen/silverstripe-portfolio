/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************!*\
  !*** ./client/src/main.js ***!
  \****************************/
window.addEventListener('DOMContentLoaded', function () {
  var allEntities = document.querySelectorAll('[data-filter]');
  var query = {};
  var queryEntityKey = document.querySelector('[data-all-entities]');
  if (queryEntityKey) {
    queryEntityKey = queryEntityKey.dataset.filterEntityName;
  }
  var params = new URLSearchParams(window.location.search);

  // Get location URL
  function getUrl() {
    var protocol = window.location.protocol;
    var host = window.location.host;
    var path = window.location.pathname;
    return "".concat(protocol, "//").concat(host).concat(path);
  }

  // Replace address URL
  function replaceURL(title, url) {
    window.history.pushState({}, title, url);
  }
  function setQueryParam() {
    Object.keys(query).forEach(function (keyName) {
      if (Array.isArray(query[keyName]) && query[keyName].length !== 0) {
        params.set(keyName, query[keyName].join(','));
      }
    });
    var queryString = params.toString();
    if (queryString) {
      return "".concat(getUrl(), "?").concat(queryString);
    }
    return getUrl();
  }

  // Check if filters already has been set
  if (params.has(queryEntityKey)) {
    var currentEntity = params.get(queryEntityKey).toString();
    if (!currentEntity) {
      params["delete"](queryEntityKey);
      replaceURL(document.title, setQueryParam());
      return;
    }
    params.forEach(function (value, key) {
      value.split(',').forEach(function (item) {
        if (!query[key]) {
          query[key] = [];
        }
        if (!query[key].includes(item)) {
          query[key].push(item);
        } else {
          query[key].splice(query[key].indexOf(item), 1);
        }
      });
    });
  }
  if (allEntities) {
    Array.from(allEntities).forEach(function (entity) {
      var clickHandler = function clickHandler(e) {
        e.preventDefault();
        var currentEntity = e.currentTarget.dataset.urlSegment;
        if (!currentEntity) {
          return;
        }
        if (!query[queryEntityKey]) {
          query[queryEntityKey] = [];
        }
        if (!query[queryEntityKey].includes(currentEntity)) {
          query[queryEntityKey].push(currentEntity);
        } else {
          query[queryEntityKey].splice(query[queryEntityKey].indexOf(currentEntity), 1);
        }
        if (query[queryEntityKey].length === 0) {
          params["delete"](queryEntityKey);
        }
        replaceURL(document.title, setQueryParam());
        window.location.reload();
      };
      entity.addEventListener('click', clickHandler);
    });
  }
  var casesFilterForm = document.getElementById('WeDevelop_Portfolio_CasesFilterForm');
  casesFilterForm?.addEventListener('submit', function (e) {
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
