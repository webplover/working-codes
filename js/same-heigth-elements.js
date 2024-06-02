document.addEventListener("DOMContentLoaded", () => {
  function setMaxHeight(selector) {
    const elements = [...document.querySelectorAll(selector)];
    const maxHeight = Math.max(...elements.map((el) => el.offsetHeight));
    elements.forEach((el) => (el.style.height = `${maxHeight}px`));
  }

  // Call the function for different selectors
  setMaxHeight(".my-box");
});
