window.addEventListener('DOMContentLoaded', () => {
  const allEntities = document.querySelectorAll('[data-filter]');
  const query = {};
  let queryEntityKey = document.querySelector('[data-all-entities]');

  if (queryEntityKey) {
    queryEntityKey = queryEntityKey.dataset.filterEntityName;
  }

  const params = new URLSearchParams(window.location.search);

  // Get location URL
  function getUrl() {
    const { protocol } = window.location;
    const { host } = window.location;
    const path = window.location.pathname;
    return `${protocol}//${host}${path}`;
  }

  // Replace address URL
  function replaceURL(title, url) {
    window.history.pushState({}, title, url);
  }

  function setQueryParam() {
    Object.keys(query).forEach((keyName) => {
      if (Array.isArray(query[keyName]) && query[keyName].length !== 0) {
        params.set(keyName, query[keyName].join(','));
      }
    });
    const queryString = params.toString();

    if (queryString) {
      return `${getUrl()}?${queryString}`;
    }

    return getUrl();
  }

  // Check if filters already has been set
  if (params.has(queryEntityKey)) {
    const currentEntity = params.get(queryEntityKey).toString();
    if (!currentEntity) {
      params.delete(queryEntityKey);
      replaceURL(document.title, setQueryParam());
      return;
    }
    params.forEach((value, key) => {
      value.split(',').forEach((item) => {
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
    Array.from(allEntities).forEach((entity) => {
      const clickHandler = (e) => {
        e.preventDefault();
        const currentEntity = e.currentTarget.dataset.urlSegment;
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
          params.delete(queryEntityKey);
        }

        replaceURL(document.title, setQueryParam());
        window.location.reload();
      };

      entity.addEventListener('click', clickHandler);
    });
  }

  const casesFilterForm = document.getElementById('WeDevelop_Portfolio_CasesFilterForm');

  casesFilterForm.addEventListener('submit', (e) => {
    const { target } = e;
    const formData = {};

    for (let i = 0; i < target.length; i += 1) {
      const element = target.elements[i];
      let { name } = element;

      if (element.type === 'text' && element.value) {
        formData[name] = element.value;
      }

      if (element.type === 'checkbox' && element.checked) {
        name = element.name.substring(0, element.name.indexOf('['));
        formData[name] = formData[name] ?? [];
        formData[name].push(element.value);
      }

      if (element.type === 'select-one' && element.value) {
        formData[name] = element.value;
      }
    }

    // eslint-disable-next-line no-restricted-globals
    const currentURL = location.protocol + '//' + location.host + location.pathname;
    const newQueryParams = decodeURIComponent(new URLSearchParams(formData).toString());

    window.location.href = `${currentURL}?${newQueryParams}`;

    e.preventDefault();
  });

});
