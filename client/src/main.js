window.addEventListener('DOMContentLoaded', () => {
  const articleFilterForm = document.getElementById('WeDevelop_Portfolio_CasesFilterForm');

  articleFilterForm.addEventListener('submit', (e) => {
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
