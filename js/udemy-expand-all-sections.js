Array.from(
  document.querySelectorAll('button.js-panel-toggler[aria-expanded="false"]')
).forEach((el) => {
  el.click();
});
